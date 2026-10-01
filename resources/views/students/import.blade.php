<x-admin-layout>

<div class="space-y-8">

<div class="flex justify-between items-center">

    <div>
        <h1 class="text-3xl font-bold text-[#101064]">
            Import Student Data
        </h1>

        <p class="mt-2 text-gray-500">
            Upload student records provided by the registrar.
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



@if(session('success'))

<div class="
bg-green-50
border
border-green-200
text-green-700
px-5
py-4
rounded-xl
flex
items-center
gap-3
">

<div class="
w-8
h-8
rounded-full
bg-green-500
text-white
flex
items-center
justify-center
font-bold
">

✓

</div>

<p class="font-semibold">
{{ session('success') }}
</p>

</div>

@endif



@if($errors->any())

<div class="
bg-red-50
border
border-red-200
text-red-700
px-5
py-4
rounded-xl
">

{{ $errors->first() }}

</div>

@endif




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



<form action="{{ route('students.process-import') }}"
method="POST"
enctype="multipart/form-data">

@csrf



<div
x-data="{ fileName: '' }"
class="
border-2
border-dashed
border-gray-300
rounded-2xl
p-10
text-center
">


<div class="
w-14
h-14
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
font-bold
text-[#D4A017]
">

↑

</span>

</div>



<p class="
font-semibold
text-gray-700
mb-4
">

Select student Excel or CSV file

</p>



<label class="
inline-block
cursor-pointer
px-6
py-3
rounded-xl
bg-[#101064]
text-white
font-semibold
hover:bg-[#D4A017]
transition
">

Choose File


<input
type="file"
name="file"
accept=".xlsx,.xls,.csv"
required
class="hidden"

@change="fileName = $event.target.files[0].name"

>

</label>



<div
x-show="fileName"
class="
mt-5
bg-green-50
border
border-green-200
rounded-xl
px-5
py-4
flex
items-center
justify-center
gap-3
">


<div class="
w-9
h-9
rounded-full
bg-green-500
text-white
flex
items-center
justify-center
font-bold
">

✓

</div>



<div>

<p class="
text-green-700
font-semibold
"
x-text="fileName">

</p>


<p class="
text-sm
text-green-600
">

File ready for import

</p>


</div>


</div>



<p class="
text-sm
text-gray-400
mt-5
">

Accepted formats: .xlsx, .xls, .csv

</p>


</div>




<div class="flex justify-end mt-6">

<button
type="submit"
class="
px-7
py-3
rounded-xl
bg-[#D4A017]
text-white
font-semibold
hover:bg-[#101064]
transition
">

Import Students

</button>

</div>



</form>


</div>


</div>

</x-admin-layout>