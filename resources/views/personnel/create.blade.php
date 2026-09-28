<x-admin-layout>

<div class="space-y-8">

    <!-- HEADER -->

    <div>

        <h1 class="text-3xl font-bold text-[#101064]">
            Create Personnel Account
        </h1>

        <p class="mt-2 text-gray-500">
            Register a new personnel and send an account invitation automatically.
        </p>

    </div>



    <!-- FORM CARD -->

    <div class="
        bg-white
        rounded-3xl
        border
        border-gray-100
        shadow-sm
        p-8
    ">


        <form method="POST"
              action="{{ route('personnel.store') }}">

            @csrf



            <!-- PERSONNEL INFORMATION -->

            <div>


                <div class="mb-6">

                    <h2 class="
                        text-xl
                        font-semibold
                        text-[#101064]
                    ">
                        Personnel Information
                    </h2>


                    <p class="
                        mt-1
                        text-sm
                        text-gray-500
                    ">
                        Enter the personal details of the personnel.
                    </p>

                </div>




                <div class="
                    grid
                    grid-cols-1
                    md:grid-cols-2
                    gap-6
                ">


                    <!-- FIRST NAME -->

                    <div>

                        <label class="
                            block
                            mb-2
                            text-sm
                            font-semibold
                            text-gray-700
                        ">

                            First Name
                            <span class="text-red-500">*</span>

                        </label>


                        <input
                            type="text"
                            name="first_name"
                            value="{{ old('first_name') }}"
                            placeholder="Enter first name"
                            required
                            class="
                            w-full
                            rounded-xl
                            border
                            border-gray-300
                            px-4
                            py-3
                            text-gray-700
                            outline-none
                            transition
                            focus:border-[#101064]
                            focus:ring-4
                            focus:ring-[#101064]/10
                            "
                        >

                    </div>





                    <!-- LAST NAME -->

                    <div>

                        <label class="
                            block
                            mb-2
                            text-sm
                            font-semibold
                            text-gray-700
                        ">

                            Last Name
                            <span class="text-red-500">*</span>

                        </label>


                        <input
                            type="text"
                            name="last_name"
                            value="{{ old('last_name') }}"
                            placeholder="Enter last name"
                            required
                            class="
                            w-full
                            rounded-xl
                            border
                            border-gray-300
                            px-4
                            py-3
                            text-gray-700
                            outline-none
                            transition
                            focus:border-[#101064]
                            focus:ring-4
                            focus:ring-[#101064]/10
                            "
                        >

                    </div>





                    <!-- DEPARTMENT -->

                    <div>

                        <label class="
                            block
                            mb-2
                            text-sm
                            font-semibold
                            text-gray-700
                        ">

                            Department
                            <span class="text-red-500">*</span>

                        </label>


                        <input
                            type="text"
                            name="department"
                            value="{{ old('department') }}"
                            placeholder="Example: Registrar Office"
                            required
                            class="
                            w-full
                            rounded-xl
                            border
                            border-gray-300
                            px-4
                            py-3
                            text-gray-700
                            outline-none
                            transition
                            focus:border-[#101064]
                            focus:ring-4
                            focus:ring-[#101064]/10
                            "
                        >

                    </div>





                    <!-- POSITION -->

                    <div>

                        <label class="
                            block
                            mb-2
                            text-sm
                            font-semibold
                            text-gray-700
                        ">

                            Position
                            <span class="text-red-500">*</span>

                        </label>


                        <input
                            type="text"
                            name="position"
                            value="{{ old('position') }}"
                            placeholder="Example: Registrar Staff"
                            required
                            class="
                            w-full
                            rounded-xl
                            border
                            border-gray-300
                            px-4
                            py-3
                            text-gray-700
                            outline-none
                            transition
                            focus:border-[#101064]
                            focus:ring-4
                            focus:ring-[#101064]/10
                            "
                        >

                    </div>



                </div>


            </div>



            <div class="
                my-8
                border-t
                border-gray-200
            "></div>

                        <!-- ACCOUNT INFORMATION -->


            <div>


                <div class="mb-6">

                    <h2 class="
                        text-xl
                        font-semibold
                        text-[#101064]
                    ">
                        Account Information
                    </h2>


                    <p class="
                        mt-1
                        text-sm
                        text-gray-500
                    ">
                        Create login details and configure system access.
                    </p>

                </div>




                <div class="
                    grid
                    grid-cols-1
                    md:grid-cols-2
                    gap-6
                ">



                    <!-- USERNAME -->

                    <div>

                        <label class="
                            block
                            mb-2
                            text-sm
                            font-semibold
                            text-gray-700
                        ">

                            Username
                            <span class="text-red-500">*</span>

                        </label>



                        <input
                            type="text"
                            name="username"
                            value="{{ old('username') }}"
                            placeholder="Enter username"
                            required
                            class="
                            w-full
                            rounded-xl
                            border
                            border-gray-300
                            px-4
                            py-3
                            text-gray-700
                            outline-none
                            transition
                            focus:border-[#101064]
                            focus:ring-4
                            focus:ring-[#101064]/10
                            "
                        >


                    </div>





                    <!-- EMAIL -->

                    <div>

                        <label class="
                            block
                            mb-2
                            text-sm
                            font-semibold
                            text-gray-700
                        ">

                            Email Address
                            <span class="text-red-500">*</span>

                        </label>



                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="example@email.com"
                            required
                            class="
                            w-full
                            rounded-xl
                            border
                            border-gray-300
                            px-4
                            py-3
                            text-gray-700
                            outline-none
                            transition
                            focus:border-[#101064]
                            focus:ring-4
                            focus:ring-[#101064]/10
                            "
                        >


                    </div>



                </div>



                <!-- INVITATION NOTICE -->


                <div class="
                    mt-8
                    rounded-2xl
                    border
                    border-[#D4A017]/30
                    bg-[#D4A017]/10
                    p-5
                ">


                    <div class="flex gap-4">


                        <div class="
                            text-2xl
                        ">
                            ✉
                        </div>



                        <div>


                            <h3 class="
                                font-semibold
                                text-[#101064]
                            ">
                                Secure Account Setup
                            </h3>



                            <p class="
                                mt-1
                                text-sm
                                text-gray-600
                                leading-relaxed
                            ">
                                The user will receive an email invitation
                                containing a secure account setup link.
                                They will create their own password after
                                opening the invitation.
                            </p>



                        </div>



                    </div>


                </div>



            </div>






            <div class="
                my-8
                border-t
                border-gray-200
            "></div>






            <!-- ACCESS CONTROL -->


            <div>


                <div class="mb-6">


                    <h2 class="
                        text-xl
                        font-semibold
                        text-[#101064]
                    ">
                        Access Control
                    </h2>



                    <p class="
                        mt-1
                        text-sm
                        text-gray-500
                    ">
                        Assign the appropriate role and system permissions.
                    </p>


                </div>




                <!-- ROLE -->


                <div>


                    <label class="
                        block
                        mb-2
                        text-sm
                        font-semibold
                        text-gray-700
                    ">

                        Assigned Role
                        <span class="text-red-500">*</span>

                    </label>




                    <select
                        name="role_id"
                        required
                        class="
                        w-full
                        rounded-xl
                        border
                        border-gray-300
                        px-4
                        py-3
                        text-gray-700
                        outline-none
                        transition
                        focus:border-[#101064]
                        focus:ring-4
                        focus:ring-[#101064]/10
                        "
                    >


                        <option value="">
                            Select role
                        </option>



                        @foreach($roles as $role)


                            <option value="{{ $role->role_id }}"
                                {{ old('role_id') == $role->role_id ? 'selected' : '' }}
                            >

                                {{ $role->role_name }}

                            </option>


                        @endforeach



                    </select>



                </div>

                               


            </div>





            <!-- ACTION BUTTONS -->


            <div class="
                mt-10
                pt-6
                border-t
                border-gray-200
                flex
                justify-end
                gap-3
            ">



                <a href="{{ route('personnel.index') }}"
                   class="
                   px-6
                   py-3
                   rounded-xl
                   border
                   border-gray-300
                   text-gray-600
                   hover:bg-gray-50
                   transition
                   ">

                    Cancel

                </a>





                <button
                    type="submit"
                    class="
                    px-6
                    py-3
                    rounded-xl
                    bg-[#101064]
                    text-white
                    font-semibold
                    hover:bg-[#D4A017]
                    transition
                    duration-300
                    ">


                    Create Account & Send Invitation


                </button>




            </div>





        </form>


    </div>




</div>


</x-admin-layout>