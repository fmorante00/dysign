<x-admin-layout>

    <div class="space-y-8">

        <div>
            <h1 class="text-3xl font-bold text-[#101064]">
                Create Event
            </h1>

            <p class="mt-2 text-gray-500">
                Add a new university or department activity.
            </p>
        </div>


        @if($errors->any())

            <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">

                <p class="font-semibold">
                    Please check the following:
                </p>

                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif



        <div class="rounded-3xl border border-gray-100 bg-white p-8 shadow-sm">

            <form method="POST" action="{{ route('events.store') }}">

                @csrf


                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">


                    <!-- EVENT NAME -->

                    <div>

                        <label class="text-sm font-semibold text-gray-600">
                            Event Name <span class="text-red-500">*</span>
                        </label>


                        <input
                            type="text"
                            name="event_name"
                            value="{{ old('event_name') }}"
                            required
                            class="mt-2 w-full rounded-xl border-gray-300 focus:border-[#101064] focus:ring-[#101064]"
                            placeholder="Example: CCS General Assembly"
                        >

                    </div>




                    <!-- LOCATION -->

                    <div>

                        <label class="text-sm font-semibold text-gray-600">
                            Location <span class="text-red-500">*</span>
                        </label>


                        <input
                            type="text"
                            name="location"
                            value="{{ old('location') }}"
                            required
                            class="mt-2 w-full rounded-xl border-gray-300 focus:border-[#101064] focus:ring-[#101064]"
                            placeholder="Example: University Hall"
                        >

                    </div>




                    <!-- EVENT DATE -->

                    <div>

                        <label class="text-sm font-semibold text-gray-600">
                            Event Date <span class="text-red-500">*</span>
                        </label>


                        <input
                            type="date"
                            name="event_date"
                            value="{{ old('event_date') }}"
                            required
                            class="mt-2 w-full rounded-xl border-gray-300 focus:border-[#101064] focus:ring-[#101064]"
                        >

                    </div>




                    <!-- DEPARTMENT -->

                    <div>

                        <label class="text-sm font-semibold text-gray-600">
                            Department
                        </label>


                        @if(auth()->user()->role->role_name === 'Department Staff')


                            <div class="mt-2 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-700">

                                {{ auth()->user()->department->department_name ?? 'No Department Assigned' }}

                            </div>


                            <input
                                type="hidden"
                                name="department_id"
                                value="{{ auth()->user()->department_id }}"
                            >


                            <p class="mt-2 text-xs text-gray-400">
                                This event will automatically be assigned to your department.
                            </p>


                        @else


                            <select
                                name="department_id"
                                class="mt-2 w-full rounded-xl border-gray-300 focus:border-[#101064] focus:ring-[#101064]"
                            >

                                <option value="">
                                    University-wide Event
                                </option>


                                @foreach($departments as $department)

                                    <option
                                        value="{{ $department->department_id }}"
                                        {{ old('department_id') == $department->department_id ? 'selected' : '' }}
                                    >

                                        {{ $department->department_name }}

                                    </option>

                                @endforeach


                            </select>


                        @endif


                    </div>





                    <!-- ATTENDANCE PERSONNEL -->

                    <div>

                        <label class="text-sm font-semibold text-gray-600">

                            Attendance Personnel
                            <span class="text-red-500">*</span>

                        </label>


                        <select
                            name="personnel_id"
                            required
                            class="mt-2 w-full rounded-xl border-gray-300 focus:border-[#101064] focus:ring-[#101064]"
                        >

                            <option value="">
                                Select Attendance Personnel
                            </option>


                            @foreach($personnel as $person)


                                <option
                                    value="{{ $person->personnel_id }}"
                                    {{ old('personnel_id') == $person->personnel_id ? 'selected' : '' }}
                                >

                                    {{ $person->first_name }}
                                    {{ $person->last_name }}
                                    - {{ $person->position }}

                                </option>


                            @endforeach


                        </select>


                        <p class="mt-2 text-xs text-gray-400">

                            Assigned personnel will manage RFID attendance scanning.

                        </p>


                    </div>





                    <!-- START TIME -->

                    <div>

                        <label class="text-sm font-semibold text-gray-600">

                            Start Time <span class="text-red-500">*</span>

                        </label>


                        <input
                            type="time"
                            name="start_time"
                            value="{{ old('start_time') }}"
                            required
                            class="mt-2 w-full rounded-xl border-gray-300 focus:border-[#101064] focus:ring-[#101064]"
                        >

                    </div>





                    <!-- END TIME -->

                    <div>

                        <label class="text-sm font-semibold text-gray-600">

                            End Time <span class="text-red-500">*</span>

                        </label>


                        <input
                            type="time"
                            name="end_time"
                            value="{{ old('end_time') }}"
                            required
                            class="mt-2 w-full rounded-xl border-gray-300 focus:border-[#101064] focus:ring-[#101064]"
                        >


                        <p class="mt-2 text-xs text-gray-400">

                            End time must be later than the start time.

                        </p>


                    </div>


                </div>





                <!-- DESCRIPTION -->

                <div class="mt-6">

                    <label class="text-sm font-semibold text-gray-600">

                        Description

                    </label>


                    <textarea
                        name="description"
                        rows="5"
                        class="mt-2 w-full rounded-xl border-gray-300 focus:border-[#101064] focus:ring-[#101064]"
                        placeholder="Describe the event..."
                    >{{ old('description') }}</textarea>


                </div>





                <!-- BUTTONS -->

                <div class="mt-8 flex justify-end gap-3">


                    <a
                        href="{{ route('events.index') }}"
                        class="rounded-xl border border-gray-300 px-5 py-3 font-semibold text-gray-600 transition hover:bg-gray-50"
                    >

                        Cancel

                    </a>



                    <button
                        type="submit"
                        class="rounded-xl bg-[#101064] px-5 py-3 font-semibold text-white transition hover:bg-[#0c0c4f]"
                    >

                        Create Event

                    </button>


                </div>


            </form>


        </div>


    </div>


</x-admin-layout>