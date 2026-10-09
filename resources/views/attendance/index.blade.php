<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DySign Attendance</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        :root{
            --dyci-navy:#101064;
            --dyci-gold:#D4A017;
            --dyci-cream:#FAFAF7;
            --dyci-text:#1F2937;
            --dyci-muted:#6B7280;
            --dyci-line:#E5E7EB;
            --dyci-soft:#F4F5FA;
            --dyci-green:#169c4a;
        }

        html, body {
            height: 100%;
        }

        body{
            margin:0;
            font-family:'Inter', sans-serif;
            background:var(--dyci-cream);
            color:var(--dyci-text);
        }

        .serif{
            font-family:'Playfair Display', serif;
        }

        .kiosk-shell{
            min-height:100vh;
            display:flex;
            flex-direction:column;
            background:var(--dyci-cream);
        }

        .kiosk-header{
            position:relative;
            overflow:hidden;
            background:
                linear-gradient(rgba(16,16,100,.88), rgba(16,16,100,.90)),
                url('{{ asset('images/school.jpg') }}');
            background-size:cover;
            background-position:center;
            color:#fff;
            border-bottom:4px solid var(--dyci-gold);
        }

        .kiosk-header::after{
            content:"";
            position:absolute;
            inset:0;
            background:
                linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px),
                linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px);
            background-size:48px 48px;
            opacity:.18;
            pointer-events:none;
        }

        .kiosk-header-inner{
            position:relative;
            z-index:2;
            padding:26px 44px 24px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:24px;
        }

        .staff-back{
            position:absolute;
            top:14px;
            left:18px;
            z-index:3;
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:6px 10px;
            border-radius:999px;
            background:rgba(255,255,255,.08);
            color:#fff;
            text-decoration:none;
            font-size:12px;
            letter-spacing:.08em;
            text-transform:uppercase;
            opacity:.18;
            transition:.25s ease;
            backdrop-filter:blur(4px);
        }

        .staff-back:hover{
            opacity:.95;
            background:rgba(255,255,255,.14);
        }

        .brand-wrap{
            display:flex;
            align-items:center;
            gap:16px;
        }

        .brand-logo{
            width:64px;
            height:64px;
            object-fit:contain;
            flex-shrink:0;
        }

        .brand-block{
            display:flex;
            flex-direction:column;
            line-height:1;
        }

        .brand-title{
            display:flex;
            align-items:baseline;
            gap:8px;
            font-size:40px;
            font-weight:800;
            letter-spacing:-0.03em;
        }

        .brand-title .dy{
            color:#ffffff;
        }

        .brand-title .sign{
            color:var(--dyci-gold);
            font-family:'Playfair Display', serif;
            font-style:italic;
            font-weight:700;
        }

        .brand-subtitle{
            margin-top:8px;
            color:rgba(255,255,255,.82);
            font-size:14px;
            letter-spacing:.08em;
            text-transform:uppercase;
        }

        .clock-wrap{
            text-align:right;
            flex-shrink:0;
        }

        .clock-time{
            font-size:48px;
            font-weight:800;
            line-height:1;
            letter-spacing:-0.04em;
            color:#fff;
        }

        .clock-date{
            margin-top:8px;
            color:rgba(255,255,255,.84);
            font-size:14px;
            letter-spacing:.12em;
            text-transform:uppercase;
        }

        .kiosk-main{
            flex:1;
            display:flex;
            flex-direction:column;
            padding:34px 52px 28px;
        }

        .event-intro{
            text-align:center;
            margin-bottom:34px;
        }

        .event-kicker{
            font-size:13px;
            letter-spacing:.55em;
            text-transform:uppercase;
            color:#94A3B8;
        }

        .event-title{
            margin-top:14px;
            font-size:76px;
            line-height:1.02;
            color:var(--dyci-navy);
            font-family:'Playfair Display', serif;
            font-weight:600;
            letter-spacing:-0.03em;
        }

        .event-meta{
            margin-top:22px;
            display:flex;
            align-items:center;
            justify-content:center;
            gap:28px;
            flex-wrap:wrap;
        }

        .event-meta-group{
            min-width:220px;
        }

        .event-meta-label{
            font-size:12px;
            letter-spacing:.30em;
            text-transform:uppercase;
            color:var(--dyci-gold);
        }

        .event-meta-value{
            margin-top:8px;
            font-size:21px;
            font-weight:600;
            color:var(--dyci-navy);
        }

        .event-divider{
            width:1px;
            height:54px;
            background:#D1D5DB;
        }

        .fade-enter{
            animation:fadeEnter .45s ease;
        }

        @keyframes fadeEnter{
            from{
                opacity:0;
                transform:translateY(14px);
            }
            to{
                opacity:1;
                transform:translateY(0);
            }
        }

        .ticker{
            display:inline-block;
            white-space:nowrap;
            animation:moveTicker 28s linear infinite;
        }

        @keyframes moveTicker{
            from{ transform:translateX(100%); }
            to{ transform:translateX(-100%); }
        }
    </style>
</head>
<body>
<div class="kiosk-shell">

    <header class="kiosk-header">
        <a href="{{ route('my-events.index') }}" class="staff-back">
            <span>←</span>
            <span>Back</span>
        </a>

        <div class="kiosk-header-inner">
            <div class="brand-wrap">
                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="DySign Logo"
                    class="brand-logo"
                >

                <div class="brand-block">
                    <div class="brand-title">
                        <span class="dy">Dy</span>
                        <span class="sign">Sign</span>
                    </div>

                    <div class="brand-subtitle">
                        Dr. Yanga's Colleges, Inc.
                    </div>
                </div>
            </div>

            <div class="clock-wrap">
                <div id="live_clock" class="clock-time">00:00:00</div>
                <div id="live_date" class="clock-date"></div>
            </div>
        </div>
    </header>

    <main class="kiosk-main">
        <section class="event-intro fade-enter">
            <div class="event-kicker">Welcome To</div>

            <h1 class="event-title">
                {{ $event->event_name }}
            </h1>

            <div class="event-meta">
                <div class="event-meta-group">
                    <div class="event-meta-label">Schedule</div>
                    <div class="event-meta-value">
                        {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}
                        —
                        {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                    </div>
                </div>

                <div class="event-divider"></div>

                <div class="event-meta-group">
                    <div class="event-meta-label">Venue</div>
                    <div class="event-meta-value">
                        {{ $event->location }}
                    </div>
                </div>
            </div>
        </section>

                <!-- ATTENDANCE AREA -->

        <section
            class="
            grid
            grid-cols-12
            gap-10
            flex-1
            "
        >



            <!-- LATEST STUDENT -->

            <div
                class="
                col-span-8
                flex
                flex-col
                "
            >


                <div
                    class="
                    flex
                    justify-between
                    items-end
                    mb-6
                    "
                >

                    <div>

                        <div
                            class="
                            text-xs
                            uppercase
                            tracking-[0.35em]
                            text-[#D4A017]
                            "
                        >
                            Latest Check-In
                        </div>


                        <h2
                            class="
                            serif
                            text-4xl
                            text-[#101064]
                            mt-2
                            "
                        >
                            Welcome!
                        </h2>

                    </div>



                    <div
                        class="
                        flex
                        items-center
                        gap-2
                        text-green-600
                        text-sm
                        font-semibold
                        "
                    >

                        <span
                            class="
                            w-2.5
                            h-2.5
                            rounded-full
                            bg-green-500
                            animate-pulse
                            "
                        ></span>

                        Live Attendance

                    </div>


                </div>






                <div
                    id="latest_scan"
                    class="
                    bg-white
                    rounded-[2rem]
                    border
                    border-gray-200
                    flex-1
                    flex
                    items-center
                    justify-center
                    shadow-sm
                    px-12
                    "
                >



                    @if($latestAttendance && $latestAttendance->student)


                    <div
                        class="
                        fade-enter
                        flex
                        items-center
                        gap-14
                        w-full
                        "
                    >



                        <!-- PROFILE -->

                        <div
                            class="
                            relative
                            flex-shrink-0
                            "
                        >

                            <div
                                class="
                                w-52
                                h-52
                                rounded-full
                                bg-[#F1F2FA]
                                border-[10px]
                                border-white
                                shadow-xl
                                flex
                                items-center
                                justify-center
                                "
                            >

                                <span
                                    class="
                                    serif
                                    text-8xl
                                    text-[#101064]
                                    "
                                >
                                    {{ strtoupper(substr($latestAttendance->student->first_name,0,1)) }}
                                </span>


                            </div>


                            <!-- small gold accent -->

                            <div
                                class="
                                absolute
                                bottom-5
                                right-3
                                w-7
                                h-7
                                rounded-full
                                bg-[#D4A017]
                                border-4
                                border-white
                                "
                            ></div>


                        </div>









                        <!-- STUDENT INFORMATION -->


                        <div
                            class="
                            flex-1
                            "
                        >



                            <p
                                class="
                                text-sm
                                uppercase
                                tracking-[0.25em]
                                text-gray-400
                                "
                            >
                                Checked In Student
                            </p>



                            <h1
                                class="
                                serif
                                text-6xl
                                text-[#101064]
                                leading-tight
                                mt-3
                                "
                            >

                                {{ $latestAttendance->student->first_name }}

                                {{ $latestAttendance->student->last_name }}

                            </h1>





                            <div
                                class="
                                mt-5
                                flex
                                items-center
                                gap-5
                                "
                            >


                                <span
                                    class="
                                    text-2xl
                                    font-semibold
                                    text-gray-500
                                    "
                                >
                                    {{ $latestAttendance->student->program_code }}
                                </span>



                                <span
                                    class="
                                    w-1.5
                                    h-1.5
                                    rounded-full
                                    bg-gray-300
                                    "
                                ></span>



                                <span
                                    class="
                                    text-gray-500
                                    "
                                >
                                    {{ $latestAttendance->time_in->format('h:i A') }}
                                </span>



                            </div>







                            <div
                                class="
                                mt-8
                                inline-flex
                                items-center
                                gap-3
                                px-6
                                py-3
                                rounded-full
                                bg-green-50
                                text-green-700
                                font-semibold
                                "
                            >


                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M5 13l4 4L19 7"
                                    />

                                </svg>


                                Attendance Confirmed


                            </div>





                        </div>



                    </div>





                    @else


                    <div
                        class="
                        text-gray-400
                        text-lg
                        "
                    >

                        Waiting for first attendee...


                    </div>


                    @endif



                </div>



            </div>











            <!-- RECENT ARRIVALS -->


            <aside
                class="
                col-span-4
                "
            >



                <div
                    class="
                    text-xs
                    uppercase
                    tracking-[0.35em]
                    text-[#D4A017]
                    "
                >
                    Recent Arrivals
                </div>




                <h2
                    class="
                    serif
                    text-4xl
                    text-[#101064]
                    mt-2
                    mb-8
                    "
                >
                    Today
                </h2>







                <div
                    id="attendance_feed"
                    class="
                    bg-white
                    rounded-[2rem]
                    border
                    border-gray-200
                    px-7
                    divide-y
                    divide-gray-100
                    "
                >





                    @foreach($attendanceRecords as $record)


                    <div
                        class="
                        py-6
                        "
                    >


                        <div
                            class="
                            flex
                            justify-between
                            gap-4
                            "
                        >



                            <div>


                                <p
                                    class="
                                    font-semibold
                                    text-[#101064]
                                    "
                                >

                                    {{ $record->student->first_name }}

                                    {{ $record->student->last_name }}

                                </p>



                                <p
                                    class="
                                    text-sm
                                    text-gray-500
                                    mt-1
                                    "
                                >

                                    {{ $record->student->program_code }}

                                </p>



                            </div>






                            <div
                                class="
                                text-right
                                "
                            >


                                <p
                                    class="
                                    text-sm
                                    text-gray-500
                                    "
                                >

                                    {{ $record->time_in->format('h:i A') }}

                                </p>



                                <p
                                    class="
                                    text-xs
                                    text-green-600
                                    font-semibold
                                    mt-1
                                    "
                                >
                                    Confirmed
                                </p>



                            </div>



                        </div>



                    </div>



                    @endforeach




                </div>




            </aside>





        </section>


                <!-- FOOTER ANNOUNCEMENT -->

        <footer
            class="
            mt-8
            -mx-0
            h-16
            bg-[#101064]
            text-white
            flex
            items-center
            overflow-hidden
            "
        >


            <!-- LABEL -->

            <div
                class="
                h-full
                bg-[#D4A017]
                text-[#101064]
                px-8
                flex
                items-center
                gap-3
                font-bold
                tracking-wide
                flex-shrink-0
                "
            >


                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M11 5L6 9H3v6h3l5 4V5z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M19 9a5 5 0 010 6"
                    />

                </svg>


                ANNOUNCEMENTS


            </div>





            <!-- TICKER -->


            <div
                class="
                flex-1
                overflow-hidden
                "
            >

                <div
                    class="
                    ticker
                    text-lg
                    "
                >

                    Welcome to {{ $event->event_name }}

                    &nbsp; • &nbsp;

                    Attendance successfully confirmed

                    &nbsp; • &nbsp;

                    Please enjoy the program

                    &nbsp; • &nbsp;

                    Dr. Yanga's Colleges Inc.

                    &nbsp; • &nbsp;

                    Powered by DySign Digital Identity System


                </div>


            </div>


        </footer>





    </main>


</div>









<script>


document.addEventListener(
'DOMContentLoaded',
function(){





/*
|--------------------------------------------------------------------------
| CLOCK
|--------------------------------------------------------------------------
*/


function updateClock(){


    const now = new Date();



    document.getElementById(
        'live_clock'
    ).innerHTML =
        now.toLocaleTimeString();



    document.getElementById(
        'live_date'
    ).innerHTML =
        now.toLocaleDateString(
            undefined,
            {
                weekday:'long',
                month:'long',
                day:'numeric',
                year:'numeric'
            }
        );


}



updateClock();


setInterval(
    updateClock,
    1000
);









/*
|--------------------------------------------------------------------------
| RFID READER
|--------------------------------------------------------------------------
*/


const input =
document.createElement('input');



input.type="text";


input.style.position="fixed";

input.style.opacity="0";

input.style.pointerEvents="none";



document.body.appendChild(input);



input.focus();







const scanUrl =
"{{ route('attendance.scan',$event->event_id) }}";



const feedUrl =
"{{ route('attendance.feed',$event->event_id) }}";



const csrf =
"{{ csrf_token() }}";





let processing=false;










input.addEventListener(
'input',
function(){



    let value =
    this.value.replace(
        /\D/g,
        ''
    );



    this.value=value;



    if(value.length===10){


        scanStudent(value);


        this.value="";


    }


}

);









async function scanStudent(rfid){



    if(processing)
    return;



    processing=true;




    try{


        const response =
        await fetch(
            scanUrl,
            {


                method:"POST",


                headers:
                {


                    "Content-Type":
                    "application/json",


                    "Accept":
                    "application/json",


                    "X-CSRF-TOKEN":
                    csrf


                },


                body:
                JSON.stringify(
                    {

                    rfid_identifier:rfid

                    }
                )

            }
        );



        const data =
        await response.json();





        if(data.success){


            refreshAttendance();


        }




    }


    catch(error){


        console.error(error);


    }


    finally{


        processing=false;


        input.focus();


    }




}









async function refreshAttendance(){



    try{


        const response =
        await fetch(
            feedUrl
        );



        const data =
        await response.json();




        if(
            data.records &&
            data.records.length
        ){


            updateLatest(
                data.records[0]
            );


            updateRecent(
                data.records
            );


        }



    }


    catch(error){


        console.error(error);


    }



}









function updateLatest(student){



const container =
document.getElementById(
'latest_scan'
);



container.innerHTML = `


<div
class="
fade-enter
flex
items-center
gap-14
w-full
"
>


<div
class="
w-52
h-52
rounded-full
bg-[#F1F2FA]
flex
items-center
justify-center
"
>


<span
class="
serif
text-8xl
text-[#101064]
"
>

${student.first_name.charAt(0)}

</span>


</div>





<div>


<p
class="
text-xs
uppercase
tracking-[0.25em]
text-gray-400
"
>

Checked In Student

</p>



<h1
class="
serif
text-6xl
text-[#101064]
mt-3
"
>

${student.first_name}

${student.last_name}

</h1>



<p
class="
text-2xl
text-gray-500
mt-4
"
>

${student.program_code}

</p>



<div
class="
mt-6
text-green-700
font-semibold
"
>

✓ Attendance Confirmed

</div>



</div>


</div>



`;



}









function updateRecent(records){



const feed =
document.getElementById(
'attendance_feed'
);



feed.innerHTML="";



records.forEach(
record=>{


feed.innerHTML += `


<div
class="
py-6
border-b
border-gray-100
"
>


<div
class="
flex
justify-between
"
>


<div>


<p
class="
font-semibold
text-[#101064]
"
>

${record.first_name}

${record.last_name}

</p>



<p
class="
text-sm
text-gray-500
"
>

${record.program_code}

</p>


</div>




<div
class="
text-sm
text-gray-500
"
>

${record.time_display}

</div>



</div>


</div>



`;



});



}






setInterval(
refreshAttendance,
5000
);






document.addEventListener(
'click',
function(){

    input.focus();

});



});



</script>







<style>


.ticker{

animation:
moveTicker 30s linear infinite;

}



@keyframes moveTicker{


from{

transform:
translateX(100%);

}


to{

transform:
translateX(-100%);

}


}



</style>




</body>

</html>