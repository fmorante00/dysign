<x-admin-layout>
    <div class="space-y-6">
        <section class="relative overflow-hidden rounded-[26px] border-b-4 border-[#D4A017] bg-[#101064] px-7 py-9 text-white sm:px-9">
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#E7C75B]">DySign · Authorized student details</p>
            <h1 class="mt-3 text-3xl font-bold">{{ $studentRecord->first_name }} {{ $studentRecord->middle_name }} {{ $studentRecord->last_name }}</h1>
            <p class="mt-2 text-sm text-white/75">{{ $studentRecord->student_number }} · {{ $studentRecord->college }}</p>
            <a href="{{ route('department.students.index') }}" class="mt-5 inline-block rounded-lg bg-[#D4A017] px-4 py-2 text-sm font-semibold text-[#101064]">Back to directory</a>
        </section>
        <section class="grid gap-4 sm:grid-cols-2">
            @foreach([
                'Student Number' => $studentRecord->student_number,
                'Department / College' => $studentRecord->college,
                'Program' => $studentRecord->program_name,
                'Program Code' => $studentRecord->program_code,
                'Year Level' => $studentRecord->year_level,
                'Student Status' => $studentRecord->student_status,
                'Record Status' => $studentRecord->status,
            ] as $label => $value)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-500">{{ $label }}</p>
                    <p class="mt-2 text-base font-semibold text-[#101064]">{{ $value ?: 'Not recorded' }}</p>
                </div>
            @endforeach
        </section>
    </div>
</x-admin-layout>
