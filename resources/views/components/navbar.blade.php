<div
    x-data="
    {
        scrolled:false,
        open:false
    }
    "

    @scroll.window="
        scrolled = window.scrollY > 20
    "

    class="
        sticky
        top-0
        z-50
        transition
        duration-300
    "

    :class="
        scrolled
        ?
        'bg-white/70 backdrop-blur-lg shadow-md'
        :
        'bg-white'
    "
>



    <div class="
        h-24
        px-8
        flex
        items-center
        justify-between
    ">



        <!-- LEFT SIDE -->

        <div>


            <h1 class="
                text-xl
                font-bold
                text-[#101064]
            ">

                DySign Administration Portal

            </h1>



            <p class="
                text-sm
                text-gray-500
            ">

                Digital Identity Management System

            </p>


        </div>









        <!-- RIGHT SIDE -->


        <div class="
            flex
            items-center
            gap-6
        ">



            <!-- NOTIFICATION -->


            <button
                class="
                relative
                text-[#101064]
                hover:text-[#D4A017]
                transition
                "
            >


                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="22"
                    height="22"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >

                    <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9">
                    </path>

                    <path d="M13.73 21a2 2 0 0 1-3.46 0">
                    </path>


                </svg>


            </button>









            <!-- ADMIN DROPDOWN -->


            <div
                class="relative"
                @click.outside="open=false"
            >


                <!-- BUTTON -->


                <button
                    @click="open=!open"

                    class="
                    flex
                    items-center
                    gap-3
                    "
                >


                    <!-- AVATAR -->

                    <div class="
                        w-10
                        h-10
                        rounded-full
                        bg-[#101064]
                        text-white
                        flex
                        items-center
                        justify-center
                        font-semibold
                    ">

                        S

                    </div>





                    <div class="text-left">


                        <p class="
                            text-sm
                            font-semibold
                            text-[#101064]
                        ">

                            System Administrator

                        </p>


                        <p class="
                            text-xs
                            text-gray-500
                        ">

                            System Administrator

                        </p>


                    </div>







                    <!-- DROPDOWN ARROW -->


                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >

                        <path d="m6 9 4 4 4-4"/>

                    </svg>



                </button>









                <!-- DROPDOWN MENU -->


                <div

                    x-cloak

                    x-show="open"

                    x-transition:enter="
                    transition
                    ease-out
                    duration-200
                    "

                    x-transition:enter-start="
                    opacity-0
                    scale-95
                    -translate-y-2
                    "

                    x-transition:enter-end="
                    opacity-100
                    scale-100
                    translate-y-0
                    "


                    x-transition:leave="
                    transition
                    ease-in
                    duration-150
                    "

                    x-transition:leave-start="
                    opacity-100
                    scale-100
                    translate-y-0
                    "

                    x-transition:leave-end="
                    opacity-0
                    scale-95
                    -translate-y-2
                    "


                    class="
                    absolute
                    right-0
                    mt-4
                    w-56
                    bg-white
                    rounded-xl
                    shadow-lg
                    border
                    border-gray-100
                    py-2
                    "
                >





                    <a href="#"
                       class="
                       block
                       px-5
                       py-3
                       text-sm
                       text-gray-700
                       hover:bg-gray-50
                       hover:text-[#D4A017]
                       transition
                       ">

                        View Profile

                    </a>






                    <a href="#"
                       class="
                       block
                       px-5
                       py-3
                       text-sm
                       text-gray-700
                       hover:bg-gray-50
                       hover:text-[#D4A017]
                       transition
                       ">

                        Settings

                    </a>






                    <div class="
                        border-t
                        my-2
                    ">
                    </div>







                    <form method="POST"
                          action="{{ route('logout') }}">

                        @csrf


                        <button
                            class="
                            w-full
                            text-left
                            px-5
                            py-3
                            text-sm
                            text-gray-700
                            hover:bg-gray-50
                            hover:text-red-600
                            transition
                            "
                        >

                            Logout

                        </button>


                    </form>




                </div>



            </div>




        </div>



    </div>



</div>