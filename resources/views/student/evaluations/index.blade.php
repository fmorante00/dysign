<x-admin-layout>
    <style>
        .dy-eval-hero {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            background-image:
                radial-gradient(circle at 95% 12%, transparent 0 85px, rgba(255,255,255,.16) 86px 88px, transparent 89px 142px, rgba(255,255,255,.11) 143px 145px, transparent 146px),
                linear-gradient(105deg, rgba(16,16,100,.98) 0%, rgba(16,16,100,.92) 54%, rgba(16,16,100,.71) 100%),
                url('{{ asset('images/school.jpg') }}');
            background-size: cover;
            background-position: center;
        }
        .dy-eval-option { display:flex; align-items:flex-start; gap:12px; border:1px solid #e2e8f0; border-radius:13px; padding:13px 15px; background:#fff; cursor:pointer; transition:background .15s,border-color .15s; }
        .dy-eval-option:hover { background:#f7f8ff; border-color:#b9bee8; }
        .dy-eval-option:has(input:checked) { background:#f1f2ff; border-color:#101064; }
        .dy-eval-option input { accent-color:#101064; margin-top:3px; flex-shrink:0; }
        .dy-eval-option:focus-within { outline:2px solid #d4a017; outline-offset:2px; }
        .dy-eval-question { border:1px solid #e2e8f0; border-radius:18px; padding:20px; background:#fff; }
        .dy-eval-question.dy-eval-invalid { border-color:#dc2626; box-shadow:0 0 0 3px rgba(220,38,38,.07); }
        .dy-eval-input { display:block; width:100%; border:1px solid #cbd5e1; border-radius:12px; padding:12px 14px; background:#fff; color:#1e293b; font-size:14px; }
        .dy-eval-input:focus { outline:2px solid rgba(212,160,23,.55); border-color:#101064; }
        .dy-eval-rating { display:flex; align-items:center; justify-content:center; min-width:46px; height:46px; padding:0 10px; border:1px solid #cbd5e1; border-radius:12px; font-size:14px; font-weight:800; color:#101064; cursor:pointer; background:white; transition:background .15s,color .15s; }
        .dy-eval-rating:hover { border-color:#101064; background:#eef0ff; }
        .dy-eval-rating:has(input:checked) { color:white; background:#101064; border-color:#101064; }
        .dy-eval-rating:focus-within { outline:2px solid #d4a017; outline-offset:2px; }
        .dy-eval-rating input { position:absolute; opacity:0; width:1px; height:1px; }
        .dy-eval-card { border:1px solid #e2e8f0; border-radius:20px; background:#fff; overflow:hidden; box-shadow:0 2px 4px rgba(15,23,42,.035); display:flex; flex-direction:column; min-width:0; }
        .dy-eval-card:hover { border-color:#cdd4e6; box-shadow:0 7px 20px rgba(15,23,42,.055); }
        /* Independent layout rules: these must work even before Vite regenerates Tailwind utilities. */
        #responseModal {
            position: fixed;
            inset: 0;
            z-index: 20000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
            overflow: hidden;
            background: rgba(2, 6, 23, .67);
            -webkit-backdrop-filter: blur(2px);
            backdrop-filter: blur(2px);
        }
        #responseModal[aria-hidden="false"] { display: flex; }
        #responseDialog {
            box-sizing: border-box;
            width: 100%;
            max-width: 768px;
            min-height: 0;
            max-height: calc(100vh - 32px);
            max-height: calc(100dvh - 32px);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border-radius: 18px;
            background: white;
            box-shadow: 0 24px 75px rgba(0,0,0,.3);
        }
        #responseDialog > header, #responseDialog > footer { flex: 0 0 auto; }
        #responseAnswers {
            flex: 1 1 auto;
            min-height: 0;
            overflow-x: hidden;
            overflow-y: auto;
            overscroll-behavior: contain;
            scrollbar-gutter: stable;
        }
        @media (max-width: 639px) {
            #responseModal { padding: 10px; }
            #responseDialog { max-height: calc(100vh - 20px); max-height: calc(100dvh - 20px); border-radius: 14px; }
        }
        #evaluationSection { scroll-margin-top:20px; }
        @media (max-width:639px) {
            .dy-eval-question { padding:15px; }
            .dy-eval-option { padding:12px; }
            .dy-eval-rating { min-width:40px; height:42px; }
        }
        @media (prefers-reduced-motion:reduce) { .dy-eval-option, .dy-eval-card, .dy-eval-rating { transition:none; } }
    </style>

    <div class="w-full min-w-0 space-y-6 pb-10" id="dyEvaluationsApp">
        <section class="dy-eval-hero rounded-[28px] border-b-4 border-[#D4A017] px-7 py-9 text-white shadow-sm sm:px-9" aria-labelledby="dyEvalTitle">
            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="min-w-0">
                    <p class="text-xs font-bold uppercase tracking-[.23em] text-[#F2C94C]">DySign &middot; Student Account</p>
                    <h1 id="dyEvalTitle" class="mt-3 text-3xl font-extrabold tracking-tight">My Event Evaluations</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-indigo-100">Share feedback about events you attended. Review your answers anytime after submitting an evaluation.</p>
                    <span class="mt-5 inline-flex max-w-full items-center rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold text-white/90">Attendance-based eligibility &middot; One submission per evaluation</span>
                </div>
                <div class="grid w-full max-w-sm shrink-0 grid-cols-2 gap-3" aria-label="Evaluation summary">
                    <div class="rounded-2xl border border-white/20 bg-white/10 px-4 py-4">
                        <p class="text-[10px] font-bold uppercase tracking-[.13em] text-[#F2C94C]">Available</p>
                        <p id="availableCount" class="mt-2 text-3xl font-extrabold tabular-nums">—</p>
                        <p class="mt-1 text-[11px] text-indigo-100">Awaiting response</p>
                    </div>
                    <div class="rounded-2xl border border-white/20 bg-white/10 px-4 py-4">
                        <p class="text-[10px] font-bold uppercase tracking-[.13em] text-[#F2C94C]">Submitted</p>
                        <p id="submittedCount" class="mt-2 text-3xl font-extrabold tabular-nums">—</p>
                        <p class="mt-1 text-[11px] text-indigo-100">Viewable anytime</p>
                    </div>
                </div>
            </div>
        </section>

        <div id="successAlert" role="status" aria-live="polite" class="hidden rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800"></div>
        <div id="errorAlert" role="alert" class="hidden rounded-xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-medium text-rose-800"></div>

        <section id="evaluationBrowseSection" class="min-w-0" aria-labelledby="evaluationBrowseHeading">
            <div class="mb-5 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[.19em] text-[#A9780B]">Student Feedback</p>
                    <h2 id="evaluationBrowseHeading" class="mt-1 text-xl font-extrabold text-[#101064]">Event Evaluations</h2>
                    <p class="mt-1 text-xs leading-5 text-slate-500">Published evaluations for events where you have qualifying recorded attendance.</p>
                </div>
                <div class="flex flex-wrap gap-2" role="group" aria-label="Filter evaluations">
                    <button type="button" data-eval-filter="all" aria-pressed="true" class="rounded-xl bg-[#101064] px-4 py-2.5 text-xs font-bold text-white">All</button>
                    <button type="button" data-eval-filter="available" aria-pressed="false" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-600">Available</button>
                    <button type="button" data-eval-filter="submitted" aria-pressed="false" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-600">Submitted</button>
                </div>
            </div>
            <div id="loadingState" class="rounded-2xl border border-slate-200 bg-white px-6 py-14 text-center text-sm text-slate-500" role="status">Loading your evaluations...</div>
            <div id="emptyState" class="hidden rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-xl text-[#101064]">✓</div>
                <h3 class="text-base font-bold text-[#101064]">No evaluations found</h3>
                <p id="emptyStateMessage" class="mt-2 text-sm text-slate-500">Published event evaluations will appear here.</p>
            </div>
            <div id="evaluationList" class="hidden grid min-w-0 grid-cols-1 gap-4 lg:grid-cols-2 2xl:grid-cols-3"></div>
        </section>

        <section id="evaluationSection" class="hidden min-w-0 overflow-visible rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="formTitle">
            <header class="rounded-t-2xl border-b border-slate-100 px-5 py-6 sm:px-7">
                <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                    <button type="button" id="backToEvaluations" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-xs font-bold text-[#101064] hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#D4A017]">
                        <span aria-hidden="true">←</span> Back to Evaluations
                    </button>
                    <span id="formStatus" class="rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700">Published</span>
                </div>
                <p class="text-[11px] font-bold uppercase tracking-[.18em] text-[#A9780B]">Event Evaluation Form</p>
                <h2 id="formTitle" class="mt-2 break-words text-2xl font-extrabold text-[#101064]"></h2>
                <p id="formEvent" class="mt-2 text-sm font-semibold text-slate-600"></p>
                <p id="formDescription" class="mt-3 hidden max-w-3xl whitespace-pre-line text-sm leading-6 text-slate-500"></p>
            </header>
            <form id="evaluationForm" class="space-y-6 p-5 sm:p-7" novalidate>
                <div class="rounded-xl border border-indigo-100 bg-indigo-50/50 px-4 py-4">
                    <div class="mb-2 flex flex-wrap items-center justify-between gap-2">
                        <p class="text-xs font-bold text-[#101064]">Evaluation Progress</p>
                        <p id="evaluationProgressText" class="text-xs font-semibold text-slate-600">0 answered</p>
                    </div>
                    <div class="h-2 overflow-hidden rounded-full bg-indigo-100" role="progressbar" aria-label="Answered questions" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" id="evaluationProgressBarContainer">
                        <div id="evaluationProgressBar" class="h-full w-0 rounded-full bg-[#D4A017] transition-all"></div>
                    </div>
                    <p class="mt-2 text-xs text-slate-500">Questions marked <span class="font-bold text-rose-600">*</span> are required.</p>
                </div>
                <div id="questionContainer" class="min-w-0 space-y-4"></div>
                <div id="submittedNotice" class="hidden rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">You have already submitted this evaluation. You can review your original answers from the list.</div>
                <div id="submitArea" class="flex flex-col items-start justify-between gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:items-center">
                    <p class="text-xs leading-5 text-slate-500">Please review your answers carefully. Evaluations can only be submitted once.</p>
                    <button type="submit" id="submitButton" class="w-full rounded-xl bg-[#101064] px-7 py-3 text-sm font-bold text-white transition hover:bg-[#262681] focus:outline-none focus:ring-2 focus:ring-[#D4A017] disabled:cursor-not-allowed disabled:opacity-60 sm:w-auto">Submit Evaluation</button>
                </div>
            </form>
        </section>
    </div>

    <div id="responseModal" class="dy-eval-response-modal fixed inset-0 z-[8000] hidden items-center justify-center bg-slate-950/65 p-3 backdrop-blur-[2px] sm:p-5" role="presentation" aria-hidden="true">
        <div id="responseDialog" class="flex max-h-[90dvh] w-full max-w-3xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl" role="dialog" aria-modal="true" aria-labelledby="responseModalTitle" tabindex="-1">
            <header class="relative flex shrink-0 items-start justify-between gap-4 bg-[#101064] px-5 py-5 text-white sm:px-7">
                <div class="absolute inset-x-0 bottom-0 h-1 bg-[#D4A017]"></div>
                <div class="min-w-0">
                    <p class="text-[11px] font-bold uppercase tracking-[.19em] text-[#F2C94C]">Submitted Evaluation</p>
                    <h2 id="responseModalTitle" class="mt-2 break-words text-xl font-extrabold">My Evaluation Response</h2>
                    <p id="responseSubmittedAt" class="mt-1 text-xs text-indigo-100"></p>
                </div>
                <button type="button" id="closeResponseModal" aria-label="Close response" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-white/20 text-2xl text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-[#F2C94C]">×</button>
            </header>
            <div id="responseAnswers" class="min-h-0 space-y-4 overflow-y-auto p-5 sm:p-7" role="region" aria-label="Your submitted answers"></div>
            <footer class="shrink-0 border-t border-slate-100 bg-slate-50 px-5 py-4 text-right sm:px-7">
                <button type="button" id="closeResponseFooter" class="rounded-xl bg-[#101064] px-6 py-2.5 text-sm font-bold text-white hover:bg-[#25257c]">Close</button>
            </footer>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            'use strict';
            document.title = 'My Event Evaluations | DySign';
            const $ = id => document.getElementById(id);
            const elements = {
                list: $('evaluationList'), browse: $('evaluationBrowseSection'), loading: $('loadingState'), empty: $('emptyState'), emptyMessage: $('emptyStateMessage'),
                available: $('availableCount'), submitted: $('submittedCount'), section: $('evaluationSection'), form: $('evaluationForm'),
                title: $('formTitle'), event: $('formEvent'), description: $('formDescription'), questions: $('questionContainer'),
                submittedNotice: $('submittedNotice'), submitArea: $('submitArea'), submitButton: $('submitButton'),
                progress: $('evaluationProgressBar'), progressContainer: $('evaluationProgressBarContainer'), progressText: $('evaluationProgressText'),
                success: $('successAlert'), error: $('errorAlert'), modal: $('responseModal'), modalTitle: $('responseModalTitle'),
                modalDate: $('responseSubmittedAt'), modalAnswers: $('responseAnswers'), modalDialog: $('responseDialog')
            };
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
            let evaluations = [], currentForm = null, activeFilter = 'all', opening = false, submitting = false;
            // Fixed dialogs must live outside the main scroll container.
            // This changes only the modal DOM location; it does not alter form data or API calls.
            document.body.appendChild(elements.modal);
            let modalFocusReturn = null;
            const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[char]));
            const asId = value => Number.isSafeInteger(Number(value)) && Number(value) > 0 ? Number(value) : null;
            const formatType = type => ({'Yes No':'Yes / No','Single Choice':'Single Choice','Multiple Choice':'Multiple Choice','Short Answer':'Short Answer','Paragraph':'Paragraph','Rating':'Rating'})[type] ?? String(type ?? 'Question');
            const quietScroll = node => { if (!node) return; node.scrollIntoView({block:'start',behavior:window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth'}); };
            const notify = (type, message) => { if (window.DySignUI?.notify) window.DySignUI.notify(type, message); };
            const setAlert = (type, message) => {
                elements.success.classList.add('hidden'); elements.error.classList.add('hidden');
                if (!message) return;
                const target = type === 'success' ? elements.success : elements.error;
                target.textContent = message; target.classList.remove('hidden');
            };
            const getError = data => {
                const first = data?.errors && Object.values(data.errors).flat().find(Boolean);
                return String(first || data?.message || 'Unable to complete the request. Please try again.');
            };
            async function jsonRequest(url, options = {}) {
                const response = await fetch(url, { credentials:'same-origin', ...options, headers:{Accept:'application/json','X-Requested-With':'XMLHttpRequest', ...(options.headers || {})} });
                const data = await response.json().catch(() => ({}));
                if (!response.ok || data.success === false) throw new Error(getError(data));
                return data.data;
            }
            function filterButtons() {
                document.querySelectorAll('[data-eval-filter]').forEach(button => {
                    const selected = button.dataset.evalFilter === activeFilter;
                    button.setAttribute('aria-pressed', String(selected));
                    button.className = selected
                        ? 'rounded-xl bg-[#101064] px-4 py-2.5 text-xs font-bold text-white'
                        : 'rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-50';
                });
            }
            function renderCard(form) {
                const id = asId(form.evaluation_form_id);
                if (!id) return '';
                const submitted = Boolean(form.already_submitted), count = Array.isArray(form.questions) ? form.questions.length : 0;
                const badge = submitted ? 'bg-emerald-50 text-emerald-700' : 'bg-indigo-50 text-[#101064]';
                const title = escapeHtml(form.title || 'Untitled Evaluation');
                const event = escapeHtml(form.event?.event_name || 'Event');
                const description = form.description ? `<p class="mt-3 line-clamp-2 whitespace-pre-line text-xs leading-5 text-slate-500">${escapeHtml(form.description)}</p>` : '';
                return `<article class="dy-eval-card" aria-label="${title}">
                    <div class="flex flex-1 flex-col px-5 py-5 sm:px-6">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <span class="rounded-full px-3 py-1 text-[11px] font-bold ${badge}">${submitted ? 'Submitted' : 'Available'}</span>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold text-slate-600">${count} ${count === 1 ? 'Question' : 'Questions'}</span>
                        </div>
                        <h3 class="mt-5 break-words text-base font-extrabold text-[#101064]">${title}</h3>
                        <p class="mt-2 text-xs font-semibold text-slate-600">${event}</p>${description}
                        <p class="mt-auto pt-5 text-xs text-slate-500">${submitted ? 'Your response is saved. You can view it anytime.' : 'Ready for your feedback.'}</p>
                    </div>
                    <div class="border-t border-slate-100 px-5 py-4 sm:px-6">
                        <button type="button" data-eval-action="${submitted ? 'response' : 'answer'}" data-eval-id="${id}" class="w-full rounded-xl ${submitted ? 'border border-[#101064] bg-white text-[#101064] hover:bg-indigo-50' : 'bg-[#101064] text-white hover:bg-[#24247c]'} px-5 py-3 text-sm font-bold transition focus:outline-none focus:ring-2 focus:ring-[#D4A017]">${submitted ? 'View My Response' : 'Answer Evaluation'}</button>
                    </div>
                </article>`;
            }
            function renderList() {
                const visible = evaluations.filter(form => activeFilter === 'all' || Boolean(form.already_submitted) === (activeFilter === 'submitted'));
                elements.loading.classList.add('hidden');
                elements.list.innerHTML = visible.map(renderCard).join('');
                elements.list.classList.toggle('hidden', visible.length === 0);
                elements.empty.classList.toggle('hidden', visible.length > 0);
                elements.emptyMessage.textContent = evaluations.length === 0 ? 'Your eligible, published evaluations will appear here when available.' : 'No evaluations match the selected filter.';
            }
            async function loadEvaluations() {
                elements.loading.classList.remove('hidden');
                elements.list.classList.add('hidden'); elements.empty.classList.add('hidden');
                try {
                    const result = await jsonRequest('/api/student/evaluations');
                    evaluations = Array.isArray(result) ? result : [];
                    elements.available.textContent = evaluations.filter(form => !form.already_submitted).length;
                    elements.submitted.textContent = evaluations.filter(form => form.already_submitted).length;
                    renderList();
                } catch (error) {
                    elements.loading.classList.add('hidden');
                    setAlert('error', error.message);
                    elements.emptyMessage.textContent = 'We could not load your evaluations. Please refresh the page.';
                    elements.empty.classList.remove('hidden');
                }
            }
            function renderOption(questionId, value, label, inputType, dataAttribute, suffix = '') {
                const name = inputType === 'checkbox' ? `multi_${questionId}[]` : `${dataAttribute}_${questionId}`;
                const dataAttr = dataAttribute === 'data-option-id' ? `data-option-id="${escapeHtml(value)}"` : 'data-boolean-value';
                return `<label class="dy-eval-option"><input type="${inputType}" name="${escapeHtml(name)}" value="${escapeHtml(value)}" ${dataAttr}><span class="min-w-0 break-words text-sm leading-5 text-slate-700">${escapeHtml(label)}</span></label>`;
            }
            function renderQuestion(question, index) {
                const id = asId(question.evaluation_question_id);
                if (!id) return '';
                const type = String(question.question_type || '');
                const required = Boolean(question.is_required);
                let input = '';
                if (type === 'Short Answer') input = '<input data-answer-value maxlength="1000" type="text" autocomplete="off" class="dy-eval-input mt-4" placeholder="Type your answer">';
                else if (type === 'Paragraph') input = '<textarea data-answer-value rows="4" maxlength="5000" class="dy-eval-input mt-4 resize-y" placeholder="Share your feedback..."></textarea>';
                else if (type === 'Yes No') input = `<div class="mt-4 grid gap-2 sm:grid-cols-2">${renderOption(id,'1','Yes','radio','data-boolean-value')}${renderOption(id,'0','No','radio','data-boolean-value')}</div>`;
                else if (type === 'Single Choice' || type === 'Multiple Choice') {
                    const multi = type === 'Multiple Choice';
                    input = `<div class="mt-4 grid gap-2">${(question.options || []).map(option => renderOption(id,option.evaluation_question_option_id,option.option_text,multi ? 'checkbox' : 'radio','data-option-id')).join('')}</div>`;
                } else if (type === 'Rating') {
                    const min = Math.max(0,Number(question.rating_min ?? 1)), max = Math.min(20, Number(question.rating_max ?? 5));
                    let items = '';
                    for (let rating = min; rating <= max; rating++) items += `<label class="dy-eval-rating"><input type="radio" data-rating-value name="rating_${id}" value="${rating}"><span>${rating}</span></label>`;
                    input = `<div class="mt-4 flex flex-wrap gap-2">${items}</div><div class="mt-2 flex flex-wrap justify-between gap-2 text-xs text-slate-500"><span>${escapeHtml(question.rating_min_label || '')}</span><span>${escapeHtml(question.rating_max_label || '')}</span></div>`;
                } else input = '<p class="mt-3 text-sm text-amber-700">This question format is not supported by the current form.</p>';
                return `<article class="dy-eval-question evaluation-question" data-question-id="${id}" data-question-type="${escapeHtml(type)}" data-required="${required ? 'true' : 'false'}">
                    <div class="flex items-start gap-3"><span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-[#101064] text-xs font-extrabold text-white">${index+1}</span>
                    <div class="min-w-0 flex-1"><div class="flex flex-wrap items-start justify-between gap-2"><h3 class="min-w-0 flex-1 break-words text-sm font-bold leading-6 text-[#101064]">${escapeHtml(question.question_text)} ${required ? '<span class="text-rose-600" aria-label="required">*</span>' : ''}</h3><span class="rounded-md bg-indigo-50 px-2 py-1 text-[10px] font-bold uppercase tracking-wide text-indigo-800">${escapeHtml(formatType(type))}</span></div>
                    ${input}<p class="dy-eval-question-error mt-2 hidden text-xs font-semibold text-rose-600">Please answer this required question.</p></div></div>
                </article>`;
            }
            function questionAnswered(article) {
                const type = article.dataset.questionType;
                if (type === 'Short Answer' || type === 'Paragraph') return Boolean(article.querySelector('[data-answer-value]')?.value.trim());
                if (type === 'Yes No') return Boolean(article.querySelector('[data-boolean-value]:checked'));
                if (type === 'Rating') return Boolean(article.querySelector('[data-rating-value]:checked'));
                if (type === 'Single Choice' || type === 'Multiple Choice') return Boolean(article.querySelector('[data-option-id]:checked'));
                return false;
            }
            function updateProgress() {
                const questions = [...elements.questions.querySelectorAll('.evaluation-question')];
                const answered = questions.filter(questionAnswered).length;
                const percentage = questions.length ? Math.round(answered * 100 / questions.length) : 0;
                elements.progress.style.width = `${percentage}%`;
                elements.progressContainer.setAttribute('aria-valuenow', String(percentage));
                elements.progressText.textContent = `${answered} of ${questions.length} answered`;
            }
            async function openEvaluation(id) {
                if (opening || submitting) return;
                opening = true; setAlert(null, '');
                try {
                    const form = await jsonRequest(`/api/student/evaluations/${id}`);
                    currentForm = form;
                    elements.title.textContent = form.title || 'Event Evaluation';
                    elements.event.textContent = form.event?.event_name || 'Event';
                    elements.description.textContent = form.description || '';
                    elements.description.classList.toggle('hidden', !form.description);
                    const submitted = Boolean(form.already_submitted);
                    elements.questions.innerHTML = (form.questions || []).map(renderQuestion).join('');
                    elements.submittedNotice.classList.toggle('hidden', !submitted);
                    elements.submitArea.classList.toggle('hidden', submitted);
                    elements.form.querySelectorAll('input, textarea').forEach(element => { element.disabled = submitted; });
                    elements.browse.classList.add('hidden');
                    elements.section.classList.remove('hidden');
                    updateProgress(); quietScroll(elements.section);
                } catch (error) { setAlert('error', error.message); quietScroll(elements.error); notify('error', error.message); }
                finally { opening = false; }
            }
            async function closeEvaluation() {
                if (submitting) return;
                const hasDraft = currentForm && !currentForm.already_submitted &&
                    [...elements.questions.querySelectorAll('.evaluation-question')].some(questionAnswered);
                if (hasDraft) {
                    const discard = window.DySignUI?.confirm
                        ? await window.DySignUI.confirm({title:'Leave this evaluation?',message:'Your unsent answers will not be saved. Go back to the evaluation list?',confirmText:'Discard and Go Back',variant:'warning'})
                        : window.confirm('Discard your unsent answers and go back?');
                    if (!discard) return;
                }
                elements.section.classList.add('hidden'); elements.browse.classList.remove('hidden'); currentForm = null;
                setAlert(null,''); quietScroll(elements.browse);
            }
            function collectAnswers() {
                return [...elements.questions.querySelectorAll('.evaluation-question')].map(article => {
                    const question_id = Number(article.dataset.questionId), type = article.dataset.questionType;
                    if (type === 'Short Answer' || type === 'Paragraph') return {question_id, value:article.querySelector('[data-answer-value]')?.value ?? ''};
                    if (type === 'Yes No') return {question_id, value:article.querySelector('[data-boolean-value]:checked')?.value ?? null};
                    if (type === 'Rating') return {question_id, value:article.querySelector('[data-rating-value]:checked')?.value ?? null};
                    if (type === 'Single Choice' || type === 'Multiple Choice') return {question_id, option_ids:[...article.querySelectorAll('[data-option-id]:checked')].map(option => Number(option.dataset.optionId))};
                    return {question_id, value:null};
                });
            }
            function validateQuestions() {
                let firstMissing = null;
                elements.questions.querySelectorAll('.evaluation-question').forEach(article => {
                    const missing = article.dataset.required === 'true' && !questionAnswered(article);
                    article.classList.toggle('dy-eval-invalid', missing);
                    article.querySelector('.dy-eval-question-error')?.classList.toggle('hidden', !missing);
                    if (missing && !firstMissing) firstMissing = article;
                });
                if (firstMissing) {
                    setAlert('error','Please answer all required questions before submitting.');
                    quietScroll(firstMissing);
                    firstMissing.querySelector('input:not([disabled]), textarea:not([disabled])')?.focus({preventScroll:true});
                    return false;
                }
                return true;
            }
            elements.questions.addEventListener('input', updateProgress);
            elements.questions.addEventListener('change', event => {
                const article = event.target.closest('.evaluation-question');
                if (article && questionAnswered(article)) {
                    article.classList.remove('dy-eval-invalid'); article.querySelector('.dy-eval-question-error')?.classList.add('hidden');
                }
                updateProgress();
            });
            elements.form.addEventListener('submit', async event => {
                event.preventDefault();
                if (submitting || !currentForm || currentForm.already_submitted) return;
                setAlert(null,'');
                if (!validateQuestions()) return;
                const confirmed = window.DySignUI?.confirm
                    ? await window.DySignUI.confirm({title:'Submit event evaluation?',message:'Review your answers before submitting. You can only submit this evaluation once.',confirmText:'Submit Evaluation',variant:'default'})
                    : window.confirm('Submit your evaluation? You can only submit once.');
                if (!confirmed) return;
                submitting = true; elements.submitButton.disabled = true; elements.submitButton.textContent = 'Submitting...';
                try {
                    const response = await fetch(`/api/student/evaluations/${currentForm.evaluation_form_id}/responses`, {
                        method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json','Accept':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':csrfToken},body:JSON.stringify({answers:collectAnswers()})
                    });
                    const data = await response.json().catch(() => ({}));
                    if (!response.ok || data.success === false) throw new Error(getError(data));
                    elements.section.classList.add('hidden'); elements.browse.classList.remove('hidden');
                    currentForm = null; activeFilter = 'all'; filterButtons();
                    await loadEvaluations();
                    setAlert('success',data.message || 'Evaluation submitted successfully.');
                    notify('success',data.message || 'Evaluation submitted successfully.');
                    quietScroll(elements.success);
                } catch (error) { setAlert('error',error.message); notify('error',error.message); quietScroll(elements.error); }
                finally { submitting = false; elements.submitButton.disabled = false; elements.submitButton.textContent = 'Submit Evaluation'; }
            });
            const answerHtml = answer => {
                const value = answer.answer;
                if (Array.isArray(value)) return value.length ? value.map(item => `<span class="inline-flex rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-[#101064]">${escapeHtml(item)}</span>`).join(' ') : '<em class="text-slate-400">No answer provided</em>';
                if (value === null || value === undefined || value === '') return '<em class="text-slate-400">No answer provided</em>';
                return `<span class="whitespace-pre-line break-words">${escapeHtml(value)}</span>`;
            };
            async function viewMyResponse(id) {
                modalFocusReturn = document.activeElement;
                elements.modal.classList.remove('hidden'); elements.modal.setAttribute('aria-hidden','false');
                elements.modalTitle.textContent = 'My Evaluation Response'; elements.modalDate.textContent = '';
                elements.modalAnswers.innerHTML = '<p class="py-10 text-center text-sm text-slate-500">Loading your response...</p>';
                elements.modalAnswers.scrollTop = 0;
                elements.modalDialog.focus();
                try {
                    const response = await jsonRequest(`/api/student/evaluations/${id}/response`);
                    elements.modalTitle.textContent = response.title || 'My Evaluation Response';
                    if (response.submitted_at) {
                        const date = new Date(response.submitted_at);
                        elements.modalDate.textContent = Number.isNaN(date.getTime()) ? '' : `Submitted ${date.toLocaleString('en-PH',{dateStyle:'medium',timeStyle:'short'})}`;
                    }
                    const answers = Array.isArray(response.answers) ? response.answers : [];
                    elements.modalAnswers.innerHTML = answers.length
                        ? answers.map((answer,index) => `<article class="rounded-xl border border-slate-200 bg-slate-50 p-4 sm:p-5"><div class="flex items-start gap-3"><span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-[#101064] text-xs font-extrabold text-white">${index+1}</span><div class="min-w-0 flex-1"><h3 class="break-words text-sm font-bold leading-6 text-[#101064]">${escapeHtml(answer.question)}</h3><span class="mt-2 inline-block rounded-md bg-white px-2 py-1 text-[10px] font-bold uppercase tracking-wide text-slate-500">${escapeHtml(formatType(answer.type))}</span><div class="mt-3 text-sm leading-6 text-slate-700">${answerHtml(answer)}</div></div></div></article>`).join('')
                        : '<p class="rounded-xl border border-dashed border-slate-200 p-8 text-center text-sm text-slate-500">No submitted answers found.</p>';
                } catch (error) { elements.modalAnswers.innerHTML = `<p role="alert" class="rounded-xl border border-rose-200 bg-rose-50 p-5 text-sm text-rose-700">${escapeHtml(error.message)}</p>`; }
            }
            function closeModal() {
                elements.modal.classList.add('hidden'); elements.modal.setAttribute('aria-hidden','true');
                if (modalFocusReturn?.isConnected) modalFocusReturn.focus({preventScroll:true});
                modalFocusReturn = null;
            }
            elements.list.addEventListener('click', event => {
                const button = event.target.closest('[data-eval-action]');
                if (!button) return;
                const id = asId(button.dataset.evalId);
                if (!id) return;
                if (button.dataset.evalAction === 'response') viewMyResponse(id);
                else if (button.dataset.evalAction === 'answer') openEvaluation(id);
            });
            document.querySelectorAll('[data-eval-filter]').forEach(button => button.addEventListener('click', () => {activeFilter = button.dataset.evalFilter;filterButtons();renderList();}));
            $('backToEvaluations').addEventListener('click', closeEvaluation);
            $('closeResponseModal').addEventListener('click', closeModal);
            $('closeResponseFooter').addEventListener('click', closeModal);
            elements.modal.addEventListener('click', event => {if (event.target === elements.modal) closeModal();});
            document.addEventListener('keydown', event => {if (event.key === 'Escape' && elements.modal.getAttribute('aria-hidden') === 'false') closeModal();});
            filterButtons(); loadEvaluations();
        });
    </script>
</x-admin-layout>
