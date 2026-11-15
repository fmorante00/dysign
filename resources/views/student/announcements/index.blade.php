<x-admin-layout>
    <style>
        /* Scoped Student Announcements styles: no Vite rebuild is required. */
        .dya-page { --dya-navy:#101064; --dya-gold:#D4A017; --dya-ink:#152554; --dya-muted:#69768d; color:var(--dya-ink); }
        .dya-hero { position:relative; isolation:isolate; overflow:hidden; border-radius:26px; background:#101064; color:#fff; padding:32px; border-bottom:4px solid #e2b32e; box-shadow:0 8px 20px rgba(15,25,79,.08); }
        .dya-hero::before { content:""; position:absolute; inset:0; z-index:-1; background:linear-gradient(100deg,rgba(13,16,84,.97),rgba(17,21,101,.93) 53%,rgba(17,21,101,.65)),url('{{ asset('images/school.jpg') }}') center/cover no-repeat; }
        .dya-eyebrow { color:#e9bb41; font-size:11px; font-weight:800; letter-spacing:.22em; text-transform:uppercase; }
        .dya-hero h1 { font-size:clamp(24px,2.35vw,34px); font-weight:850; line-height:1.17; letter-spacing:-.03em; margin:12px 0 10px; }
        .dya-hero p { color:#e0e4fb; font-size:13px; line-height:1.7; max-width:640px; }
        .dya-hero-chip { border:1px solid rgba(255,255,255,.2); background:rgba(255,255,255,.12); padding:12px 16px; border-radius:15px; min-width:130px; }
        .dya-stats { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:14px; }
        .dya-stat { background:#fff; border:1px solid #e1e5ee; border-radius:16px; box-shadow:0 2px 2px rgba(22,34,72,.05); padding:18px 19px; min-width:0; }
        .dya-stat-label { color:#5e708e; font-size:12px; font-weight:650; }
        .dya-stat-value { font-size:28px; line-height:1; font-weight:850; color:var(--dya-navy); margin:13px 0 7px; }
        .dya-stat-note { color:#79869d; font-size:11px; line-height:1.5; }
        .dya-panel { background:#fff; border:1px solid #e0e5ef; border-radius:18px; box-shadow:0 1px 2px rgba(22,34,72,.04); overflow:hidden; }
        .dya-panel-header { background:linear-gradient(110deg,#101064,#252575); padding:20px 24px; color:white; }
        .dya-section-title { font-size:19px; font-weight:800; letter-spacing:-.02em; color:var(--dya-navy); }
        .dya-filters { display:grid; grid-template-columns:minmax(190px,2fr) minmax(140px,1.2fr) minmax(130px,1fr) minmax(145px,1fr); gap:13px; }
        .dya-field { min-width:0; display:flex; flex-direction:column; gap:7px; color:#4a5670; font-size:12px; font-weight:700; }
        .dya-input { background:white; color:#182354; width:100%; height:42px; min-width:0; border:1px solid #d4dce9; border-radius:11px; padding:0 12px; font-size:13px; font-weight:500; outline:none; }
        .dya-input:focus { border-color:#4343a0; box-shadow:0 0 0 3px rgba(32,32,128,.1); }
        .dya-filter-foot { display:flex; align-items:end; flex-wrap:wrap; gap:12px; margin-top:14px; }
        .dya-filter-foot .dya-field { width:160px; }
        .dya-btn { display:inline-flex; align-items:center; justify-content:center; gap:7px; min-height:40px; padding:0 14px; font-size:12px; font-weight:750; border-radius:10px; cursor:pointer; border:1px solid #d8deeb; color:#101064; background:white; }
        .dya-btn:hover:not(:disabled) { background:#eff1ff; }
        .dya-btn:disabled { opacity:.45; cursor:not-allowed; }
        .dya-btn-primary { color:white; background:#101064; border-color:#101064; }
        .dya-btn-primary:hover:not(:disabled) { background:#242481; }
        .dya-result-row { display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:8px; margin:18px 2px 12px; color:#69768d; font-size:12px; }
        .dya-entry { background:#fff; border:1px solid #e0e5ef; border-radius:17px; overflow:hidden; box-shadow:0 2px 2px rgba(18,27,72,.04); margin-bottom:13px; }
        .dya-entry[open] { border-color:#cbd3e5; box-shadow:0 6px 20px rgba(17,30,83,.07); }
        .dya-entry > summary { display:block; list-style:none; cursor:pointer; padding:19px 20px 18px; border-left:4px solid #d4a017; }
        .dya-entry > summary::-webkit-details-marker { display:none; }
        .dya-entry > summary:focus-visible { outline:3px solid #d4a017; outline-offset:-4px; }
        .dya-entry-title { margin:8px 0 5px; font-size:17px; font-weight:800; line-height:1.35; color:#101064; overflow-wrap:anywhere; }
        .dya-entry-excerpt { color:#62718a; font-size:12px; line-height:1.65; margin:10px 0 0; overflow-wrap:anywhere; }
        .dya-tag { display:inline-flex; align-items:center; border-radius:20px; padding:5px 10px; background:#f0f2ff; color:#232374; font-size:10px; font-weight:750; max-width:100%; overflow-wrap:anywhere; }
        .dya-tag-gold { background:#fff6df; color:#916500; }
        .dya-entry-body { border-top:1px solid #e7eaf1; padding:19px 24px 23px; }
        .dya-entry-message { white-space:pre-wrap; overflow-wrap:anywhere; font-size:13px; line-height:1.8; color:#38465f; }
        .dya-attachment-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:12px; margin-top:14px; }
        .dya-attachment { display:block; overflow:hidden; border:1px solid #dee4ef; border-radius:12px; background:#f8faff; text-decoration:none; color:#101064; transition:border-color .15s,transform .15s; min-width:0; }
        .dya-attachment:hover { border-color:#d4a017; transform:translateY(-1px); }
        .dya-attachment img { display:block; width:100%; height:132px; object-fit:cover; background:#e6e9f4; }
        .dya-file-label { padding:10px 12px; display:flex; align-items:center; gap:9px; min-width:0; }
        .dya-file-label span:last-child { min-width:0; overflow-wrap:anywhere; font-size:12px; font-weight:750; }
        .dya-plain-file { display:flex; align-items:center; gap:12px; padding:16px 12px; min-height:74px; }
        .dya-plain-file b { display:block; font-size:12px; overflow-wrap:anywhere; }
        .dya-plain-file small { color:#7c879c; font-size:11px; }
        .dya-pager { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; margin:18px 2px 0; }
        .dya-page-numbers { display:flex; flex-wrap:wrap; gap:6px; align-items:center; }
        .dya-page-numbers .dya-btn { min-width:39px; padding:0 10px; }
        .dya-state { border:1px dashed #d7dcea; border-radius:16px; background:#fff; padding:36px 22px; text-align:center; color:#66738a; }
        .dya-state h3 { color:#101064; font-weight:800; font-size:17px; margin:0 0 7px; }
        .dya-hidden { display:none!important; }
        @media (max-width:1060px) { .dya-filters { grid-template-columns:repeat(2,minmax(0,1fr)); } .dya-stats { grid-template-columns:repeat(2,minmax(0,1fr)); } .dya-attachment-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } }
        @media (max-width:620px) { .dya-hero { padding:26px 21px; border-radius:19px; } .dya-hero-chip { width:100%; } .dya-filters { grid-template-columns:1fr; } .dya-filter-foot .dya-field { flex:1 1 140px; } .dya-stat { padding:16px 13px; } .dya-stat-value { font-size:25px; } .dya-panel-header { padding:17px; } .dya-entry > summary { padding:16px; } .dya-entry-body { padding:17px; } .dya-attachment-grid { grid-template-columns:1fr; } .dya-pager { justify-content:center; } }
    </style>

    <div class="dya-page w-full min-w-0 space-y-5 pb-10" id="dyaPage">
        <section class="dya-hero" aria-labelledby="dyaPageTitle">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <div class="dya-eyebrow">DySign · Student Portal</div>
                    <h1 id="dyaPageTitle">My Announcements</h1>
                    <p>Stay informed about the events and updates shared with your student account.</p>
                </div>
                <div class="dya-hero-chip shrink-0" aria-live="polite">
                    <div class="text-[10px] font-bold uppercase tracking-[.15em] text-amber-200">Your Inbox</div>
                    <div id="dyaHeroCount" class="mt-1 text-xl font-extrabold">—</div>
                </div>
            </div>
        </section>

        <section class="dya-stats" aria-label="Announcement summary">
            <div class="dya-stat"><div class="dya-stat-label">Total Announcements</div><div class="dya-stat-value" id="dyaTotal">—</div><div class="dya-stat-note">Shared with your account</div></div>
            <div class="dya-stat"><div class="dya-stat-label">With Attachments</div><div class="dya-stat-value" id="dyaWithFiles">—</div><div class="dya-stat-note">Announcements with files</div></div>
            <div class="dya-stat"><div class="dya-stat-label">Events</div><div class="dya-stat-value" id="dyaEvents">—</div><div class="dya-stat-note">Distinct events in your inbox</div></div>
            <div class="dya-stat"><div class="dya-stat-label">This Month</div><div class="dya-stat-value" id="dyaThisMonth">—</div><div class="dya-stat-note">Announcements posted this month</div></div>
        </section>

        <section class="dya-panel" aria-labelledby="dyaSectionHeading">
            <div class="dya-panel-header">
                <div class="dya-eyebrow">Updates &amp; Communications</div>
                <h2 id="dyaSectionHeading" class="mt-1 text-lg font-extrabold">Announcement Inbox</h2>
                <p class="mt-1 text-xs text-indigo-100">Search and filter your updates. Expand an announcement to read the full message and open its attachments.</p>
            </div>
            <div class="p-4 sm:p-6">
                <div class="dya-filters">
                    <label class="dya-field" for="dyaSearch">Search announcements
                        <input id="dyaSearch" class="dya-input" type="search" placeholder="Event, subject, message..." autocomplete="off">
                    </label>
                    <label class="dya-field" for="dyaEvent">Event
                        <select id="dyaEvent" class="dya-input"><option value="">All events</option></select>
                    </label>
                    <label class="dya-field" for="dyaFiles">Attachments
                        <select id="dyaFiles" class="dya-input"><option value="">All announcements</option><option value="yes">With attachments</option><option value="no">Without attachments</option></select>
                    </label>
                    <label class="dya-field" for="dyaSort">Sort by
                        <select id="dyaSort" class="dya-input"><option value="newest">Newest first</option><option value="oldest">Oldest first</option><option value="event">Event name A–Z</option></select>
                    </label>
                </div>
                <div class="dya-filter-foot">
                    <label class="dya-field" for="dyaStart">Posted from
                        <input id="dyaStart" class="dya-input" type="date">
                    </label>
                    <label class="dya-field" for="dyaEnd">Posted to
                        <input id="dyaEnd" class="dya-input" type="date">
                    </label>
                    <label class="dya-field" for="dyaPerPage">Per page
                        <select id="dyaPerPage" class="dya-input"><option value="5">5 records</option><option value="10">10 records</option><option value="20">20 records</option></select>
                    </label>
                    <button id="dyaClear" class="dya-btn" type="button">Clear filters</button>
                </div>
            </div>
        </section>

        <div id="dyaLoading" class="dya-state" role="status"><h3>Loading announcements...</h3><p class="text-sm">Fetching the updates shared with your account.</p></div>
        <div id="dyaError" class="dya-state dya-hidden" role="alert"><h3>Could not load announcements</h3><p id="dyaErrorText" class="text-sm mb-3">Please try again.</p><button type="button" id="dyaRetry" class="dya-btn dya-btn-primary">Retry</button></div>
        <div id="dyaEmpty" class="dya-state dya-hidden"><h3>No announcements yet</h3><p class="text-sm">Updates intended for your account will appear here.</p></div>
        <div id="dyaNoMatches" class="dya-state dya-hidden"><h3>No matching announcements</h3><p class="text-sm">Try another keyword or clear your filters.</p></div>
        <section id="dyaResults" class="dya-hidden" aria-label="Announcement list">
            <div class="dya-result-row"><p id="dyaResultCount" role="status" aria-live="polite"></p><p id="dyaPageLabel"></p></div>
            <div id="dyaList"></div>
            <nav id="dyaPagination" class="dya-pager" aria-label="Announcement pages"></nav>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const root = document.getElementById('dyaPage');
            if (!root) return;
            const byId = id => document.getElementById(id);
            let announcements = [];
            let currentPage = 1;
            const expanded = new Set();
            let initializedExpansion = false;
            const filters = ['dyaSearch','dyaEvent','dyaFiles','dyaSort','dyaStart','dyaEnd','dyaPerPage'];

            function escapeHtml(value) {
                return String(value ?? '').replace(/[&<>"']/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[char]));
            }
            function dateValue(value) {
                if (!value) return null;
                const date = new Date(value);
                return Number.isNaN(date.getTime()) ? null : date;
            }
            function localDay(date) {
                if (!date) return '';
                const y = date.getFullYear();
                return `${y}-${String(date.getMonth()+1).padStart(2,'0')}-${String(date.getDate()).padStart(2,'0')}`;
            }
            function prettyDate(value) {
                const date = dateValue(value);
                return date ? date.toLocaleString('en-PH',{month:'short',day:'numeric',year:'numeric',hour:'numeric',minute:'2-digit'}) : 'Date unavailable';
            }
            function eventName(item) { return String(item?.event?.event_name || 'General announcement'); }
            function eventKey(item) { return String(item?.event_id ?? item?.event?.event_id ?? eventName(item)); }
            function attachments(item) { return Array.isArray(item.attachments) ? item.attachments : []; }
            function safeUrl(item, attachment) {
                const announcementId = Number(item.announcement_id);
                const attachmentId = Number(attachment.announcement_attachment_id);
                if (!Number.isSafeInteger(announcementId) || announcementId < 1 || !Number.isSafeInteger(attachmentId) || attachmentId < 1) return null;
                return `/student/announcements/${announcementId}/attachments/${attachmentId}`;
            }
            function renderAttachments(item) {
                const files = attachments(item);
                if (!files.length) return '';
                return `<div class="mt-6 border-t border-slate-100 pt-5"><h4 class="mb-2 text-xs font-extrabold uppercase tracking-widest text-slate-500">Attachments (${files.length})</h4><div class="dya-attachment-grid">${files.map(file => {
                    const url = safeUrl(item,file);
                    if (!url) return '';
                    const mime = String(file.mime_type || '').toLowerCase();
                    const name = escapeHtml(file.file_name || 'Attachment');
                    if (mime.startsWith('image/')) return `<a class="dya-attachment" href="${url}" target="_blank" rel="noopener noreferrer" aria-label="View ${name} in new tab"><img src="${url}" alt="${name}" loading="lazy"><span class="dya-file-label"><span aria-hidden="true">🖼️</span><span>${name}</span></span></a>`;
                    const pdf = mime === 'application/pdf';
                    return `<a class="dya-attachment dya-plain-file" href="${url}" target="_blank" rel="noopener noreferrer"><span aria-hidden="true" style="font-size:25px">${pdf?'📄':'📎'}</span><span style="min-width:0"><b>${name}</b><small>${pdf?'Open PDF':'View attachment'} in a new tab</small></span></a>`;
                }).join('')}</div><p class="mt-3 text-[11px] text-slate-500">Files are available only to authorized recipients.</p></div>`;
            }
            function renderEntry(item) {
                const name = eventName(item);
                const message = String(item.message || '');
                const text = message.replace(/\s+/g,' ').trim();
                const preview = text.length > 165 ? text.slice(0,165).trimEnd() + '…' : text;
                const files = attachments(item);
                const key = String(item.announcement_id);
                const isOpen = expanded.has(key);
                return `<details class="dya-entry" data-announcement="${escapeHtml(key)}" ${isOpen?'open':''}>
                    <summary>
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="min-w-0 flex-1"><span class="dya-tag dya-tag-gold">${escapeHtml(name)}</span><h3 class="dya-entry-title">${escapeHtml(item.subject || 'Announcement')}</h3></div>
                            <span class="text-[11px] font-medium text-slate-500">${escapeHtml(prettyDate(item.created_at || item.sent_at))}</span>
                        </div>
                        <p class="dya-entry-excerpt">${escapeHtml(preview || 'Open to view this announcement.')}</p>
                        <div class="mt-3 flex flex-wrap items-center justify-between gap-2"><span class="dya-tag">${files.length ? `${files.length} attachment${files.length===1?'':'s'}` : 'Message only'}</span><span class="text-xs font-extrabold text-[#101064]">${isOpen?'Hide details':'Read announcement'} <span aria-hidden="true">${isOpen?'⌃':'⌄'}</span></span></div>
                    </summary>
                    <div class="dya-entry-body"><div class="mb-3 text-[11px] font-bold uppercase tracking-wider text-slate-500">Full announcement</div><div class="dya-entry-message">${escapeHtml(message)}</div>${renderAttachments(item)}</div>
                </details>`;
            }
            function setVisible(id,visible) { byId(id).classList.toggle('dya-hidden', !visible); }
            function currentFiltered() {
                const search = byId('dyaSearch').value.trim().toLocaleLowerCase();
                const event = byId('dyaEvent').value;
                const files = byId('dyaFiles').value;
                const start = byId('dyaStart').value;
                const end = byId('dyaEnd').value;
                const sort = byId('dyaSort').value;
                const filtered = announcements.filter(item => {
                    const searchable = [item.subject,item.message,eventName(item)].join(' ').toLocaleLowerCase();
                    if (search && !searchable.includes(search)) return false;
                    if (event && eventKey(item) !== event) return false;
                    if (files === 'yes' && !attachments(item).length) return false;
                    if (files === 'no' && attachments(item).length) return false;
                    const date = localDay(dateValue(item.created_at || item.sent_at));
                    if (start && (!date || date < start)) return false;
                    if (end && (!date || date > end)) return false;
                    return true;
                });
                filtered.sort((a,b) => {
                    if (sort === 'event') return eventName(a).localeCompare(eventName(b)) || (dateValue(b.created_at)?.getTime() || 0)-(dateValue(a.created_at)?.getTime() || 0);
                    const delta = (dateValue(a.created_at || a.sent_at)?.getTime() || 0) - (dateValue(b.created_at || b.sent_at)?.getTime() || 0);
                    return (sort === 'oldest' ? delta : -delta) || (Number(b.announcement_id) - Number(a.announcement_id));
                });
                return filtered;
            }
            function pageButton(text,page,disabled,active=false,ariaLabel='') {
                return `<button type="button" class="dya-btn ${active?'dya-btn-primary':''}" data-page="${page}" ${disabled?'disabled':''} ${active?'aria-current="page"':''} ${ariaLabel?`aria-label="${escapeHtml(ariaLabel)}"`:''}>${text}</button>`;
            }
            function render() {
                if (!announcements.length) {
                    setVisible('dyaResults',false); setVisible('dyaEmpty',true); setVisible('dyaNoMatches',false); return;
                }
                const records = currentFiltered();
                setVisible('dyaEmpty',false);
                setVisible('dyaNoMatches',!records.length);
                setVisible('dyaResults',!!records.length);
                if (!records.length) return;
                const perPage = Number(byId('dyaPerPage').value) || 5;
                const pages = Math.max(1,Math.ceil(records.length/perPage));
                currentPage = Math.max(1,Math.min(currentPage,pages));
                const startIndex = (currentPage-1)*perPage;
                const onPage = records.slice(startIndex,startIndex+perPage);
                if (!initializedExpansion && onPage.length) { expanded.add(String(onPage[0].announcement_id)); initializedExpansion = true; }
                byId('dyaList').innerHTML = onPage.map(renderEntry).join('');
                byId('dyaResultCount').textContent = `Showing ${startIndex+1}–${Math.min(records.length,startIndex+perPage)} of ${records.length} matching announcement${records.length===1?'':'s'}`;
                byId('dyaPageLabel').textContent = `Page ${currentPage} of ${pages}`;
                const nav = byId('dyaPagination');
                if (pages === 1) { nav.innerHTML=''; return; }
                const first = Math.max(1,Math.min(currentPage-2,pages-4));
                const last = Math.min(pages,first+4);
                let pageNumbers = '';
                for (let p=first;p<=last;p++) pageNumbers += pageButton(String(p),p,false,p===currentPage,`Page ${p}`);
                nav.innerHTML = `<div class="text-xs text-slate-500">${records.length} results · ${perPage} per page</div><div class="dya-page-numbers">${pageButton('Previous',currentPage-1,currentPage===1,false,'Previous page')}${pageNumbers}${pageButton('Next',currentPage+1,currentPage===pages,false,'Next page')}</div>`;
            }
            function refreshStats() {
                const now = new Date();
                const month = `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}`;
                byId('dyaTotal').textContent = announcements.length;
                byId('dyaWithFiles').textContent = announcements.filter(item=>attachments(item).length).length;
                byId('dyaEvents').textContent = new Set(announcements.map(eventKey)).size;
                byId('dyaThisMonth').textContent = announcements.filter(item=>localDay(dateValue(item.created_at || item.sent_at)).startsWith(month)).length;
                byId('dyaHeroCount').textContent = `${announcements.length} announcement${announcements.length===1?'':'s'}`;
                const select = byId('dyaEvent');
                const options = new Map();
                announcements.forEach(item => options.set(eventKey(item),eventName(item)));
                select.innerHTML = '<option value="">All events</option>' + [...options].sort((a,b)=>a[1].localeCompare(b[1])).map(([value,label])=>`<option value="${escapeHtml(value)}">${escapeHtml(label)}</option>`).join('');
            }
            async function load() {
                setVisible('dyaLoading',true); setVisible('dyaError',false); setVisible('dyaResults',false); setVisible('dyaEmpty',false); setVisible('dyaNoMatches',false);
                try {
                    const response = await fetch('/api/student/announcements', {credentials:'same-origin',headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}});
                    const payload = await response.json();
                    if (!response.ok || !payload.success || !Array.isArray(payload.data)) throw new Error(payload.message || 'Unable to retrieve your announcements.');
                    announcements = payload.data;
                    currentPage=1; expanded.clear(); initializedExpansion=false;
                    refreshStats(); render();
                } catch(error) {
                    byId('dyaErrorText').textContent = error.message || 'Please try again.';
                    setVisible('dyaError',true);
                } finally { setVisible('dyaLoading',false); }
            }
            filters.forEach(id => {
                byId(id).addEventListener(id==='dyaSearch'?'input':'change',()=>{ currentPage=1; render(); });
            });
            byId('dyaClear').addEventListener('click',()=>{
                byId('dyaSearch').value=''; byId('dyaEvent').value=''; byId('dyaFiles').value='';
                byId('dyaSort').value='newest'; byId('dyaStart').value=''; byId('dyaEnd').value=''; byId('dyaPerPage').value='5';
                currentPage=1; render();
            });
            byId('dyaRetry').addEventListener('click',load);
            byId('dyaList').addEventListener('toggle', event => {
                const entry=event.target;
                if (!(entry instanceof HTMLDetailsElement) || !entry.matches('.dya-entry')) return;
                const key = entry.dataset.announcement;
                if (entry.open) expanded.add(key); else expanded.delete(key);
                const text = entry.querySelector('summary > div:last-child > span:last-child');
                if (text) text.innerHTML = entry.open ? 'Hide details <span aria-hidden="true">⌃</span>' : 'Read announcement <span aria-hidden="true">⌄</span>';
            },true);
            byId('dyaPagination').addEventListener('click',event => {
                const button = event.target.closest('button[data-page]');
                if (!button || button.disabled) return;
                currentPage = Number(button.dataset.page) || 1;
                render();
            });
            load();
        });
    </script>
</x-admin-layout>
