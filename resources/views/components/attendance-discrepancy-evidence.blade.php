@if($item->documents->isNotEmpty())
    <div class="mt-4 rounded-xl border border-indigo-100 bg-white p-3">
        <p class="mb-3 text-xs font-bold text-[#101064]">
            Supporting Event Photos ({{ $item->documents->count() }})
        </p>

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
            @foreach($item->documents as $document)
                @php
                    $evidenceUrl = route($routeName, [
                        'attendanceDiscrepancy' => $item->discrepancy_id,
                        'document' => $document->document_id,
                    ]);
                @endphp

                <a href="{{ $evidenceUrl }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="group block overflow-hidden rounded-xl border border-slate-200 bg-slate-50 hover:border-[#D4A017]"
                   title="Open supporting evidence">

                    <img src="{{ $evidenceUrl }}"
                         alt="Student-submitted event evidence"
                         loading="lazy"
                         class="h-28 w-full object-cover">

                    <p class="truncate px-2 py-2 text-xs font-medium text-[#101064]">
                        {{ $document->file_name }}
                    </p>
                </a>
            @endforeach
        </div>

        <p class="mt-3 text-xs text-slate-500">
            Uploaded photos are supporting evidence only.
            Verify attendance independently before making a decision.
        </p>
    </div>
@endif