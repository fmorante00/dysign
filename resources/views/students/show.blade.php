<x-admin-layout>

<div class="space-y-8">


<!-- HEADER -->

<div class="flex justify-between items-center">


<div>

<h1 class="text-3xl font-bold text-[#101064]">
Student Profile
</h1>

<p class="mt-2 text-gray-500">
View student information and attendance identifier details.
</p>

</div>


<a href="{{ route('students.index') }}"
class="
px-5
py-3
rounded-xl
border
border-gray-300
text-gray-600
hover:bg-gray-50
transition
">

Back

</a>


</div>






<!-- PROFILE CARD -->


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
gap-5
pb-6
border-b
border-gray-100
">


<div class="
w-16
h-16
rounded-full
bg-[#101064]
text-white
flex
items-center
justify-center
text-2xl
font-bold
">

{{ strtoupper(substr($student->first_name,0,1)) }}

</div>



<div>

<h2 class="
text-2xl
font-bold
text-[#101064]
">

{{ $student->first_name }}
{{ $student->middle_name }}
{{ $student->last_name }}

</h2>


<p class="text-gray-500">

{{ $student->student_number }}

</p>


</div>


</div>







<!-- STUDENT INFORMATION -->


<div class="mt-8">


<h3 class="
text-lg
font-semibold
text-[#101064]
mb-5
">

Student Information

</h3>


<div class="
grid
grid-cols-1
md:grid-cols-2
gap-6
">


<div>

<p class="text-sm text-gray-400">
Student Number
</p>

<p class="font-semibold text-gray-700">
{{ $student->student_number }}
</p>

</div>



<div>

<p class="text-sm text-gray-400">
Student Status
</p>

<p class="font-semibold text-gray-700">
{{ $student->student_status }}
</p>

</div>



</div>


</div>







<!-- ACADEMIC INFORMATION -->


<div class="mt-10">


<h3 class="
text-lg
font-semibold
text-[#101064]
mb-5
">

Academic Information

</h3>


<div class="
grid
grid-cols-1
md:grid-cols-2
gap-6
">


<div>

<p class="text-sm text-gray-400">
College
</p>

<p class="font-semibold text-gray-700">
{{ $student->college }}
</p>

</div>



<div>

<p class="text-sm text-gray-400">
Program
</p>

<p class="font-semibold text-gray-700">
{{ $student->program_name }}
</p>

</div>



<div>

<p class="text-sm text-gray-400">
Program Code
</p>

<p class="font-semibold text-gray-700">
{{ $student->program_code }}
</p>

</div>



<div>

<p class="text-sm text-gray-400">
Year Level
</p>

<p class="font-semibold text-gray-700">
{{ $student->year_level }} Year
</p>

</div>


</div>


</div>







<!-- RFID INFORMATION -->


<div class="mt-10">


<h3 class="
text-lg
font-semibold
text-[#101064]
mb-5
">

RFID Identifier

</h3>



<div class="
bg-[#F8F5E8]
rounded-2xl
p-5
">


@if($student->rfid_identifier)


<p class="
text-sm
text-gray-500
">

Identifier

</p>


<p class="
text-xl
font-bold
text-[#101064]
">

{{ $student->rfid_identifier }}

</p>


<p class="
text-sm
text-green-700
mt-2
">

✓ Available for attendance identification

</p>


@else


<p class="
text-gray-500
">

No RFID identifier available.

</p>


@endif


</div>


</div>





</div>


</div>


</x-admin-layout>