<x-admin-layout>

<div class="space-y-8">


    <!-- HEADER -->

    <div>

        <h1 class="
            text-3xl
            font-bold
            text-[#101064]
        ">
            Edit Personnel Account
        </h1>


        <p class="
            mt-2
            text-gray-500
        ">
            Update personnel information and access permissions.
        </p>


    </div>








    <!-- MAIN CARD -->

    <div class="
        bg-white
        rounded-3xl
        border
        border-gray-100
        shadow-sm
        p-8
    ">


        <form method="POST"
        action="{{ route('personnel.update', $personnel->personnel_id) }}">


            @csrf

            @method('PUT')









            <!-- PERSONNEL INFORMATION -->


            <div>


                <h2 class="
                    text-xl
                    font-semibold
                    text-[#101064]
                ">
                    Personnel Information
                </h2>


                <p class="
                    mt-1
                    mb-6
                    text-sm
                    text-gray-500
                ">
                    Update the personnel profile details.
                </p>







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
                        </label>


                        <input
                            type="text"
                            name="first_name"
                            value="{{ $personnel->first_name }}"
                            required
                            class="
                            w-full
                            rounded-xl
                            border
                            border-gray-300
                            px-4
                            py-3
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
                        </label>


                        <input
                            type="text"
                            name="last_name"
                            value="{{ $personnel->last_name }}"
                            required
                            class="
                            w-full
                            rounded-xl
                            border
                            border-gray-300
                            px-4
                            py-3
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
                        </label>


                        <input
                            type="text"
                            name="department"
                            value="{{ $personnel->department }}"
                            required
                            class="
                            w-full
                            rounded-xl
                            border
                            border-gray-300
                            px-4
                            py-3
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
                        </label>


                        <input
                            type="text"
                            name="position"
                            value="{{ $personnel->position }}"
                            required
                            class="
                            w-full
                            rounded-xl
                            border
                            border-gray-300
                            px-4
                            py-3
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


                <h2 class="
                    text-xl
                    font-semibold
                    text-[#101064]
                ">
                    Account Information
                </h2>


                <p class="
                    mt-1
                    mb-6
                    text-sm
                    text-gray-500
                ">
                    Account credentials are displayed for reference.
                </p>







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
                        </label>



                        <input
                            type="text"
                            value="{{ $personnel->user->username }}"
                            disabled
                            class="
                            w-full
                            rounded-xl
                            border
                            border-gray-200
                            bg-gray-100
                            px-4
                            py-3
                            text-gray-500
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
                        </label>



                        <input
                            type="email"
                            value="{{ $personnel->user->email }}"
                            disabled
                            class="
                            w-full
                            rounded-xl
                            border
                            border-gray-200
                            bg-gray-100
                            px-4
                            py-3
                            text-gray-500
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









            <!-- ACCESS CONTROL -->


            <div>


                <h2 class="
                    text-xl
                    font-semibold
                    text-[#101064]
                ">
                    Access Control
                </h2>


                <p class="
                    mt-1
                    mb-6
                    text-sm
                    text-gray-500
                ">
                    Manage system permissions for this account.
                </p>







                <div class="
                    grid
                    grid-cols-1
                    md:grid-cols-2
                    gap-6
                ">





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
                            outline-none
                            transition
                            focus:border-[#101064]
                            focus:ring-4
                            focus:ring-[#101064]/10
                            "
                        >



                            @foreach($roles as $role)


                            <option
                            value="{{ $role->role_id }}"
                            {{ $personnel->user->role_id == $role->role_id ? 'selected':'' }}
                            >

                                {{ $role->role_name }}

                            </option>


                            @endforeach




                        </select>



                    </div>








                    <!-- STATUS -->


                    <div>


                        <label class="
                            block
                            mb-2
                            text-sm
                            font-semibold
                            text-gray-700
                        ">
                            Account Status
                        </label>




                        <div class="
                            rounded-xl
                            border
                            border-gray-200
                            bg-gray-50
                            px-4
                            py-3
                        ">


                            @if($personnel->user->status == 'Active')


                            <span class="
                            text-green-700
                            font-semibold
                            ">

                                Active Account

                            </span>


                            @else


                            <span class="
                            text-gray-600
                            font-semibold
                            ">

                                Inactive Account

                            </span>


                            @endif



                        </div>



                    </div>



                </div>



            </div>









            <!-- BUTTONS -->


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

                    Save Changes

                </button>



            </div>







        </form>



    </div>



</div>


</x-admin-layout>