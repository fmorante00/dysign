<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>DySign Admin</title>


    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>



<body class="bg-gray-100 overflow-hidden">



<div class="
h-screen
flex
overflow-hidden
">



    <!-- SIDEBAR -->

    <x-sidebar />





    <!-- MAIN CONTENT AREA -->


    <div class="
    flex-1
    h-screen
    flex
    flex-col
    ml-72
    ">





        <!-- NAVBAR -->


        <header class="
        sticky
        top-0
        z-40
        ">


            <x-navbar />


        </header>








        <!-- PAGE CONTENT -->


        <main

        class="
        flex-1
        overflow-y-auto
        p-8
        "

        >


            {{ $slot }}


        </main>




    </div>



</div>


<div
    id="global-confirm-modal"
    class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-950/60 px-4"
>
    <div class="w-full max-w-md rounded-[28px] bg-white shadow-2xl">
        <div class="border-b border-slate-100 px-6 py-5">
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-[#D4A017]">
                Confirmation Required
            </p>
            <h3 id="confirm-modal-title" class="mt-2 text-2xl font-bold text-[#101064]">
                Are you sure?
            </h3>
            <p id="confirm-modal-message" class="mt-3 text-sm leading-6 text-slate-500">
                Please confirm this action before continuing.
            </p>
        </div>

        <div class="flex items-center justify-end gap-3 px-6 py-5">
            <button
                type="button"
                id="confirm-modal-cancel"
                class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
            >
                Cancel
            </button>

            <button
                type="button"
                id="confirm-modal-proceed"
                class="rounded-xl bg-[#101064] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#0b0b4d]"
            >
                Proceed
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('global-confirm-modal');
    const titleEl = document.getElementById('confirm-modal-title');
    const messageEl = document.getElementById('confirm-modal-message');
    const cancelBtn = document.getElementById('confirm-modal-cancel');
    const proceedBtn = document.getElementById('confirm-modal-proceed');

    let pendingForm = null;
    let pendingLink = null;

    function openModal({ title, message, confirmText }) {
        titleEl.textContent = title || 'Are you sure?';
        messageEl.textContent = message || 'Please confirm this action before continuing.';
        proceedBtn.textContent = confirmText || 'Proceed';

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');

        pendingForm = null;
        pendingLink = null;
    }

    document.addEventListener('submit', function (event) {
        const form = event.target.closest('form[data-confirm]');
        if (!form) return;

        if (form.dataset.confirmed === 'true') {
            form.dataset.confirmed = 'false';
            return;
        }

        event.preventDefault();

        pendingForm = form;
        pendingLink = null;

        openModal({
            title: form.dataset.confirmTitle,
            message: form.dataset.confirmMessage,
            confirmText: form.dataset.confirmConfirmText
        });
    });

    document.addEventListener('click', function (event) {
        const link = event.target.closest('a[data-confirm-link]');
        if (!link) return;

        event.preventDefault();

        pendingLink = link;
        pendingForm = null;

        openModal({
            title: link.dataset.confirmTitle,
            message: link.dataset.confirmMessage,
            confirmText: link.dataset.confirmConfirmText
        });
    });

    proceedBtn.addEventListener('click', function () {
        if (pendingForm) {
            pendingForm.dataset.confirmed = 'true';
            pendingForm.submit();
        } else if (pendingLink) {
            window.location.href = pendingLink.href;
        }

        closeModal();
    });

    cancelBtn.addEventListener('click', closeModal);

    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
});
</script>


</body>

</html>