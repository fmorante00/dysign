<x-admin-layout>
    <div class="space-y-6">
        <section class="relative overflow-hidden rounded-[26px] border-b-4 border-[#D4A017] bg-[#101064] px-7 py-9 text-white sm:px-9">
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#E7C75B]">DySign · Authorized student directory</p>
            <h1 class="mt-3 text-3xl font-bold">Student Directory</h1>
            <p class="mt-2 max-w-2xl text-sm text-white/75">
                {{ $user->department?->is_school_wide ? 'School-wide student access for the Supreme Student Council.' : 'Only students within your assigned department are displayed.' }}
            </p>
            <p class="mt-4 text-xs font-semibold text-[#E7C75B]">{{ $user->department?->department_name ?? 'No department assigned' }}</p>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-[#101064]">Student Registry</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ number_format($students->total()) }} matching students</p>
                </div>
                <form action="{{ route('department.students.index') }}" method="GET" class="flex flex-wrap gap-2">
                    <input name="search" type="search" value="{{ $search }}" maxlength="100" placeholder="Name, number or program" class="min-w-48 flex-1 rounded-xl border-slate-300 text-sm focus:border-[#D4A017] focus:ring-[#D4A017]" />
                    <button class="rounded-xl bg-[#101064] px-5 py-2.5 text-sm font-semibold text-white" type="submit">Search</button>
                    @if ($search !== '')
                        <a href="{{ route('department.students.index') }}" class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm">Reset</a>
                    @endif
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Student</th>
                        <th class="px-5 py-4">Department</th>
                        <th class="px-5 py-4">Program / Year</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4 text-right">Action</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse($students as $student)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4">
                                <p class="font-semibold text-[#101064]">{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $student->student_number }}</p>
                            </td>
                            <td class="px-5 py-4">{{ $student->college }}</td>
                            <td class="px-5 py-4">{{ $student->program_code }} · Year {{ $student->year_level }}</td>
                            <td class="px-5 py-4">{{ $student->status }}</td>
                            <td class="px-5 py-4 text-right"><a href="{{ route('department.students.show', $student->student_id) }}" class="font-semibold text-[#101064] underline">View</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-14 text-center text-slate-500">No students match your department or search. Confirm the student college labels in the database.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if($students->hasPages())
                <div class="border-t border-slate-100 p-5">{{ $students->links() }}</div>
            @endif
        </section>
    </div>
</x-admin-layout>
