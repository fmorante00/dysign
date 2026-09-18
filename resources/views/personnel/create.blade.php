<x-admin-layout>

<div class="space-y-8">


    <!-- HEADER -->

    <div>

        <h1 class="
            text-3xl
            font-bold
            text-[#101064]
        ">
            Create Personnel Account
        </h1>


        <p class="
            mt-2
            text-gray-500
        ">
            Register a new authorized personnel account for the DySign system.
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
                        Configure login credentials and system access.
                    </p>

                </div>








                <div class="space-y-6">





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








                    <!-- PASSWORD -->


                    <div x-data="{show:false}">


                        <label class="
                            block
                            mb-2
                            text-sm
                            font-semibold
                            text-gray-700
                        ">

                            Password
                            <span class="text-red-500">*</span>

                        </label>





                        <div class="relative">


                            <input
                                :type="show ? 'text':'password'"
                                name="password"
                                placeholder="Enter password"
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




                            <button
                                type="button"
                                @click="show=!show"
                                class="
                                absolute
                                right-4
                                top-3
                                text-sm
                                text-[#101064]
                                font-medium
                                "
                            >

                                Show

                            </button>



                        </div>



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


                                <option value="{{ $role->role_id }}">

                                    {{ $role->role_name }}

                                </option>


                            @endforeach



                        </select>




                    </div>





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

                    Create Account

                </button>



            </div>






        </form>


    </div>




</div>


</x-admin-layout>