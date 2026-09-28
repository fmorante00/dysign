<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

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





</body>

</html>