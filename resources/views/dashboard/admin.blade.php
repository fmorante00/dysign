<x-admin-layout>


<div

class="
space-y-8
min-h-screen
pb-10
"

x-data="{

    selectedEvent:null,


    events:[

        {
            date:18,
            title:'RFID System Maintenance',
            time:'1:00 PM - 3:00 PM',
            venue:'IT Office',
            status:'System Activity'
        },


        {
            date:25,
            title:'Leadership Seminar',
            time:'8:00 AM - 5:00 PM',
            venue:'Main Auditorium',
            status:'Upcoming'
        },


        {
            date:30,
            title:'Freshmen Orientation',
            time:'9:00 AM - 12:00 PM',
            venue:'University Hall',
            status:'Upcoming'
        }

    ],



    selectEvent(day){

        this.selectedEvent =
        this.events.find(
            event => event.date === day
        );

    }


}"

>



<!-- HEADER -->

<div>


<h1

class="
text-3xl
font-bold
text-[#101064]
"

>

Welcome back, Administrator

</h1>


<p

class="
mt-2
text-gray-500
"

>

Manage DySign digital identity operations and system activities.

</p>


</div>







<!-- MAIN DASHBOARD GRID -->

<div

class="
grid
grid-cols-1
xl:grid-cols-3
gap-6
items-start
"

>



<!-- CALENDAR -->

<div

class="
xl:col-span-2
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-6
"

>



<div

class="
flex
justify-between
items-center
mb-6
"

>


<div>


<h2

class="
text-xl
font-semibold
text-[#101064]
"

>

September 2026

</h2>



<p

class="
text-sm
text-gray-500
"

>

System Events Calendar

</p>



</div>





<div

class="
flex
gap-2
"

>


<button

class="
border
rounded-lg
px-3
py-2
hover:bg-gray-50
"

>

←

</button>


<button

class="
border
rounded-lg
px-3
py-2
hover:bg-gray-50
"

>

→

</button>



</div>


</div>







<div

class="
grid
grid-cols-7
gap-3
text-center
mb-3
"

>


@foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)


<div

class="
text-xs
font-semibold
text-gray-400
uppercase
"

>

{{ $day }}

</div>


@endforeach


</div>






<div

class="
grid
grid-cols-7
gap-3
"

>


@for($i = 1; $i <= 30; $i++)


<button

@click="selectEvent({{$i}})"

class="
relative
h-16
rounded-xl
border
border-gray-100
hover:bg-gray-50
transition
text-left
p-3
"

>


<span

class="
text-sm
text-gray-700
"

>

{{$i}}

</span>





<template x-for="event in events">


<span

x-show="event.date === {{$i}}"

class="
absolute
bottom-2
left-3
w-2
h-2
rounded-full
"

:class="

event.status === 'Upcoming'

?

'bg-[#101064]'

:

'bg-[#D4A017]'

"

></span>


</template>



</button>



@endfor


</div>







<div

class="
mt-6
border-t
pt-4
flex
gap-6
text-sm
text-gray-500
"

>


<div class="flex items-center gap-2">

<span class="w-2 h-2 rounded-full bg-[#101064]"></span>

Upcoming Event

</div>



<div class="flex items-center gap-2">

<span class="w-2 h-2 rounded-full bg-[#D4A017]"></span>

System Activity

</div>



</div>






</div>

<!-- RIGHT SIDE PANEL -->

<div

class="
space-y-6
"

>






<!-- SELECTED EVENT -->

<div

class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-6
"

>



<div

class="
flex
justify-between
items-center
"

>


<h2

class="
text-lg
font-semibold
text-[#101064]
"

>

Selected Event

</h2>



<button

@click="selectedEvent=null"

x-show="selectedEvent"

class="
text-gray-400
hover:text-gray-700
"

>

✕

</button>



</div>







<template x-if="selectedEvent">



<div

class="
mt-5
space-y-4
"

>


<div>


<p

class="
text-sm
text-gray-500
"

>

Event Name

</p>


<p

class="
font-semibold
text-[#101064]
"

x-text="selectedEvent.title"

></p>


</div>






<div>


<p

class="
text-sm
text-gray-500
"

>

Schedule

</p>


<p

class="
font-medium
text-gray-700
"

x-text="selectedEvent.time"

></p>


</div>







<div>


<p

class="
text-sm
text-gray-500
"

>

Venue

</p>


<p

class="
font-medium
text-gray-700
"

x-text="selectedEvent.venue"

></p>


</div>







<div>


<p

class="
text-sm
text-gray-500
"

>

Status

</p>



<span

class="
inline-flex
mt-1
px-3
py-1
rounded-full
text-xs
font-semibold
bg-yellow-100
text-yellow-700
"

x-text="selectedEvent.status"

></span>



</div>





</div>


</template>






<template x-if="!selectedEvent">


<p

class="
mt-5
text-sm
text-gray-500
"

>

Select an event date from the calendar to view details.

</p>


</template>






</div>









<!-- SYSTEM OVERVIEW -->

<div

class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-6
"

>



<h2

class="
text-lg
font-semibold
text-[#101064]
mb-5
"

>

System Overview

</h2>






<div

class="
space-y-4
"

>




<div

class="
border
rounded-xl
p-4
"

>


<p

class="
text-sm
text-gray-500
"

>

Personnel Accounts

</p>


<p

class="
text-3xl
font-bold
text-[#D4A017]
mt-2
"

>

24

</p>


<p

class="
text-xs
text-gray-500
"

>

Active personnel users

</p>


</div>







<div

class="
border
rounded-xl
p-4
"

>


<p

class="
text-sm
text-gray-500
"

>

Registered Students

</p>


<p

class="
text-3xl
font-bold
text-[#D4A017]
mt-2
"

>

1,542

</p>


<p

class="
text-xs
text-gray-500
"

>

Student identity records

</p>


</div>







<div

class="
border
rounded-xl
p-4
"

>


<p

class="
text-sm
text-gray-500
"

>

RFID Credentials

</p>


<p

class="
text-3xl
font-bold
text-[#D4A017]
mt-2
"

>

1,420

</p>


<p

class="
text-xs
text-gray-500
"

>

Assigned student cards

</p>


</div>






</div>



</div>

<!-- UPCOMING EVENTS -->


<div

class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-6
"

>


<h2

class="
text-lg
font-semibold
text-[#101064]
mb-5
"

>

Upcoming Events

</h2>






<div

class="
space-y-4
"

>



<div

class="
border
border-gray-100
rounded-xl
p-4
"

>


<p

class="
font-semibold
text-[#101064]
"

>

Leadership Seminar

</p>


<p

class="
text-sm
text-gray-500
mt-1
"

>

September 25, 2026

</p>


<p

class="
text-sm
text-gray-500
"

>

Main Auditorium

</p>


<span

class="
inline-block
mt-3
px-3
py-1
rounded-full
bg-blue-100
text-blue-700
text-xs
font-semibold
"

>

Upcoming

</span>



</div>









<div

class="
border
border-gray-100
rounded-xl
p-4
"

>


<p

class="
font-semibold
text-[#101064]
"

>

Freshmen Orientation

</p>


<p

class="
text-sm
text-gray-500
mt-1
"

>

September 30, 2026

</p>


<p

class="
text-sm
text-gray-500
"

>

University Hall

</p>


<span

class="
inline-block
mt-3
px-3
py-1
rounded-full
bg-blue-100
text-blue-700
text-xs
font-semibold
"

>

Upcoming

</span>



</div>






</div>



</div>









<!-- RECENT ACTIVITY -->


<div

class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-6
"

>


<h2

class="
text-lg
font-semibold
text-[#101064]
mb-5
"

>

Recent Activity

</h2>







<div

class="
space-y-5
"

>




<div

class="
flex
gap-4
"

>


<div

class="
w-3
h-3
rounded-full
bg-[#D4A017]
mt-2
"

></div>



<div>


<p

class="
font-semibold
text-gray-700
"

>

Personnel Account Created

</p>


<p

class="
text-sm
text-gray-500
"

>

Administrator added a new attendance personnel account.

</p>


<p

class="
text-xs
text-gray-400
mt-1
"

>

15 minutes ago

</p>


</div>



</div>








<div

class="
flex
gap-4
"

>


<div

class="
w-3
h-3
rounded-full
bg-[#101064]
mt-2
"

></div>



<div>


<p

class="
font-semibold
text-gray-700
"

>

RFID Credential Updated

</p>


<p

class="
text-sm
text-gray-500
"

>

Student RFID information was modified.

</p>


<p

class="
text-xs
text-gray-400
mt-1
"

>

2 hours ago

</p>


</div>



</div>








<div

class="
flex
gap-4
"

>


<div

class="
w-3
h-3
rounded-full
bg-green-500
mt-2
"

></div>



<div>


<p

class="
font-semibold
text-gray-700
"

>

System Backup Completed

</p>


<p

class="
text-sm
text-gray-500
"

>

Database backup process finished successfully.

</p>


<p

class="
text-xs
text-gray-400
mt-1
"

>

Yesterday

</p>


</div>



</div>







</div>



</div>






</div>


</div>


</div>


</x-admin-layout>