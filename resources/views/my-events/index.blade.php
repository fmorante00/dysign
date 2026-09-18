<x-admin-layout>

<div class="space-y-8">


<!-- HEADER -->

<div>


<h1 class="
text-3xl
font-bold
text-[#101064]
">

My Assigned Events

</h1>


<p class="
mt-2
text-gray-500
">

View events assigned to you and manage attendance operations.

</p>


</div>









<!-- SUMMARY -->


<div class="
grid
grid-cols-1
md:grid-cols-3
gap-5
">



<div class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-6
">


<p class="
text-sm
text-gray-500
">

Upcoming Events

</p>


<h2 class="
text-3xl
font-bold
text-[#101064]
mt-2
">

3

</h2>


</div>







<div class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-6
">


<p class="
text-sm
text-gray-500
">

Active Events

</p>


<h2 class="
text-3xl
font-bold
text-green-600
mt-2
">

1

</h2>


</div>







<div class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-6
">


<p class="
text-sm
text-gray-500
">

Completed Events

</p>


<h2 class="
text-3xl
font-bold
text-gray-600
mt-2
">

12

</h2>


</div>



</div>









<!-- EVENTS -->


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

Assigned Event List

</h2>







<div class="
space-y-5
">







<!-- EVENT CARD -->


<div class="
border
border-gray-200
rounded-2xl
p-6
hover:shadow-md
transition
">



<div class="
flex
justify-between
items-start
">



<div>


<h3 class="
text-xl
font-semibold
text-[#101064]
">

Leadership Seminar

</h3>



<p class="
text-sm
text-gray-500
mt-1
">

College of Business

</p>


</div>






<span class="
px-3
py-1
rounded-full
bg-yellow-50
text-yellow-700
text-xs
font-semibold
">

Upcoming

</span>



</div>







<div class="
grid
grid-cols-1
md:grid-cols-3
gap-5
mt-6
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









<div class="
flex
justify-end
mt-6
">


<a

href="{{ route('my-events.show',1) }}"

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

Open Event

</a>


</div>





</div>









<!-- ACTIVE EVENT -->


<div class="
border
border-green-200
bg-green-50
rounded-2xl
p-6
">



<div class="
flex
justify-between
items-start
">



<div>


<h3 class="
text-xl
font-semibold
text-[#101064]
">

College Assembly

</h3>



<p class="
text-sm
text-gray-500
mt-1
">

University Administration

</p>


</div>






<span class="
px-3
py-1
rounded-full
bg-green-100
text-green-700
text-xs
font-semibold
">

Active

</span>



</div>









<div class="
mt-6
flex
justify-between
items-center
">



<div>

<p class="
text-sm
text-gray-500
">

Your Role

</p>


<p class="
font-semibold
text-gray-700
">

Event Staff

</p>


</div>







<a

href="#"

class="
px-6
py-3
rounded-xl
bg-green-600
text-white
font-semibold
hover:bg-green-700
transition
"

>

Open Attendance

</a>



</div>



</div>







</div>



</div>








</div>


</x-admin-layout>