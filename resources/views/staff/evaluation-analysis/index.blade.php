<x-admin-layout>
    <style>
        .ea-shell{width:100%;min-width:0;padding-bottom:32px;color:#101064}
        .ea-hero{isolation:isolate;position:relative;overflow:hidden;border-radius:28px;color:#fff;min-height:200px;background:linear-gradient(100deg,rgba(16,16,100,.97),rgba(16,16,100,.9) 48%,rgba(16,16,100,.71)),url('{{ asset('images/school.jpg') }}') center/cover;box-shadow:0 7px 20px rgba(16,16,100,.06)}
        .ea-hero:after{content:'';position:absolute;bottom:0;left:0;right:0;height:3px;background:#d4a017;z-index:2}
        .ea-circle{position:absolute;pointer-events:none;border:2px solid rgba(255,255,255,.24);border-radius:50%;width:410px;height:410px;top:-150px;right:-55px}.ea-circle:nth-child(2){width:300px;height:300px;top:-95px;right:0}.ea-circle:nth-child(3){width:185px;height:185px;top:-36px;right:58px}
        .ea-hero-inner{position:relative;z-index:1;display:flex;align-items:center;justify-content:space-between;gap:28px;padding:38px 36px;min-height:200px}
        .ea-kicker{display:flex;align-items:center;gap:10px;letter-spacing:.22em;text-transform:uppercase;font-weight:800;font-size:11px;color:#ecc45b}.ea-kicker:before{content:'';height:2px;width:28px;background:#d4a017}
        .ea-hero h1{font-size:32px;line-height:1.15;font-weight:800;letter-spacing:-.03em;margin:15px 0 10px}.ea-hero p{font-size:13px;line-height:1.8;color:rgba(255,255,255,.79);max-width:600px}
        .ea-hero-note{flex:0 0 205px;padding:15px 16px;border:1px solid rgba(255,255,255,.18);border-radius:16px;background:rgba(255,255,255,.1);backdrop-filter:blur(7px)}
        .ea-section-heading{margin:27px 0 14px}.ea-section-heading small{color:#bd8500;font-weight:800;letter-spacing:.22em;font-size:10px;text-transform:uppercase}.ea-section-heading h2{font-weight:800;font-size:18px;margin:4px 0}.ea-section-heading p{color:#657087;font-size:12px}
        .ea-metrics{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px}.ea-metric{padding:19px 20px;background:#fff;border:1px solid #e2e6ef;border-radius:16px;box-shadow:0 1px 4px rgba(16,16,100,.05)}.ea-metric-label{font-size:10px;font-weight:800;letter-spacing:.09em;text-transform:uppercase;color:#8792aa}.ea-metric strong{display:block;font-size:27px;font-weight:800;line-height:1.2;margin:10px 0 4px}.ea-metric span:last-child{font-size:11px;color:#79859b}
        .ea-card{border:1px solid #e2e6ef;border-radius:18px;background:#fff;overflow:hidden;box-shadow:0 2px 7px rgba(16,16,100,.04)}
        .ea-card-head{display:flex;justify-content:space-between;align-items:center;gap:16px;padding:20px 22px;border-bottom:1px solid #edf0f5}.ea-card-head h3{font-size:17px;font-weight:800}.ea-card-head p{font-size:12px;color:#718098;margin-top:4px}
        .ea-filters{display:grid;grid-template-columns:minmax(0,2fr) minmax(165px,1fr) minmax(130px,.65fr) auto;align-items:end;gap:12px;padding:18px 22px;background:#fbfcff;border-bottom:1px solid #ebedf3}
        .ea-field{display:block;min-width:0}.ea-field span{display:block;font-size:10px;letter-spacing:.08em;color:#64718e;text-transform:uppercase;font-weight:800;margin-bottom:7px}.ea-field input,.ea-field select{display:block;box-sizing:border-box;width:100%;min-height:42px;border:1px solid #dce3ef;border-radius:10px;background:#fff;padding:10px 12px;font-size:13px;color:#111651}.ea-field input:focus,.ea-field select:focus{outline:none;border-color:#101064;box-shadow:0 0 0 3px rgba(16,16,100,.08)}
        .ea-filter-actions{display:flex;gap:7px}.ea-btn{display:inline-flex;justify-content:center;align-items:center;gap:6px;text-decoration:none;cursor:pointer;border:1px solid transparent;border-radius:10px;padding:11px 15px;min-height:42px;font-weight:800;font-size:12px;white-space:nowrap}.ea-btn-primary{background:#101064;color:#fff}.ea-btn-primary:hover{background:#282887}.ea-btn-outline{background:#fff;color:#101064;border-color:#dce3ef}.ea-btn-outline:hover{background:#f5f6ff}.ea-btn-gold{background:#d4a017;color:#101064}.ea-btn-gold:hover{background:#e6b42c}
        .ea-table-wrap{overflow:auto}.ea-table{border-collapse:collapse;width:100%;text-align:left;min-width:760px}.ea-table th{background:#f8f9fc;border-bottom:1px solid #ebeff5;font-size:10px;letter-spacing:.1em;text-transform:uppercase;color:#8290a8;padding:14px 20px}.ea-table td{padding:17px 20px;border-bottom:1px solid #eef1f7;vertical-align:middle;font-size:12px}.ea-table tr:last-child td{border-bottom:0}.ea-table tbody tr:hover{background:#fdfdff}.ea-table strong{font-size:13px;color:#101064}.ea-table small{display:block;font-size:11px;color:#8490a5;margin-top:5px}
        .ea-status{display:inline-flex;align-items:center;padding:5px 10px;border-radius:99px;background:#edf0f9;color:#435070;font-size:10px;font-weight:800}.ea-status.published{background:#eaf9ef;color:#078c42}.ea-status.scheduled{background:#fff7df;color:#a57500}.ea-status.closed{background:#eef1f5;color:#48546c}.ea-status.draft{background:#eef0ff;color:#383bb4}
        .ea-table-actions{display:flex;gap:6px;flex-wrap:wrap}.ea-rows-footer{padding:15px 22px;color:#77839b;font-size:12px;display:flex;justify-content:space-between;gap:10px;align-items:center}
        @media(max-width:1120px){.ea-filters{grid-template-columns:minmax(0,2fr) minmax(165px,1fr)}.ea-hero-note{display:none}.ea-metrics{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media(max-width:640px){.ea-hero-inner{padding:30px 22px;min-height:185px}.ea-hero h1{font-size:27px}.ea-circle{transform:translateX(160px)}.ea-metrics{grid-template-columns:repeat(2,minmax(0,1fr));gap:8px}.ea-metric{padding:15px}.ea-metric strong{font-size:23px}.ea-filters{grid-template-columns:minmax(0,1fr);padding:16px}.ea-filter-actions{flex-wrap:wrap}.ea-card-head{padding:18px}.ea-rows-footer{padding:14px}}
        @media print{.ea-filters,.ea-filter-actions,.ea-table-actions{display:none!important}}
    </style>

    <div class="ea-shell">
        <section class="ea-hero" aria-label="Evaluation analysis header">
            <div aria-hidden="true"><span class="ea-circle"></span><span class="ea-circle"></span><span class="ea-circle"></span></div>
            <div class="ea-hero-inner">
                <div>
                    <div class="ea-kicker">Event Feedback & Insights</div>
                    <h1>Event Evaluation Analysis</h1>
                    <p>Review response trends, rating distributions, and anonymous student feedback for your department's events.</p>
                </div>
                <div class="ea-hero-note">
                    <div style="font-size:10px;letter-spacing:.15em;font-weight:800;color:#ecc45b;text-transform:uppercase">Analytics Workspace</div>
                    <div style="font-size:14px;font-weight:800;margin-top:6px">Questionnaire Reports</div>
                    <div style="font-size:11px;color:#fffbd9;margin-top:5px">Department-only access</div>
                </div>
            </div>
        </section>

        <div class="ea-section-heading"><small>Overview</small><h2>Evaluation Summary</h2><p>All indicators are limited to events owned by your department.</p></div>
        <section class="ea-metrics" aria-label="Evaluation metrics">
            <div class="ea-metric"><div class="ea-metric-label">Total Forms</div><strong>{{ $totalForms }}</strong><span>Questionnaires created</span></div>
            <div class="ea-metric"><div class="ea-metric-label">Published</div><strong style="color:#059c53">{{ $publishedForms }}</strong><span>Available for participants</span></div>
            <div class="ea-metric"><div class="ea-metric-label">Scheduled</div><strong style="color:#b98300">{{ $scheduledForms }}</strong><span>Awaiting publication</span></div>
            <div class="ea-metric"><div class="ea-metric-label">Total Responses</div><strong>{{ $totalResponses }}</strong><span>Submitted questionnaires</span></div>
        </section>

        <div class="ea-section-heading"><small>Questionnaire Directory</small><h2>Evaluation Reports</h2><p>Filter forms, open analysis, or download a clean CSV directory.</p></div>
        <section class="ea-card">
            <div class="ea-card-head">
                <div><h3>Department Evaluation Forms</h3><p>{{ $forms->total() }} matching questionnaire(s)</p></div>
                <a class="ea-btn ea-btn-outline" href="{{ route('staff.evaluation-analysis.index', array_merge(request()->except(['page', 'export']), ['export' => 'csv'])) }}">Download Directory CSV</a>
            </div>
            <form class="ea-filters" method="GET" action="{{ route('staff.evaluation-analysis.index') }}">
                <label class="ea-field"><span>Search Form / Event</span><input name="search" value="{{ $search }}" maxlength="120" placeholder="Title or event name..." /></label>
                <label class="ea-field"><span>Status</span><select name="status">
                    @foreach (['all' => 'All statuses', 'Draft' => 'Draft', 'Scheduled' => 'Scheduled', 'Published' => 'Published', 'Closed' => 'Closed'] as $value => $label)
                        <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                    @endforeach
                </select></label>
                <label class="ea-field"><span>Rows Per Page</span><select name="per_page">
                    @foreach ([10,25,50] as $count)<option value="{{ $count }}" @selected($perPage === $count)>{{ $count }} forms</option>@endforeach
                </select></label>
                <div class="ea-filter-actions"><button class="ea-btn ea-btn-primary" type="submit">Apply</button><a class="ea-btn ea-btn-outline" href="{{ route('staff.evaluation-analysis.index') }}">Reset</a></div>
            </form>
            <div class="ea-table-wrap"><table class="ea-table">
                <thead><tr><th>Questionnaire</th><th>Event</th><th>Status</th><th>Responses</th><th>Actions</th></tr></thead>
                <tbody>
                @forelse ($forms as $form)
                    <tr>
                        <td><strong>{{ $form->title }}</strong><small>Form #{{ $form->evaluation_form_id }}</small></td>
                        <td><strong>{{ $form->event?->event_name ?? 'Event unavailable' }}</strong><small>{{ $form->event?->event_date?->format('M d, Y') ?? 'No date' }}</small></td>
                        <td><span class="ea-status {{ strtolower($form->status) }}">{{ $form->status }}</span></td>
                        <td><strong>{{ $form->submissions_count }}</strong> <small>submission(s)</small></td>
                        <td><div class="ea-table-actions">
                            <a class="ea-btn ea-btn-primary" href="{{ route('staff.evaluation-analysis.show', $form->evaluation_form_id) }}">View Analysis</a>
                            <a class="ea-btn ea-btn-outline" href="{{ route('staff.evaluation-analysis.show', ['evaluationForm' => $form->evaluation_form_id, 'export' => 'csv']) }}">CSV</a>
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center;padding:44px 20px;color:#7e8aa0">No evaluation forms match your filters.</td></tr>
                @endforelse
                </tbody>
            </table></div>
            <div class="ea-rows-footer"><span>Showing {{ $forms->firstItem() ?? 0 }}–{{ $forms->lastItem() ?? 0 }} of {{ $forms->total() }} forms</span><span>CSV = clean data table · PDF = styled printable report</span></div>
        </section>
        <div style="margin-top:16px">{{ $forms->links() }}</div>
    </div>
</x-admin-layout>
