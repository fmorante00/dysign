<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>DySign Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>



<body class="bg-gray-100">


<div class="min-h-screen flex">



    <!-- SIDEBAR -->

    <aside class="
        w-72
        bg-[#0B1F3A]
        text-white
        flex
        flex-col
        shadow-xl
    ">


        <!-- BRAND -->

        <div class="
            p-6
            border-b
            border-white/10
        ">


            <h1 class="
                text-3xl
                font-bold
            ">

                DySign

            </h1>


            <p class="
                text-sm
                text-blue-200
                mt-1
            ">

                Digital Identity System

            </p>


        </div>






        <!-- NAVIGATION -->

        <nav class="flex-1 p-5 space-y-2">



            <a href="/dashboard"
               class="
               block
               px-4
               py-3
               rounded-xl
               bg-white/10
               hover:bg-white/20
               transition
               ">

                🏠 Dashboard

            </a>





            <a href="/personnel"
               class="
               block
               px-4
               py-3
               rounded-xl
               hover:bg-white/20
               transition
               ">

                👥 Personnel Management

            </a>





            <a href="#"
               class="
               block
               px-4
               py-3
               rounded-xl
               hover:bg-white/20
               transition
               ">

                🎓 Student Data

            </a>





            <a href="#"
               class="
               block
               px-4
               py-3
               rounded-xl
               hover:bg-white/20
               transition
               ">

                📡 RFID Management

            </a>





            <a href="#"
               class="
               block
               px-4
               py-3
               rounded-xl
               hover:bg-white/20
               transition
               ">

                🔐 Roles & Access

            </a>





            <a href="#"
               class="
               block
               px-4
               py-3
               rounded-xl
               hover:bg-white/20
               transition
               ">

                📋 Activity Logs

            </a>



        </nav>






        <!-- BOTTOM -->

        <div class="
            p-5
            border-t
            border-white/10
        ">



            <a href="/profile"
               class="
               block
               px-4
               py-3
               rounded-xl
               hover:bg-white/20
               transition
               ">

                ⚙ Profile

            </a>




            <form method="POST" action="{{ route('logout') }}">

                @csrf


                <button
                    class="
                    w-full
                    text-left
                    px-4
                    py-3
                    rounded-xl
                    hover:bg-white/20
                    transition
                    ">

                    🚪 Logout

                </button>


            </form>



        </div>



    </aside>









    <!-- MAIN AREA -->


    <div class="flex-1">



        <!-- NAVBAR -->


        <header class="
            bg-white
            shadow-sm
            px-8
            py-5
            flex
            justify-between
            items-center
        ">


            <div>

                <h2 class="
                    text-xl
                    font-semibold
                    text-gray-800
                ">

                    Admin Dashboard

                </h2>


                <p class="
                    text-sm
                    text-gray-500
                ">

                    Welcome back, Administrator

                </p>


            </div>





            <div class="
                flex
                items-center
                gap-3
            ">


                <div class="
                    text-right
                ">


                    <p class="
                        font-semibold
                        text-gray-800
                    ">

                        {{ Auth::user()->name ?? 'Admin' }}

                    </p>


                    <p class="
                        text-xs
                        text-gray-500
                    ">

                        System Administrator

                    </p>


                </div>


            </div>


        </header>







        <!-- PAGE CONTENT -->


        <main class="p-8">


            {{ $slot }}


        </main>



    </div>



</div>


</body>


</html>