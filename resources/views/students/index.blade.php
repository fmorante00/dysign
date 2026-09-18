<x-admin-layout>

<div

class="space-y-8"

x-data="{
    openMenu:null
}"

@click.outside="openMenu=null"

>


<!-- HEADER -->

<div class="flex justify-between items-center">


<div>

<h1 class="
text-3xl
font-bold
text-[#101064]
">
Student Data Management
</h1>


<p class="
mt-2
text-gray-500
">
Manage student records and RFID identity information.
</p>


</div>


</div>









<!-- SUMMARY CARDS -->


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

1,250

</h2>


<p class="text-xs text-gray-400 mt-1">
Registered records
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
RFID Assigned
</p>


<h2 class="
text-3xl
font-bold
text-green-600
mt-2
">

1,100

</h2>


<p class="text-xs text-gray-400 mt-1">
Active identity cards
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
Pending RFID
</p>


<h2 class="
text-3xl
font-bold
text-[#D4A017]
mt-2
">

150

</h2>


<p class="text-xs text-gray-400 mt-1">
Waiting assignment
</p>


</div>


</div>









<!-- TABLE -->

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
View and manage registered students.
</p>


</div>





<div class="flex gap-3">


<a

href="{{ route('students.import') }}"

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
"
>

Import Students

</a>





<button

class="
bg-[#101064]
text-white
px-5
py-2
rounded-xl
text-sm
font-semibold
hover:bg-[#D4A017]
transition
"

>

+ Add Student

</button>


</div>


</div>









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


<!-- SAMPLE STUDENT 1 -->

<tr class="
border-t
hover:bg-gray-50
transition
">


<td class="
px-6
py-6
text-gray-700
font-medium
">

2026-0001

</td>





<td class="
px-6
py-6
">


<div class="
flex
items-center
gap-3
">


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

J

</div>


<div>

<p class="
font-semibold
text-[#101064]
">

Juan Dela Cruz

</p>


<p class="
text-sm
text-gray-500
">

juan.delacruz@student.edu

</p>


</div>


</div>


</td>







<td class="
px-6
py-6
text-gray-600
">

BS Information Technology

</td>






<td class="
px-6
py-6
text-gray-600
">

1st Year

</td>







<td class="
px-6
py-6
">


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


</td>







<td class="
px-6
py-6
">


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


</td>







<td class="
px-6
py-6
relative
">


<div class="relative">


<button

@click="
openMenu === 1 
? openMenu=null 
: openMenu=1
"

class="
w-10
h-10
rounded-xl
border
border-gray-200
hover:bg-gray-100
transition
text-gray-600
"

>

⋮

</button>

<div

x-show="openMenu===1"

x-transition

class="
absolute
right-0
bottom-12
w-52
bg-white
rounded-xl
shadow-xl
border
border-gray-100
z-[999]
overflow-hidden
"

style="display:none;"

>


<a

href="{{ route('students.show',1) }}"

class="
block
px-5
py-3
text-sm
text-gray-700
hover:bg-gray-50
"

>

View Student Profile

</a>



<a

href="#"

class="
block
px-5
py-3
text-sm
text-gray-700
hover:bg-gray-50
"

>

Edit Student

</a>



<a

href="#"

class="
block
px-5
py-3
text-sm
text-[#8A6D00]
hover:bg-[#F8F5E8]
"

>

Assign RFID

</a>



<a

href="#"

class="
block
px-5
py-3
text-sm
text-gray-600
hover:bg-gray-50
"

>

Deactivate Student

</a>


</div>

</div>


</td>



</tr>









<!-- SAMPLE STUDENT 2 -->


<tr class="
border-t
hover:bg-gray-50
transition
">


<td class="
px-6
py-6
text-gray-700
font-medium
">

2026-0002

</td>





<td class="
px-6
py-6
">


<div class="
flex
items-center
gap-3
">


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

maria.santos@student.edu

</p>


</div>


</div>


</td>







<td class="
px-6
py-6
text-gray-600
">

BS Accountancy

</td>






<td class="
px-6
py-6
text-gray-600
">

2nd Year

</td>







<td class="
px-6
py-6
">


<span class="
bg-gray-100
text-gray-600
px-3
py-1
rounded-full
text-xs
font-semibold
">

Pending

</span>


</td>







<td class="
px-6
py-6
">


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


</td>







<td class="
px-6
py-6
relative
">


<div class="relative">


<button

@click="
openMenu === 2 
? openMenu=null 
: openMenu=2
"

class="
w-10
h-10
rounded-xl
border
border-gray-200
hover:bg-gray-100
transition
text-gray-600
"

>

⋮

</button>



<div

x-show="openMenu===2"

x-transition

class="
absolute
right-0
bottom-12
w-52
bg-white
rounded-xl
shadow-xl
border
border-gray-100
z-[999]
overflow-hidden
"

style="display:none;"

>


<a

href="{{ route('students.show',2) }}"

class="
block
px-5
py-3
text-sm
text-gray-700
hover:bg-gray-50
"

>

View Student Profile

</a>



<a

href="#"

class="
block
px-5
py-3
text-sm
text-gray-700
hover:bg-gray-50
"

>

Edit Student

</a>



<a

href="#"

class="
block
px-5
py-3
text-sm
text-[#8A6D00]
hover:bg-[#F8F5E8]
"

>

Assign RFID

</a>



<a

href="#"

class="
block
px-5
py-3
text-sm
text-gray-600
hover:bg-gray-50
"

>

Deactivate Student

</a>


</div>


</div>


</td>



</tr>





</tbody>


</table>


</div>


</div>







</div>


</x-admin-layout>