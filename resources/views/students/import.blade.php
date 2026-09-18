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

Import Student Data

</h1>


<p class="
mt-2
text-gray-500
">

Upload and register student records through bulk import.

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









<!-- UPLOAD CARD -->


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

Upload Student File

</h2>







<div class="
border-2
border-dashed
border-gray-300
rounded-2xl
p-10
text-center
">


<div class="
w-16
h-16
mx-auto
rounded-full
bg-[#F8F5E8]
flex
items-center
justify-center
mb-4
">


<span class="
text-2xl
text-[#D4A017]
font-bold
">

↑

</span>


</div>





<h3 class="
font-semibold
text-gray-700
">

Choose Student Data File

</h3>



<p class="
text-sm
text-gray-400
mt-2
">

Supported formats: .xlsx, .csv

</p>





<button

class="
mt-6
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

Choose File

</button>



</div>



</div>








<!-- TEMPLATE CARD -->


<div class="
bg-white
rounded-3xl
border
border-gray-100
shadow-sm
p-8
flex
justify-between
items-center
">


<div>


<h2 class="
text-xl
font-semibold
text-[#101064]
">

Import Template

</h2>


<p class="
text-sm
text-gray-500
mt-1
">

Use the standard student information template before uploading.

</p>


</div>





<button

class="
px-5
py-3
rounded-xl
border
border-[#D4A017]
text-[#8A6D00]
hover:bg-[#F8F5E8]
transition
font-semibold
"

>

Download Template

</button>



</div>








<!-- PROCESS -->


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

Import Process

</h2>





<div class="
grid
grid-cols-1
md:grid-cols-4
gap-5
">


@foreach([
'Upload File',
'Validate Records',
'Preview Data',
'Confirm Import'
] as $index=>$step)


<div class="
border
border-gray-100
rounded-2xl
p-5
text-center
">


<div class="
w-10
h-10
mx-auto
rounded-full
bg-[#101064]
text-white
flex
items-center
justify-center
font-bold
mb-3
">

{{ $index + 1 }}

</div>


<p class="
font-semibold
text-gray-700
">

{{ $step }}

</p>


</div>


@endforeach


</div>




</div>









<div class="flex justify-end">


<button

class="
px-7
py-3
rounded-xl
bg-[#101064]
text-white
font-semibold
hover:bg-[#D4A017]
transition
"

>

Validate File

</button>


</div>







</div>


</x-admin-layout>