<x-admin-layout>
    <style>
        /* Styling scoped to the staff registration page; matches Staff Account Management. */
        .personnel-create-hero {
            background: linear-gradient(100deg,
                rgba(16,16,100,.97) 0%,
                rgba(16,16,100,.92) 52%,
                rgba(16,16,100,.74) 100%),
                url('{{ asset('images/school.jpg') }}');
            background-size: cover;
            background-position: center;
        }
        .personnel-create-section { box-shadow: 0 1px 2px rgba(15,23,42,.025); }
        .personnel-create-field {
            display: block;
            width: 100%;
            min-width: 0;
            min-height: 44px;
            border: 1px solid #e5e7eb;
            border-radius: .5rem;
            padding: .65rem .9rem;
            background: #fff;
            color: #334155;
            font-size: .875rem;
            transition: border-color .15s, box-shadow .15s;
        }
        .personnel-create-field::placeholder { color: #94a3b8; }
        .personnel-create-field:focus {
            outline: none;
            border-color: #101064;
            box-shadow: 0 0 0 3px rgba(16,16,100,.10);
        }
        .personnel-create-field[readonly] { background: #f8fafc; color: #64748b; }
        .personnel-create-field[aria-invalid="true"] { border-color: #dc2626; }
        .personnel-create-label {
            display: block;
            margin-bottom: .5rem;
            color: #64748b;
            font-size: .70rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
        }
    </style>

@include('partials.personnel-university-styles')

    <div class="dy-staff-page min-w-0 space-y-7 pb-8">
        {{-- Match the full-width hero used on Staff Account Management. --}}
        <header class="personnel-create-hero relative overflow-hidden rounded-[28px] px-7 py-8 text-white sm:px-8 lg:px-10 lg:py-10">
            <div class="absolute bottom-0 left-0 h-1 w-full bg-[#D4A017]" aria-hidden="true"></div>
            <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="min-w-0 max-w-3xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-[#E7C75B]">User Administration</p>
                    <h1 class="mt-3 break-words text-3xl font-bold tracking-tight md:text-4xl">Create Staff Account</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-white/75">Register authorized staff members and assign their department and system access within DySign.</p>
                    <div class="mt-5 flex flex-wrap gap-x-7 gap-y-2 text-xs text-white/75">
                        <span class="inline-flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-[#E7C75B]"></span> Official staff details</span>
                        <span class="inline-flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-emerald-400"></span> Role-based access</span>
                        <span class="inline-flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-white/70"></span> Secure account invitation</span>
                    </div>
                </div>

                <a href="{{ route('personnel.index') }}"
                   class="inline-flex shrink-0 items-center justify-center gap-2 self-start rounded-xl bg-[#D4A017] px-6 py-3 text-sm font-bold text-[#101064] transition hover:bg-white">
                    <svg class="h-4 w-4" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Staff Accounts
                </a>
            </div>
        </header>

        @if($errors->any())
            <div role="alert" class="flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
                <svg class="mt-0.5 h-5 w-5 shrink-0" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18.49A2 2 0 003.55 21h16.9a2 2 0 001.73-2.51L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <div>
                    <p class="font-bold">Please check the highlighted fields.</p>
                    <p class="mt-1">{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        {{-- Reuse the management page's section title hierarchy above the form. --}}
        <section class="min-w-0">
            <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-[#D4A017]">Account Registration</p>
                    <h2 class="mt-2 text-xl font-bold text-[#101064]">Staff Account Details</h2>
                    <p class="mt-1 text-sm text-gray-400">Enter the staff member's official information and assign access.</p>
                </div>
                <p class="text-xs font-medium text-gray-400"><span class="font-bold text-red-500">*</span> Required field</p>
            </div>

            <form method="POST" action="{{ route('personnel.store') }}" class="personnel-create-section min-w-0 overflow-hidden border border-gray-200 bg-white" data-confirm data-confirm-title="Create personnel record?" data-confirm-message="Confirm the details before creating this personnel account." data-confirm-confirm-text="Create Personnel" data-confirm-variant="default">
                @csrf

                <div class="grid grid-cols-1 gap-x-7 gap-y-6 px-6 py-7 md:grid-cols-2 xl:grid-cols-3 sm:px-8 sm:py-8">
                {{-- Identity --}}
                <div class="min-w-0">
                    <label for="first_name" class="personnel-create-label">First Name <span class="text-red-500">*</span></label>
                    <input id="first_name" name="first_name" type="text" maxlength="100" autocomplete="given-name"
                           value="{{ old('first_name') }}" placeholder="Enter first name" required
                           aria-invalid="{{ $errors->has('first_name') ? 'true' : 'false' }}" class="personnel-create-field">
                    @error('first_name') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="min-w-0">
                    <label for="last_name" class="personnel-create-label">Last Name <span class="text-red-500">*</span></label>
                    <input id="last_name" name="last_name" type="text" maxlength="100" autocomplete="family-name"
                           value="{{ old('last_name') }}" placeholder="Enter last name" required
                           aria-invalid="{{ $errors->has('last_name') ? 'true' : 'false' }}" class="personnel-create-field">
                    @error('last_name') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Department and role --}}
                <div class="min-w-0">
                    <label for="department_id" class="personnel-create-label">Department / College <span class="text-red-500">*</span></label>
                    <select id="department_id" name="department_id" required
                            aria-invalid="{{ $errors->has('department_id') ? 'true' : 'false' }}" class="personnel-create-field">
                        <option value="">Select a department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->department_id }}" @selected(old('department_id') == $department->department_id)>
                                {{ $department->department_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="min-w-0">
                    <label for="role_id" class="personnel-create-label">Assigned Role <span class="text-red-500">*</span></label>
                    <select id="role_id" name="role_id" required
                            aria-invalid="{{ $errors->has('role_id') ? 'true' : 'false' }}" class="personnel-create-field">
                        <option value="">Select a role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->role_id }}" @selected(old('role_id') == $role->role_id)>
                                {{ $role->role_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Account setup --}}
                <div class="min-w-0">
                    <label for="email" class="personnel-create-label">Email Address <span class="text-red-500">*</span></label>
                    <input id="email" name="email" type="email" maxlength="255" autocomplete="email"
                           value="{{ old('email') }}" placeholder="name@school.edu.ph" required
                           aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}" class="personnel-create-field">
                    @error('email') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="min-w-0">
                    <label for="username" class="personnel-create-label">Username Preview <span class="ml-1 font-normal text-slate-400">(automatic)</span></label>
                    <input id="username" type="text" value="{{ old('username') }}" readonly
                           placeholder="Generated from staff name" aria-describedby="username_help"
                           class="personnel-create-field">
                    <p id="username_help" class="mt-1.5 text-xs text-slate-500">
                        Final username is generated by the server and may have a numeric suffix.
                    </p>
                </div>
            </div>

            {{-- One unobtrusive notice, instead of the previous sidebar and multiple helper panels. --}}
            <div class="mx-6 mb-6 flex items-start gap-3 border border-amber-200/70 bg-[#FFFCF3] px-4 py-3 sm:mx-8">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-[#B68A0D]" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l9 6 9-6M4 8l8-5 8 5v10l-8 4-8-4V8z"/>
                </svg>
                <p class="text-xs leading-5 text-slate-600">
                    <span class="font-bold text-[#101064]">Account setup:</span>
                    DySign will generate a time-limited invitation link and attempt to email it after registration.
                </p>
            </div>

            <div class="flex flex-col gap-4 border-t border-gray-100 bg-gray-50/60 px-6 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-8">
                <p class="text-xs text-slate-500">Staff accounts use predefined roles. Student accounts are provisioned separately.</p>
                <div class="flex flex-wrap items-center justify-end gap-3">
                    <a href="{{ route('personnel.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#101064] px-6 py-3 text-sm font-bold text-white transition hover:bg-[#D4A017] hover:text-[#101064]">
                        <svg class="h-4 w-4" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Create Staff Account
                    </button>
                </div>
            </div>
            </form>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const firstNameInput = document.getElementById('first_name');
            const lastNameInput = document.getElementById('last_name');
            const usernameInput = document.getElementById('username');

            if (!firstNameInput || !lastNameInput || !usernameInput) return;

            const cleanName = value => value.trim().toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9]/g, '');

            function updateUsernamePreview() {
                const first = cleanName(firstNameInput.value);
                const last = cleanName(lastNameInput.value);
                usernameInput.value = [first, last].filter(Boolean).join('.');
            }

            firstNameInput.addEventListener('input', updateUsernamePreview);
            lastNameInput.addEventListener('input', updateUsernamePreview);
            updateUsernamePreview();
        });
    </script>
</x-admin-layout>
