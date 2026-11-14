<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DySign Evaluation Report — {{ $form->title }}</title>
    <style>
        :root{--navy:#101064;--gold:#d4a017;--muted:#617089;--line:#e0e5ef}
        *{box-sizing:border-box}body{font-family:Arial,Helvetica,sans-serif;margin:0;background:#eef0f5;color:#142047;font-size:12px}.report{width:min(900px,calc(100% - 30px));margin:24px auto;background:#fff;box-shadow:0 4px 25px #15155712}.top{background:var(--navy);color:white;padding:25px 34px;display:flex;align-items:center;gap:16px;border-bottom:4px solid var(--gold)}.logo{width:60px;height:60px;object-fit:contain;border-radius:50%;background:white;padding:4px}.identity{font-size:22px;font-weight:800;letter-spacing:-.02em}.identity small{display:block;font-weight:400;font-size:10px;letter-spacing:.08em;margin-top:4px;color:#e9e9ff}.doc-tag{margin-left:auto;text-align:right;font-size:10px;font-weight:bold;color:#e8c25c;text-transform:uppercase;letter-spacing:.14em}.content{padding:30px 35px}.eyebrow{font-size:10px;letter-spacing:.18em;font-weight:bold;color:#a87900;text-transform:uppercase}.report h1{font-size:25px;color:var(--navy);margin:8px 0}.sub{color:#65718c;line-height:1.8;margin-bottom:25px}.meta{border:1px solid var(--line);border-radius:9px;padding:17px;display:grid;grid-template-columns:1fr 1fr;gap:13px;margin-bottom:24px}.meta b{display:block;color:var(--navy);margin-bottom:4px}.meta span{color:#66748e}.metrics{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-bottom:24px}.metric{border:1px solid var(--line);border-radius:9px;padding:14px}.metric .label{font-size:9px;color:#68758e;text-transform:uppercase;letter-spacing:.1em;font-weight:700}.metric strong{display:block;font-size:22px;color:var(--navy);margin-top:6px}.section-title{border-bottom:2px solid var(--gold);font-size:17px;padding-bottom:9px;margin-top:25px;color:var(--navy)}.question{break-inside:avoid;page-break-inside:avoid;border:1px solid var(--line);border-radius:9px;padding:18px 18px 14px;margin:12px 0}.question h3{font-size:14px;margin:0 0 5px;color:var(--navy)}.question .qmeta{font-size:10px;color:var(--muted);margin-bottom:13px}.dist{width:100%;border-collapse:collapse;margin-top:10px}.dist th,.dist td{text-align:left;border-bottom:1px solid #e7eaf1;padding:8px 9px;vertical-align:top}.dist th{background:#f6f7fa;color:#566179;font-size:10px}.dist td.count{text-align:right;white-space:nowrap}.comment{padding:10px 12px;background:#f7f8fb;border-left:3px solid var(--gold);border-radius:5px;color:#45526b;margin:8px 0;white-space:pre-wrap;overflow-wrap:anywhere}.foot{padding:18px 35px;border-top:1px solid var(--line);color:#7a8599;display:flex;justify-content:space-between;gap:10px;font-size:10px}.buttons{width:min(900px,calc(100% - 30px));margin:22px auto 12px;display:flex;justify-content:space-between;gap:12px}.btn{background:var(--navy);color:#fff;border:0;border-radius:9px;padding:11px 16px;cursor:pointer;font-size:12px;font-weight:bold}.btn.secondary{color:var(--navy);background:#fff;border:1px solid #d4daea}.note{background:#fffaf1;border:1px solid #ead8a2;padding:11px 14px;border-radius:6px;color:#66562b;margin:20px 0;font-size:11px;line-height:1.6}
        @media(max-width:670px){.top{padding:20px}.content{padding:20px}.foot{padding:20px}.metrics{grid-template-columns:repeat(2,1fr)}.meta{grid-template-columns:1fr}.doc-tag{display:none}}
        @page{size:A4;margin:13mm}@media print{body{background:#fff;-webkit-print-color-adjust:exact;print-color-adjust:exact}.report{width:100%;margin:0;box-shadow:none}.buttons{display:none}.top,.question,.metric{break-inside:avoid}a{text-decoration:none;color:inherit}}
    </style>
</head>
<body>
    <div class="buttons">
        <a class="btn secondary" href="{{ route('staff.evaluation-analysis.show', $form->evaluation_form_id) }}">← Back to Analysis</a>
        <button class="btn" type="button" onclick="window.print()">Print / Save as PDF</button>
    </div>
    <main class="report">
        <header class="top">
            <img class="logo" src="{{ asset('images/logo.png') }}" alt="DySign school logo">
            <div class="identity">DySign <small>Digital Identity Management System</small></div>
            <div class="doc-tag">Event Evaluation<br>Analysis Report</div>
        </header>
        <div class="content">
            <div class="eyebrow">Official Questionnaire Summary</div>
            <h1>{{ $form->title }}</h1>
            <p class="sub">An anonymized summary of event evaluation results prepared by DySign.</p>
            <div class="meta">
                <div><b>Event</b><span>{{ $form->event?->event_name ?? 'Unavailable' }}</span></div>
                <div><b>Event Date</b><span>{{ $form->event?->event_date?->format('F d, Y') ?? 'N/A' }}</span></div>
                <div><b>Evaluation Status</b><span>{{ $form->status }}</span></div>
                <div><b>Generated</b><span>{{ now()->format('F d, Y · g:i A') }}</span></div>
            </div>
            <div class="metrics">
                <div class="metric"><div class="label">Responses</div><strong>{{ $submissionCount }}</strong></div>
                <div class="metric"><div class="label">Eligible</div><strong>{{ $eligibleCount }}</strong></div>
                <div class="metric"><div class="label">Response Rate</div><strong>{{ $responseRate === null ? 'N/A' : $responseRate . '%' }}</strong></div>
                <div class="metric"><div class="label">Average Rating</div><strong>{{ $overallRating === null ? 'N/A' : $overallRating }}</strong></div>
            </div>
            <div class="note">Response rate is based on the eligible student roster. Question percentages exclude unanswered items; multiple-choice totals can exceed 100%. No student identities are included.</div>
            <h2 class="section-title">Question-by-Question Results</h2>
            @forelse($results as $index => $result)
                <section class="question">
                    <h3>{{ $index + 1 }}. {{ $result['question']->question_text }}</h3>
                    <div class="qmeta">{{ $result['question']->question_type }} · {{ $result['answered'] }} of {{ $submissionCount }} answered @if($result['average'] !== null) · Average {{ $result['average'] }} @endif</div>
                    @if(count($result['distribution']))
                        <table class="dist"><thead><tr><th>Answer / Option</th><th>Count</th><th>Share</th></tr></thead><tbody>
                        @foreach($result['distribution'] as $choice)
                            <tr><td>{{ $choice['label'] }}</td><td class="count">{{ $choice['count'] }}</td><td class="count">{{ $choice['percent'] }}%</td></tr>
                        @endforeach
                        </tbody></table>
                    @elseif(in_array($result['question']->question_type, ['Short Answer','Paragraph']))
                        @forelse($result['comments'] as $comment)
                            <div class="comment">{{ $comment }}</div>
                        @empty<p style="color:#7e899d">No written responses.</p>@endforelse
                        @if($result['answered'] > count($result['comments']))
                            <p style="color:#7e899d;font-size:10px">Showing the first {{ count($result['comments']) }} anonymized comments.</p>
                        @endif
                    @else<p style="color:#7e899d">No responses recorded.</p>@endif
                </section>
            @empty
                <p>No evaluation questions are available.</p>
            @endforelse
        </div>
        <footer class="foot"><span>DySign • Event Participation Management System</span><span>Generated {{ now()->format('Y-m-d H:i') }} • Internal use</span></footer>
    </main>
</body>
</html>
