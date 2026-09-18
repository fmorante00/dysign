<x-admin-layout>


<div class="space-y-8">


    <!-- HEADER -->

    <div>

        <h1 class="
            text-3xl
            font-bold
            text-[#101064]
        ">
            Welcome back, Administrator
        </h1>


        <p class="
            text-gray-500
            mt-2
        ">
            Manage DySign digital identity operations and system activities.
        </p>


    </div>







    <!-- MAIN GRID -->

        <div class="
            grid
            grid-cols-3
            gap-6
            items-start
            ">





        <!-- CALENDAR -->

        <div class="
             col-span-2
             bg-white
             rounded-2xl
             shadow-sm
             border
             border-gray-100
             p-6
             h-fit
             ">


            <div class="
                flex
                justify-between
                items-center
                mb-6
            ">


                <div>

                    <h2 class="
                        text-xl
                        font-semibold
                        text-[#101064]
                    ">
                        September 2026
                    </h2>


                    <p class="
                        text-sm
                        text-gray-500
                    ">
                        System Events Calendar
                    </p>


                </div>




                <div class="flex gap-2">

                    <button class="
                        px-3
                        py-2
                        rounded-lg
                        border
                        hover:bg-gray-50
                    ">
                        ←
                    </button>


                    <button class="
                        px-3
                        py-2
                        rounded-lg
                        border
                        hover:bg-gray-50
                    ">
                        →
                    </button>

                </div>


            </div>








            <!-- DAYS -->

            <div class="
                grid
                grid-cols-7
                gap-3
                text-center
                mb-3
            ">


                @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)

                    <div class="
                        text-xs
                        uppercase
                        font-semibold
                        text-gray-400
                    ">

                        {{ $day }}

                    </div>

                @endforeach


            </div>







            <!-- DATES -->

            <div
                x-data="{selectedDay:null}"
                class="
                grid
                grid-cols-7
                gap-3
                "
            >


                @for($i = 1; $i <= 30; $i++)


                    <button
                        @click="selectedDay={{$i}}"
                        class="
                        h-16
                        rounded-xl
                        border
                        border-gray-100
                        p-3
                        text-left
                        hover:bg-gray-50
                        transition
                        relative
                        "
                        :class="
                        selectedDay == {{$i}}
                        ?
                        'border-[#D4A017] bg-yellow-50'
                        :
                        ''
                        "
                    >


                        <span class="
                            text-sm
                            text-gray-700
                        ">
                            {{$i}}
                        </span>




                        @if($i == 18)

                        <span class="
                            absolute
                            bottom-2
                            left-3
                            w-2
                            h-2
                            bg-[#D4A017]
                            rounded-full
                        ">
                        </span>

                        @endif





                        @if($i == 25)

                        <span class="
                            absolute
                            bottom-2
                            left-3
                            w-2
                            h-2
                            bg-[#101064]
                            rounded-full
                        ">
                        </span>

                        @endif



                    </button>


                @endfor


            </div>






            <div class="
                mt-6
                border-t
                pt-4
                flex
                gap-6
                text-sm
                text-gray-500
            ">


                <div class="flex items-center gap-2">

                    <span class="
                        w-2
                        h-2
                        rounded-full
                        bg-[#D4A017]
                    ">
                    </span>

                    RFID Activity

                </div>




                <div class="flex items-center gap-2">

                    <span class="
                        w-2
                        h-2
                        rounded-full
                        bg-[#101064]
                    ">
                    </span>

                    System Event

                </div>


            </div>



        </div>









        <!-- RIGHT SIDE -->

        <div class="space-y-6">







            <!-- SYSTEM OVERVIEW -->


            <div class="
                bg-white
                rounded-2xl
                shadow-sm
                border
                border-gray-100
                p-6
            ">


                <h2 class="
                    text-lg
                    font-semibold
                    text-[#101064]
                    mb-5
                ">
                    System Overview
                </h2>




                <div class="space-y-4">





                    <!-- CARD -->

                    @foreach([
                        ['Personnel','24','Active Accounts'],
                        ['Students','1,542','Registered Students'],
                        ['RFID Identity','1,420','Assigned Credentials']
                    ] as $item)



                    <div class="
                        p-4
                        rounded-xl
                        border
                        border-gray-100
                        hover:shadow-md
                        transition
                    ">


                        <p class="
                            text-sm
                            text-gray-500
                        ">

                            {{ $item[0] }}

                        </p>



                        <p class="
                            text-3xl
                            font-bold
                            text-[#D4A017]
                            mt-2
                        ">

                            {{ $item[1] }}

                        </p>


                        <p class="
                            text-xs
                            text-gray-500
                        ">

                            {{ $item[2] }}

                        </p>


                    </div>


                    @endforeach



                </div>


            </div>









            <!-- ACTIVITY TIMELINE -->


            <div class="
                bg-white
                rounded-2xl
                shadow-sm
                border
                border-gray-100
                p-6
            ">


                <h2 class="
                    text-lg
                    font-semibold
                    text-[#101064]
                    mb-5
                ">
                    Recent Activity
                </h2>





                <div class="
                    border-l-2
                    border-[#D4A017]
                    pl-5
                    space-y-6
                ">




                    <div>

                        <p class="
                            text-xs
                            text-gray-400
                        ">
                            15 minutes ago
                        </p>


                        <p class="font-semibold">

                            Personnel Account Created

                        </p>


                        <p class="
                            text-sm
                            text-gray-500
                        ">

                            Administrator added a new authorized user.

                        </p>


                    </div>







                    <div>

                        <p class="
                            text-xs
                            text-gray-400
                        ">
                            2 hours ago
                        </p>


                        <p class="font-semibold">

                            RFID Record Updated

                        </p>


                        <p class="
                            text-sm
                            text-gray-500
                        ">

                            Student credential information modified.

                        </p>


                    </div>







                    <div>

                        <p class="
                            text-xs
                            text-gray-400
                        ">
                            Yesterday
                        </p>


                        <p class="font-semibold">

                            Backup Completed

                        </p>


                        <p class="
                            text-sm
                            text-gray-500
                        ">

                            System database backup successful.

                        </p>


                    </div>




                </div>



            </div>




        </div>




    </div>



</div>


</x-admin-layout>