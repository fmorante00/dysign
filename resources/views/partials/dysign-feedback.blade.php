{{-- Shared DySign confirmation and feedback popups. Included once by x-admin-layout. --}}
{{-- Confirmation follows the Staff Accounts action-dialog design: navy header + gold rule. --}}
<div id="global-confirm-modal"
     class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/50 px-4 py-6 backdrop-blur-[2px]"
     aria-hidden="true">
    <section role="dialog" aria-modal="true" aria-labelledby="confirm-modal-title"
             aria-describedby="confirm-modal-message" tabindex="-1"
             class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">
        <header class="relative bg-[#101064] px-6 py-5 text-white">
            <div class="absolute bottom-0 left-0 h-1 w-full bg-[#D4A017]" aria-hidden="true"></div>
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p id="confirm-modal-eyebrow"
                       class="text-[10px] font-semibold uppercase tracking-[0.25em] text-[#E7C75B]">Confirmation Required</p>
                    <h2 id="confirm-modal-title" class="mt-2 break-words text-xl font-bold text-white">Are you sure?</h2>
                </div>
                <button type="button" id="confirm-modal-close" aria-label="Close confirmation"
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-2xl leading-none text-white/70 transition hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-[#E7C75B]">&times;</button>
            </div>
        </header>
        <div class="px-6 py-6">
            <div class="flex items-start gap-4">
                <div id="confirm-modal-icon" aria-hidden="true"
                     class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#FFF9E7] text-lg font-bold text-[#B68A0D]">!</div>
                <p id="confirm-modal-message" class="min-w-0 flex-1 whitespace-pre-line break-words text-sm leading-6 text-gray-600">Please confirm this action before continuing.</p>
            </div>
        </div>
        <footer class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-100 bg-gray-50 px-6 py-4">
            <button type="button" id="confirm-modal-cancel"
                    class="rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#D4A017]">Cancel</button>
            <button type="button" id="confirm-modal-proceed"
                    class="rounded-lg bg-[#101064] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#D4A017] hover:text-[#101064] focus:outline-none focus:ring-2 focus:ring-[#D4A017]">Proceed</button>
        </footer>
    </section>
</div>

<div id="dysign-feedback-toast" class="pointer-events-none fixed right-4 top-20 z-[10000] hidden w-[min(24rem,calc(100vw-2rem))]" aria-live="polite">
    <div id="dysign-feedback-panel" class="pointer-events-auto flex items-start gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-2xl">
        <div id="dysign-feedback-symbol" class="mt-0.5 text-lg font-extrabold" aria-hidden="true">✓</div>
        <div class="min-w-0 flex-1">
            <p id="dysign-feedback-title" class="font-bold text-[#101064]">Success</p>
            <p id="dysign-feedback-message" class="mt-1 break-words text-sm leading-5 text-slate-600"></p>
        </div>
        <button id="dysign-feedback-close" type="button" aria-label="Dismiss notification" class="rounded-lg px-2 py-1 text-slate-500 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-[#D4A017]">×</button>
    </div>
</div>

@foreach (['success', 'error', 'warning', 'info', 'status'] as $dysignFlashType)
    @if (is_string(session($dysignFlashType)) && trim(session($dysignFlashType)) !== '')
        <div hidden data-dysign-flash-type="{{ $dysignFlashType }}" data-dysign-flash-message="{{ session($dysignFlashType) }}"></div>
    @endif
@endforeach

<script>
(() => {
    'use strict';
    const modal = document.getElementById('global-confirm-modal');
    const title = document.getElementById('confirm-modal-title');
    const message = document.getElementById('confirm-modal-message');
    const eyebrow = document.getElementById('confirm-modal-eyebrow');
    const symbol = document.getElementById('confirm-modal-icon');
    const proceed = document.getElementById('confirm-modal-proceed');
    const cancel = document.getElementById('confirm-modal-cancel');
    const close = document.getElementById('confirm-modal-close');
    const toast = document.getElementById('dysign-feedback-toast');
    const toastPanel = document.getElementById('dysign-feedback-panel');
    const toastSymbol = document.getElementById('dysign-feedback-symbol');
    const toastTitle = document.getElementById('dysign-feedback-title');
    const toastMessage = document.getElementById('dysign-feedback-message');
    const toastClose = document.getElementById('dysign-feedback-close');
    if (!modal || !proceed || !cancel) return;

    let pending = null;
    let restoreFocus = null;
    let restoreOverflow = '';
    let toastTimer;

    function setVariant(variant) {
        const danger = variant === 'danger';
        symbol.className = 'flex h-11 w-11 shrink-0 items-center justify-center rounded-full text-lg font-bold ' +
            (danger ? 'bg-red-50 text-red-700' : 'bg-[#FFF9E7] text-[#B68A0D]');
        proceed.className = 'rounded-lg px-5 py-2.5 text-sm font-semibold text-white transition focus:outline-none focus:ring-2 focus:ring-[#D4A017] ' +
            (danger ? 'bg-red-700 hover:bg-red-800' : 'bg-[#101064] hover:bg-[#D4A017] hover:text-[#101064]');
        eyebrow.textContent = danger ? 'Important Action' : 'Confirmation Required';
        // Gold text keeps both variants legible on the navy header.
        eyebrow.className = 'text-[10px] font-semibold uppercase tracking-[0.25em] text-[#E7C75B]';
    }

    function showConfirmation(options, accept, reject = () => {}) {
        if (pending) return false;
        pending = { accept, reject };
        restoreFocus = document.activeElement;
        restoreOverflow = document.body.style.overflow;
        title.textContent = options.title || 'Are you sure?';
        message.textContent = options.message || 'Please confirm this action before continuing.';
        proceed.textContent = options.confirmText || 'Proceed';
        setVariant(options.variant || 'default');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        cancel.focus();
        return true;
    }

    function finishConfirmation(accepted) {
        if (!pending) return;
        const { accept, reject } = pending;
        pending = null;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = restoreOverflow;
        if (restoreFocus && restoreFocus.isConnected && typeof restoreFocus.focus === 'function') restoreFocus.focus();
        restoreFocus = null;
        if (accepted) accept(); else reject();
    }

    function optionsFrom(element) {
        return {
            title: element.dataset.confirmTitle,
            message: element.dataset.confirmMessage,
            confirmText: element.dataset.confirmConfirmText,
            variant: element.dataset.confirmVariant
        };
    }

    // Opt-in only: do not override AJAX workflows, specialized review dialogs, or GET filters.
    document.addEventListener('submit', (event) => {
        const form = event.target;
        if (!(form instanceof HTMLFormElement) || !form.hasAttribute('data-confirm') || event.defaultPrevented) return;
        if (form.dataset.dyConfirmed === 'true') return;
        event.preventDefault();
        const submitter = event.submitter;
        showConfirmation(optionsFrom(form), () => {
            if (!form.isConnected) return;
            form.dataset.dyConfirmed = 'true';
            try {
                // Re-runs browser validation and submit handlers and preserves button name/value.
                if (submitter && submitter.form === form && !submitter.disabled) form.requestSubmit(submitter);
                else form.requestSubmit();
            } finally {
                delete form.dataset.dyConfirmed;
            }
        });
    });

    document.addEventListener('click', (event) => {
        const link = event.target.closest('a[data-confirm-link]');
        if (!link || event.defaultPrevented || event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
        event.preventDefault();
        showConfirmation(optionsFrom(link), () => { window.location.assign(link.href); });
    });

    proceed.addEventListener('click', () => finishConfirmation(true));
    cancel.addEventListener('click', () => finishConfirmation(false));
    close.addEventListener('click', () => finishConfirmation(false));
    modal.addEventListener('click', (event) => { if (event.target === modal) finishConfirmation(false); });
    document.addEventListener('keydown', (event) => {
        if (!pending) return;
        if (event.key === 'Escape') { event.preventDefault(); finishConfirmation(false); return; }
        if (event.key !== 'Tab') return;
        const buttons = [close, cancel, proceed].filter(button => !button.disabled);
        const first = buttons[0], last = buttons[buttons.length - 1];
        if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
        else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
    });

    function notify(type, text) {
        if (typeof text !== 'string' || !text.trim()) return;
        const kind = ['success','error','warning','info'].includes(type) ? type : 'info';
        const palette = {
            success: ['border-emerald-200', 'text-emerald-700', 'Success', '✓'],
            error: ['border-red-200', 'text-red-700', 'Action Failed', '!'],
            warning: ['border-amber-200', 'text-amber-700', 'Attention Needed', '!'],
            info: ['border-blue-200', 'text-[#101064]', 'Notice', 'i']
        }[kind];
        toastPanel.className = 'pointer-events-auto flex items-start gap-3 rounded-2xl border bg-white p-4 shadow-2xl ' + palette[0];
        toastSymbol.className = 'mt-0.5 text-lg font-extrabold ' + palette[1];
        toastTitle.textContent = palette[2];
        toastSymbol.textContent = palette[3];
        toastMessage.textContent = text;
        toast.classList.remove('hidden');
        toast.setAttribute('role', kind === 'error' ? 'alert' : 'status');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toast.classList.add('hidden'), kind === 'error' ? 8500 : 5500);
    }
    toastClose.addEventListener('click', () => { clearTimeout(toastTimer); toast.classList.add('hidden'); });

    window.DySignUI = Object.freeze({
        confirm(options = {}) {
            return new Promise(resolve => {
                if (!showConfirmation(options, () => resolve(true), () => resolve(false))) resolve(false);
            });
        },
        notify
    });

    // Preserve existing detailed inline flash banners; don't repeat the same text as a toast.
    const inlineFlashSelectors = '[role="status"], [role="alert"], .dsr-alert, .bg-emerald-50, .bg-green-50, .bg-red-50, .bg-amber-50';
    function alreadyDisplayed(text) {
        if (!text || !document.querySelector('main')) return false;
        return Array.from(document.querySelector('main').querySelectorAll(inlineFlashSelectors)).some(element =>
            element.getClientRects().length > 0 && element.textContent.trim().includes(text)
        );
    }
    const flashes = Array.from(document.querySelectorAll('[data-dysign-flash-type]'));
    const preferred = ['error', 'warning', 'success', 'status', 'info'];
    flashes.sort((a,b) => preferred.indexOf(a.dataset.dysignFlashType) - preferred.indexOf(b.dataset.dysignFlashType));
    const flash = flashes[0];
    if (flash && !alreadyDisplayed(flash.dataset.dysignFlashMessage)) {
        notify(flash.dataset.dysignFlashType, flash.dataset.dysignFlashMessage);
    }
})();
</script>
