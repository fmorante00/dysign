<x-admin-layout>

<div class="space-y-8">



<!-- HEADER -->

<div class="flex justify-between items-start">


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

href="{{ route('my-events.index') }}"

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
bg-[#101064]
rounded-3xl
p-8
text-white
shadow-sm
">


<div class="
flex
justify-between
items-start
">


<div>


<p class="
text-sm
uppercase
tracking-wide
text-blue-200
">

Attendance Status

</p>



<h2 class="
text-3xl
font-bold
mt-2
">

Upcoming

</h2>



<p class="
mt-4
text-blue-100
">

You are assigned as Attendance Personnel for this event.

</p>



</div>





<span class="
bg-[#D4A017]
text-[#101064]
px-4
py-2
rounded-full
text-sm
font-semibold
">

Assigned

</span>



</div>





</div>









<!-- EVENT INFORMATION -->


<div class="
grid
grid-cols-1
lg:grid-cols-2
gap-6
">





<div class="
bg-white
rounded-3xl
border
border-gray-100
shadow-sm
p-8
">


<h2 class="
text-xl
font-semibold
text-[#101064]
mb-6
">

Event Information

</h2>




<div class="
space-y-5
">



<div>

<p class="
text-sm
text-gray-500
">

Department

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

Venue

</p>

<p class="
font-semibold
text-gray-700
">

Main Auditorium

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

Leadership development seminar for students.

</p>

</div>



</div>


</div>








<div class="
bg-white
rounded-3xl
border
border-gray-100
shadow-sm
p-8
">


<h2 class="
text-xl
font-semibold
text-[#101064]
mb-6
">

Schedule

</h2>





<div class="
space-y-5
">



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

Assigned Role

</p>


<p class="
font-semibold
text-gray-700
">

Attendance Personnel

</p>


</div>




</div>


</div>






</div>









<!-- ATTENDANCE ACTION -->


<div class="
bg-white
rounded-3xl
border
border-gray-100
shadow-sm
p-8
">


<div class="
flex
flex-col
md:flex-row
justify-between
items-center
gap-5
">


<div>


<h2 class="
text-xl
font-semibold
text-[#101064]
">

Attendance Operations

</h2>


<p class="
mt-2
text-gray-500
">

Start monitoring attendance once the event begins.

</p>


</div>






<a

href="{{ route('attendance.index') }}"

class="
px-8
py-3
rounded-xl
bg-[#101064]
text-white
font-semibold
hover:bg-[#D4A017]
transition
"

>

Start Attendance

</a>




</div>


</div>






</div>


</x-admin-layout>