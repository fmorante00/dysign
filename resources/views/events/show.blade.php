<x-admin-layout>

<div class="space-y-8">


<!-- HEADER -->

<div class="flex justify-between items-center">


<div>

<h1 class="
text-3xl
font-bold
text-[#101064]
">

Leadership Seminar

</h1>


<p class="
mt-2
text-gray-500
">

Event details and attendance preparation.

</p>


</div>




<a

href="{{ route('events.index') }}"

class="
px-5
py-3
rounded-xl
border
border-gray-300
text-gray-600
hover:bg-gray-50
transition
"

>

Back

</a>



</div>









<!-- STATUS CARD -->


<div class="
bg-white
rounded-3xl
border
border-gray-100
shadow-sm
p-6
flex
justify-between
items-center
">


<div>


<p class="
text-sm
text-gray-500
">

Event Status

</p>


<span class="
inline-block
mt-2
px-4
py-2
rounded-full
bg-yellow-50
text-yellow-700
font-semibold
text-sm
">

Upcoming

</span>


</div>





<div class="
flex
gap-3
">


<a

href="#"

class="
px-5
py-3
rounded-xl
border
border-gray-300
text-gray-600
hover:bg-gray-50
transition
"

>

Edit Event

</a>

<a

href="{{ route('events.assign', 1) }}"

class="
px-5
py-3
rounded-xl
border
border-[#101064]
text-[#101064]
hover:bg-[#101064]
hover:text-white
transition
"

>

Assign Personnel

</a>






<button

class="
px-6
py-3
rounded-xl
bg-[#101064]
text-white
font-semibold
hover:bg-[#D4A017]
transition
"

>

Activate Event

</button>



</div>

</div>









<!-- EVENT INFORMATION -->


<div class="
grid
grid-cols-1
md:grid-cols-2
gap-6
">





<div class="
bg-white
rounded-3xl
border
border-gray-100
shadow-sm
p-7
">


<h2 class="
text-xl
font-semibold
text-[#101064]
mb-6
">

Event Information

</h2>





<div class="space-y-5">



<div>

<p class="
text-sm
text-gray-500
">

Event Type

</p>


<p class="
font-semibold
text-gray-700
">

Seminar

</p>


</div>






<div>

<p class="
text-sm
text-gray-500
">

Department / Organizer

</p>


<p class="
font-semibold
text-gray-700
">

College of Business

</p>


</div>






<div>

<p class="
text-sm
text-gray-500
">

Description

</p>


<p class="
font-semibold
text-gray-700
">

Student leadership development activity.

</p>


</div>



</div>


</div>









<!-- SCHEDULE -->


<div class="
bg-white
rounded-3xl
border
border-gray-100
shadow-sm
p-7
">


<h2 class="
text-xl
font-semibold
text-[#101064]
mb-6
">

Schedule

</h2>





<div class="space-y-5">



<div>

<p class="
text-sm
text-gray-500
">

Date

</p>


<p class="
font-semibold
text-gray-700
">

September 25, 2026

</p>


</div>





<div>

<p class="
text-sm
text-gray-500
">

Time

</p>


<p class="
font-semibold
text-gray-700
">

8:00 AM - 5:00 PM

</p>


</div>





<div>

<p class="
text-sm
text-gray-500
">

Duration

</p>


<p class="
font-semibold
text-gray-700
">

9 Hours

</p>


</div>



</div>


</div>




</div>









<!-- ASSIGNED PERSONNEL -->


<div class="
bg-white
rounded-3xl
border
border-gray-100
shadow-sm
p-7
">


<h2 class="
text-xl
font-semibold
text-[#101064]
mb-6
">

Assigned Personnel

</h2>






<div class="
grid
grid-cols-1
md:grid-cols-2
gap-5
">





<div class="
border
border-gray-200
rounded-2xl
p-5
flex
items-center
gap-4
">


<div class="
w-12
h-12
rounded-full
bg-[#101064]
text-white
flex
items-center
justify-center
font-bold
">

J

</div>



<div>

<p class="
font-semibold
text-[#101064]
">

John Reyes

</p>


<p class="
text-sm
text-gray-500
">

Attendance Personnel

</p>


</div>



</div>








<div class="
border
border-gray-200
rounded-2xl
p-5
flex
items-center
gap-4
">


<div class="
w-12
h-12
rounded-full
bg-[#D4A017]
text-white
flex
items-center
justify-center
font-bold
">

M

</div>



<div>

<p class="
font-semibold
text-[#101064]
">

Maria Santos

</p>


<p class="
text-sm
text-gray-500
">

Event Staff

</p>


</div>



</div>





</div>


</div>









<!-- FUTURE ATTENDANCE SECTION -->


<div class="
bg-[#101064]
rounded-3xl
p-8
text-white
">


<h2 class="
text-xl
font-semibold
">

Attendance Operations

</h2>


<p class="
mt-2
text-white/70
">

Once the event starts, assigned personnel can activate RFID attendance monitoring.

</p>





<div class="
mt-5
flex
gap-3
">


<button

class="
px-6
py-3
rounded-xl
bg-white
text-[#101064]
font-semibold
"

>

Open Attendance Monitor

</button>


</div>



</div>






</div>


</x-admin-layout>