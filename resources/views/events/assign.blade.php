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

Assign Personnel

</h1>


<p class="
mt-2
text-gray-500
">

Assign attendance personnel responsible for this event.

</p>


</div>




<a

href="{{ route('events.show', 1) }}"

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









<!-- EVENT SUMMARY -->


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
text-blue-200
uppercase
tracking-wide
">

Event

</p>



<h2 class="
text-3xl
font-bold
mt-2
">

Leadership Seminar

</h2>



<div class="
mt-4
space-y-2
text-blue-100
">


<p>
Date: September 25, 2026
</p>


<p>
Time: 8:00 AM - 5:00 PM
</p>


<p>
Department: College of Business
</p>


</div>



</div>





<span class="
px-4
py-2
rounded-full
bg-[#D4A017]
text-[#101064]
font-semibold
text-sm
">

Upcoming

</span>



</div>


</div>









<!-- PERSONNEL SELECTION -->


<div class="
grid
grid-cols-1
lg:grid-cols-2
gap-6
">







<!-- AVAILABLE PERSONNEL -->


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
mb-2
">

Available Personnel

</h2>


<p class="
text-sm
text-gray-500
mb-6
">

Select personnel who will handle attendance operations.

</p>







<div class="
space-y-4
">







<!-- PERSON 1 -->


<label class="
flex
items-center
gap-4
border
border-gray-200
rounded-2xl
p-5
cursor-pointer
hover:bg-gray-50
transition
">


<input

type="checkbox"

class="
w-5
h-5
text-[#101064]
rounded
"

>



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



</label>









<!-- PERSON 2 -->


<label class="
flex
items-center
gap-4
border
border-gray-200
rounded-2xl
p-5
cursor-pointer
hover:bg-gray-50
transition
">


<input

type="checkbox"

class="
w-5
h-5
text-[#101064]
rounded
"

>



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

Attendance Personnel

</p>


</div>



</label>

<!-- CURRENT SELECTION -->


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
mb-2
">

Selected Personnel

</h2>


<p class="
text-sm
text-gray-500
mb-6
">

Personnel assigned to this event.

</p>







<div class="
space-y-4
">






<div class="
flex
items-center
justify-between
border
border-gray-200
rounded-2xl
p-5
">


<div class="
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





<button

class="
text-sm
text-red-500
hover:text-red-700
"

>

Remove

</button>


</div>









<div class="
flex
items-center
justify-between
border
border-gray-200
rounded-2xl
p-5
">


<div class="
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

Attendance Personnel

</p>


</div>



</div>





<button

class="
text-sm
text-red-500
hover:text-red-700
"

>

Remove

</button>


</div>







</div>








</div>







</div>









<!-- ACTION AREA -->


<div class="
bg-gray-50
rounded-3xl
border
border-gray-200
p-8
flex
flex-col
md:flex-row
items-center
justify-between
gap-5
">


<div>


<h2 class="
text-lg
font-semibold
text-[#101064]
">

Ready to assign?

</h2>


<p class="
text-sm
text-gray-500
mt-1
">

Selected personnel will receive this event on their attendance dashboard.

</p>


</div>







<button

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

Assign Personnel

</button>



</div>







</div>


</x-admin-layout>