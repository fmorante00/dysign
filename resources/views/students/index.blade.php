<x-admin-layout>

@if(session('success'))

<div class="
bg-green-50
border
border-green-200
text-green-700
px-5
py-4
rounded-xl
mb-6
">

<p class="font-semibold mb-2">
✓ Student Import Completed
</p>

<p>
New Students Added:
{{ session('success')['created'] }}
</p>

<p>
Updated Records:
{{ session('success')['updated'] }}
</p>

<p>
Failed Records:
{{ session('success')['failed'] }}
</p>

</div>

@endif



<div class="space-y-8">



<!-- HEADER -->

<div class="flex justify-between items-center">

<div>

<h1 class="text-3xl font-bold text-[#101064]">
Student Records
</h1>


<p class="mt-2 text-gray-500">
View imported student information and RFID identifiers used for attendance identification.
</p>

</div>

</div>





<!-- SUMMARY -->

<div class="grid grid-cols-1 md:grid-cols-3 gap-5">


<div class="
bg-white
rounded-2xl
border
border-gray-100
p-5
shadow-sm
">

<p class="text-sm text-gray-500">
Total Students
</p>


<h2 class="
text-3xl
font-bold
text-[#101064]
mt-2
">

{{ $students->count() }}

</h2>


<p class="text-xs text-gray-400 mt-1">
Imported student records
</p>


</div>




<div class="
bg-white
rounded-2xl
border
border-gray-100
p-5
shadow-sm
">

<p class="text-sm text-gray-500">
Students with RFID Identifier
</p>


<h2 class="
text-3xl
font-bold
text-green-600
mt-2
">

{{ $students->whereNotNull('rfid_identifier')->count() }}

</h2>


<p class="text-xs text-gray-400 mt-1">
Available for attendance identification
</p>


</div>




<div class="
bg-white
rounded-2xl
border
border-gray-100
p-5
shadow-sm
">

<p class="text-sm text-gray-500">
Missing RFID Identifier
</p>


<h2 class="
text-3xl
font-bold
text-[#D4A017]
mt-2
">

{{ $students->whereNull('rfid_identifier')->count() }}

</h2>


<p class="text-xs text-gray-400 mt-1">
Requires data verification
</p>


</div>


</div>







<!-- DIRECTORY -->

<div class="
bg-white
rounded-3xl
border
border-gray-100
shadow-sm
">



<div class="
p-6
border-b
border-gray-100
">


<div class="
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

Student Directory

</h2>


<p class="
text-sm
text-gray-500
mt-1
">

View imported student records.

</p>


</div>



<a href="{{ route('students.import') }}"
class="
px-5
py-2
rounded-xl
border
border-[#D4A017]
text-[#8A6D00]
hover:bg-[#F8F5E8]
transition
text-sm
font-semibold
">

Import Students

</a>


</div>





<!-- FILTERS -->

<form method="GET"
action="{{ route('students.index') }}"
class="
mt-6
grid
grid-cols-1
md:grid-cols-4
gap-4
">


<input
type="text"
name="search"
value="{{ request('search') }}"
placeholder="Search student number or name..."
class="
border
border-gray-200
rounded-xl
px-4
py-3
text-sm
"
/>



<select
name="college"
class="
border
border-gray-200
rounded-xl
px-4
py-3
text-sm
">

<option value="">
All Colleges
</option>


@foreach($students->pluck('college')->unique() as $college)

<option
value="{{ $college }}"
{{ request('college') == $college ? 'selected' : '' }}
>

{{ $college }}

</option>

@endforeach

</select>




<select
name="program_code"
class="
border
border-gray-200
rounded-xl
px-4
py-3
text-sm
">

<option value="">
All Programs
</option>


@foreach($students->pluck('program_code')->unique() as $program)

<option
value="{{ $program }}"
{{ request('program_code') == $program ? 'selected' : '' }}
>

{{ $program }}

</option>

@endforeach

</select>




<select
name="year_level"
class="
border
border-gray-200
rounded-xl
px-4
py-3
text-sm
">

<option value="">
All Year Levels
</option>


@for($year = 1; $year <= 5; $year++)

<option
value="{{ $year }}"
{{ request('year_level') == $year ? 'selected' : '' }}
>

{{ $year }} Year

</option>

@endfor


</select>



<div class="md:col-span-4 flex justify-end">


<button
type="submit"
class="
px-6
py-3
rounded-xl
bg-[#101064]
text-white
font-semibold
text-sm
hover:bg-[#D4A017]
transition
">

Search

</button>


</div>


</form>


</div>








<!-- TABLE -->


<div class="overflow-visible">


<table class="w-full">


<thead class="bg-gray-50">


<tr>


<th class="px-6 py-4 text-left text-sm text-gray-500">
Student ID
</th>


<th class="px-6 py-4 text-left text-sm text-gray-500">
Student Name
</th>


<th class="px-6 py-4 text-left text-sm text-gray-500">
Program
</th>


<th class="px-6 py-4 text-left text-sm text-gray-500">
Year Level
</th>


<th class="px-6 py-4 text-left text-sm text-gray-500">
RFID Status
</th>


<th class="px-6 py-4 text-left text-sm text-gray-500">
Status
</th>


<th class="px-6 py-4 text-left text-sm text-gray-500">
Action
</th>


</tr>


</thead>




<tbody>


@forelse($students as $student)


<tr class="
border-t
hover:bg-gray-50
transition
">



<td class="
px-6
py-6
font-medium
text-gray-700
">

{{ $student->student_number }}

</td>




<td class="px-6 py-6">


<div class="flex items-center gap-3">


<div class="
w-10
h-10
rounded-full
bg-[#101064]
text-white
flex
items-center
justify-center
font-bold
">

{{ strtoupper(substr($student->first_name,0,1)) }}

</div>



<div>


<p class="
font-semibold
text-[#101064]
">

{{ $student->first_name }}
{{ $student->middle_name }}
{{ $student->last_name }}

</p>


<p class="
text-sm
text-gray-500
">

{{ $student->student_number }}

</p>


</div>


</div>


</td>




<td class="px-6 py-6 text-gray-600">

{{ $student->program_code }}

</td>




<td class="px-6 py-6 text-gray-600">

{{ $student->year_level }} Year

</td>




<td class="px-6 py-6">


@if($student->rfid_identifier)

<span class="
bg-green-50
text-green-700
px-3
py-1
rounded-full
text-xs
font-semibold
">

Assigned

</span>


@else


<span class="
bg-gray-100
text-gray-600
px-3
py-1
rounded-full
text-xs
font-semibold
">

Missing

</span>


@endif


</td>




<td class="px-6 py-6">


@if($student->status === 'Active')

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


@else


<span class="
bg-gray-100
text-gray-600
px-3
py-1
rounded-full
text-xs
font-semibold
">

Inactive

</span>


@endif


</td>




<td class="px-6 py-6">


<a href="{{ route('students.show', $student->student_id) }}"
class="
text-[#101064]
text-sm
font-semibold
hover:underline
">

View

</a>


</td>



</tr>


@empty


<tr>

<td colspan="7"
class="
px-6
py-10
text-center
text-gray-400
">

No student records found.

</td>

</tr>


@endforelse


</tbody>


</table>


</div>


</div>


</div>


</x-admin-layout>