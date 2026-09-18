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

Assign RFID

</h1>


<p class="
mt-2
text-gray-500
">

Assign an RFID identifier to a student record.

</p>


</div>




<a

href="{{ route('rfid.index') }}"

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









<!-- FORM CARD -->


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

RFID Assignment Details

</h2>







<!-- STUDENT SELECT -->


<div class="mb-6">


<label class="
block
text-sm
text-gray-500
mb-2
">

Select Student

</label>



<select

class="
w-full
rounded-xl
border-gray-200
focus:ring-[#D4A017]
focus:border-[#D4A017]
"

>


<option>

Juan Dela Cruz - 2026-0001

</option>


<option>

Maria Santos - 2026-0002

</option>


</select>



</div>








<!-- STUDENT INFO -->


<div class="
bg-gray-50
rounded-2xl
p-6
mb-6
">


<h3 class="
font-semibold
text-[#101064]
mb-4
">

Student Information

</h3>



<div class="
grid
grid-cols-1
md:grid-cols-3
gap-5
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



</div>



</div>









<!-- RFID INPUT -->


<div class="mb-6">


<label class="
block
text-sm
text-gray-500
mb-2
">

RFID UID

</label>


<input

type="text"

value="04-A8-93-XX-21"

class="
w-full
rounded-xl
border-gray-200
focus:ring-[#D4A017]
focus:border-[#D4A017]
"

>



</div>









<!-- STATUS -->


<div class="mb-8">


<label class="
block
text-sm
text-gray-500
mb-2
">

Card Status

</label>



<div class="
flex
items-center
gap-3
">


<span class="
bg-green-50
text-green-700
px-4
py-2
rounded-full
text-sm
font-semibold
">

Active

</span>


<p class="
text-sm
text-gray-500
">

Ready for assignment

</p>


</div>


</div>









<!-- BUTTONS -->


<div class="
flex
justify-end
gap-3
">


<a

href="{{ route('rfid.index') }}"

class="
px-6
py-3
rounded-xl
border
border-gray-300
text-gray-600
hover:bg-gray-50
transition
"

>

Cancel

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

Assign RFID

</button>



</div>







</div>








</div>


</x-admin-layout>