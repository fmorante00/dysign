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

Student Profile

</h1>


<p class="
mt-2
text-gray-500
">

View student information and RFID identity details.

</p>


</div>




<a

href="{{ route('students.index') }}"

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









<!-- PROFILE HEADER -->


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
items-center
gap-6
">


<div class="
w-20
h-20
rounded-full
bg-[#101064]
text-white
flex
items-center
justify-center
text-3xl
font-bold
">

J

</div>





<div class="flex-1">


<h2 class="
text-2xl
font-bold
text-[#101064]
">

Juan Dela Cruz

</h2>


<p class="
text-gray-500
mt-1
">

BS Information Technology

</p>


<p class="
text-sm
text-gray-400
">

1st Year

</p>


</div>




<span class="
bg-green-50
text-green-700
px-4
py-2
rounded-full
text-sm
font-semibold
">

Active Student

</span>



</div>


</div>










<!-- INFORMATION -->


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

Student Information

</h2>




<div class="
grid
grid-cols-1
md:grid-cols-2
gap-6
">



<div>

<p class="text-sm text-gray-400">
Student ID
</p>

<p class="font-semibold text-gray-800">
2026-0001
</p>

</div>




<div>

<p class="text-sm text-gray-400">
Full Name
</p>

<p class="font-semibold text-gray-800">
Juan Dela Cruz
</p>

</div>





<div>

<p class="text-sm text-gray-400">
Email Address
</p>

<p class="font-semibold text-gray-800">
juan.delacruz@student.edu
</p>

</div>





<div>

<p class="text-sm text-gray-400">
Program
</p>

<p class="font-semibold text-gray-800">
BS Information Technology
</p>

</div>






<div>

<p class="text-sm text-gray-400">
Year Level
</p>

<p class="font-semibold text-gray-800">
1st Year
</p>

</div>



<div>

<p class="text-sm text-gray-400">
Section
</p>

<p class="font-semibold text-gray-800">
A
</p>

</div>



</div>


</div>









<!-- RFID INFORMATION -->


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

RFID Identity Information

</h2>




<div class="
grid
grid-cols-1
md:grid-cols-3
gap-6
">



<div>

<p class="text-sm text-gray-400">
RFID UID
</p>

<p class="font-semibold text-gray-800">
04-A8-93-XX-21
</p>

</div>




<div>

<p class="text-sm text-gray-400">
Card Status
</p>


<span class="
bg-green-50
text-green-700
px-3
py-1
rounded-full
text-xs
font-semibold
">

Active

</span>


</div>





<div>

<p class="text-sm text-gray-400">
Assigned Date
</p>

<p class="font-semibold text-gray-800">
September 18, 2026
</p>

</div>



</div>



</div>









<!-- ACTIONS -->


<div class="
flex
justify-end
gap-3
">


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

Edit Student

</button>


</div>







</div>


</x-admin-layout>