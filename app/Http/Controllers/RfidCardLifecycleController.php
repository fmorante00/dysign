<?php

namespace App\Http\Controllers;

use App\Models\RfidCardAssignment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RfidCardLifecycleController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAdministrator();

        $search = trim((string) $request->query('search', ''));
        $students = Student::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('student_number', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('program_code', 'like', "%{$search}%");
                });
            })
            ->orderBy('student_number')
            ->paginate(20)
            ->withQueryString();

        $studentIds = $students->getCollection()->pluck('student_id');
        $latestCards = RfidCardAssignment::whereIn('student_id', $studentIds)
            ->orderBy('rfid_card_assignment_id')
            ->get()
            ->keyBy('student_id');

        $history = RfidCardAssignment::with(['student', 'assignedBy', 'endedBy'])
            ->orderByDesc('rfid_card_assignment_id')
            ->limit(50)
            ->get();

        $counts = [
            'active' => RfidCardAssignment::where('status', 'Active')->count(),
            'lost' => RfidCardAssignment::where('status', 'Lost')->count(),
            'replaced' => RfidCardAssignment::where('status', 'Replaced')->count(),
        ];

        return view('rfid-cards.index', compact('students', 'latestCards', 'history', 'counts', 'search'));
    }

    public function reportLost(Request $request, int $student)
    {
        $this->authorizeAdministrator();
        $data = $request->validate([
            'reason' => ['required', 'string', 'min:3', 'max:500'],
        ]);

        DB::transaction(function () use ($student, $data) {
            $studentRecord = Student::whereKey($student)->lockForUpdate()->firstOrFail();
            $card = RfidCardAssignment::where('student_id', $studentRecord->student_id)
                ->where('status', 'Active')
                ->lockForUpdate()
                ->first();

            if (!$card || $card->rfid_identifier !== $studentRecord->rfid_identifier) {
                throw ValidationException::withMessages([
                    'card' => 'No matching active RFID assignment was found. Check the imported RFID data first.',
                ]);
            }

            $card->update([
                'status' => 'Lost',
                'ended_at' => now(),
                'reason' => trim($data['reason']),
                'ended_by' => auth()->user()->user_id,
            ]);
            // Keep students.rfid_identifier until replacement: schema is NOT NULL.
            // The scanner now checks the lifecycle status and rejects this UID.
        }, 3);

        return redirect()->route('rfid-cards.index')
            ->with('success', 'Lost RFID card disabled. Previous attendance records were preserved.');
    }

    public function replace(Request $request, int $student)
    {
        $this->authorizeAdministrator();
        $data = $request->validate([
            'rfid_identifier' => [
                'required', 'string', 'regex:/^\d{10}$/',
                Rule::unique('students', 'rfid_identifier'),
                Rule::unique('rfid_card_assignments', 'rfid_identifier'),
            ],
            'reason' => ['required', 'string', 'min:3', 'max:500'],
        ], [
            'rfid_identifier.regex' => 'The RFID UID must contain exactly 10 digits.',
        ]);

        DB::transaction(function () use ($student, $data) {
            $studentRecord = Student::whereKey($student)->lockForUpdate()->firstOrFail();

            if ($studentRecord->status !== 'Active') {
                throw ValidationException::withMessages([
                    'student' => 'Cannot assign a new RFID to an inactive student record.',
                ]);
            }

            $latest = RfidCardAssignment::where('student_id', $studentRecord->student_id)
                ->orderByDesc('rfid_card_assignment_id')
                ->lockForUpdate()
                ->first();

            if (!$latest || $latest->rfid_identifier !== $studentRecord->rfid_identifier
                || !in_array($latest->status, ['Active', 'Lost'], true)) {
                throw ValidationException::withMessages([
                    'card' => 'The current RFID assignment is not synchronized. Resolve this before replacement.',
                ]);
            }

            $newUid = trim($data['rfid_identifier']);
            if ($newUid === $studentRecord->rfid_identifier
                || Student::where('rfid_identifier', $newUid)->exists()
                || RfidCardAssignment::where('rfid_identifier', $newUid)->exists()) {
                throw ValidationException::withMessages([
                    'rfid_identifier' => 'RFID UID is already assigned or has been used previously.',
                ]);
            }

            if ($latest->status === 'Active') {
                $latest->update([
                    'status' => 'Replaced',
                    'ended_at' => now(),
                    'reason' => trim($data['reason']),
                    'ended_by' => auth()->user()->user_id,
                ]);
            }

            $studentRecord->rfid_identifier = $newUid;
            $studentRecord->save();

            RfidCardAssignment::create([
                'student_id' => $studentRecord->student_id,
                'rfid_identifier' => $newUid,
                'status' => 'Active',
                'assigned_at' => now(),
                'reason' => trim($data['reason']),
                'assigned_by' => auth()->user()->user_id,
            ]);
            // Existing attendance_records rows remain unchanged.
        }, 3);

        return redirect()->route('rfid-cards.index')
            ->with('success', 'Replacement RFID registered and previous assignment deactivated.');
    }

    /**
     * Authorize a UID imported through a Registrar file. Until approved,
     * such a UID cannot be used for attendance scanning.
     */
    public function approveImported(Request $request, int $student)
    {
        $this->authorizeAdministrator();
        $data = $request->validate([
            'reason' => ['required', 'string', 'min:3', 'max:400'],
        ]);

        DB::transaction(function () use ($student, $data) {
            $record = Student::whereKey($student)->lockForUpdate()->firstOrFail();
            $uid = (string) $record->rfid_identifier;

            if ($record->status !== 'Active' || !preg_match('/^\d{10}$/', $uid)) {
                throw ValidationException::withMessages([
                    'card' => 'The student must be active and the imported RFID must contain 10 digits.',
                ]);
            }

            $latest = RfidCardAssignment::where('student_id', $record->student_id)
                ->orderByDesc('rfid_card_assignment_id')
                ->lockForUpdate()
                ->first();

            if ($latest && $latest->rfid_identifier === $uid && $latest->status === 'Active') {
                throw ValidationException::withMessages(['card' => 'This card is already active.']);
            }

            // Never reactivate any UID that already appears in card history.
            if (RfidCardAssignment::where('rfid_identifier', $uid)->exists()) {
                throw ValidationException::withMessages([
                    'card' => 'The imported UID was previously assigned or disabled. It cannot be reused.',
                ]);
            }

            if ($latest && $latest->status === 'Active') {
                $latest->update([
                    'status' => 'Replaced',
                    'ended_at' => now(),
                    'reason' => 'Superseded by Administrator-approved Registrar import. ' . trim($data['reason']),
                    'ended_by' => auth()->user()->user_id,
                ]);
            }

            RfidCardAssignment::create([
                'student_id' => $record->student_id,
                'rfid_identifier' => $uid,
                'status' => 'Active',
                'assigned_at' => now(),
                'reason' => trim($data['reason']),
                'assigned_by' => auth()->user()->user_id,
            ]);
        }, 3);

        return redirect()->route('rfid-cards.index')
            ->with('success', 'Imported RFID card approved for scanning. Prior attendance remains unchanged.');
    }

    private function authorizeAdministrator(): void
    {
        abort_unless(auth()->user()?->role?->role_name === 'Administrator', 403);
    }
}
