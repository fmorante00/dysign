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

Event Management

</h1>


<p class="
mt-2
text-gray-500
">

Manage approved school events and assigned personnel.

</p>


</div>




<a

href="{{ route('events.create') }}"

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

+ Create Event

</a>



</div>









<!-- SUMMARY CARDS -->


<div class="
grid
grid-cols-1
md:grid-cols-4
gap-5
">



<div class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-5
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

8

</h2>


</div>







<div class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-5
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
p-5
">


<p class="
text-sm
text-gray-500
">

Completed

</p>


<h2 class="
text-3xl
font-bold
text-gray-600
mt-2
">

24

</h2>


</div>







<div class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-5
">


<p class="
text-sm
text-gray-500
">

Cancelled

</p>


<h2 class="
text-3xl
font-bold
text-red-500
mt-2
">

2

</h2>


</div>



</div>









<!-- EVENT DIRECTORY -->


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

Event Directory

</h2>


<p class="
text-sm
text-gray-500
mt-1
">

View and manage scheduled events.

</p>


</div>


</div>





<!-- SEARCH AND FILTER -->


<div class="
mt-6
grid
grid-cols-1
md:grid-cols-3
gap-4
">



<input

type="text"

placeholder="Search event..."

class="
rounded-xl
border
border-gray-300
px-4
py-3
focus:ring-2
focus:ring-[#D4A017]
"

>





<select

class="
rounded-xl
border
border-gray-300
px-4
py-3
"

>

<option>
All Status
</option>

<option>
Upcoming
</option>

<option>
Active
</option>

<option>
Completed
</option>

<option>
Cancelled
</option>


</select>




</div>



</div>









<div class="overflow-visible">


<table class="w-full">


<thead class="bg-gray-50">


<tr>


<th class="
px-6
py-4
text-left
text-sm
text-gray-500
">

Event

</th>


<th class="
px-6
py-4
text-left
text-sm
text-gray-500
">

Department

</th>


<th class="
px-6
py-4
text-left
text-sm
text-gray-500
">

Schedule

</th>


<th class="
px-6
py-4
text-left
text-sm
text-gray-500
">

Status

</th>


<th class="
px-6
py-4
text-left
text-sm
text-gray-500
">

Personnel

</th>


<th class="
px-6
py-4
text-left
text-sm
text-gray-500
">

Action

</th>


</tr>


</thead>

<tbody>


<!-- EVENT 1 -->

<tr class="
border-t
hover:bg-gray-50
transition
">


<td class="
px-6
py-6
">


<p class="
font-semibold
text-[#101064]
">

Leadership Seminar

</p>


<p class="
text-sm
text-gray-500
">

Student Development Program

</p>


</td>






<td class="
px-6
py-6
text-gray-600
">

College of Business

</td>






<td class="
px-6
py-6
">


<p class="
font-medium
text-gray-700
">

September 25, 2026

</p>


<p class="
text-sm
text-gray-500
">

8:00 AM - 5:00 PM

</p>


</td>






<td class="
px-6
py-6
">


<span class="
px-3
py-1
rounded-full
text-xs
font-semibold
bg-yellow-50
text-yellow-700
">

Upcoming

</span>


</td>






<td class="
px-6
py-6
">


<div class="
flex
-space-x-2
">


<div class="
w-9
h-9
rounded-full
bg-[#101064]
text-white
flex
items-center
justify-center
font-bold
border-2
border-white
">

J

</div>


<div class="
w-9
h-9
rounded-full
bg-[#D4A017]
text-white
flex
items-center
justify-center
font-bold
border-2
border-white
">

M

</div>


</div>


<p class="
text-xs
text-gray-500
mt-2
">

2 Assigned Personnel

</p>


</td>







<td class="
px-6
py-6
relative
">


<button

onclick="toggleMenu('menu1')"

class="
w-10
h-10
rounded-xl
border
border-gray-200
hover:bg-gray-100
transition
"

>

⋮

</button>





<div

id="menu1"

class="
hidden
absolute
right-5
mt-2
w-48
bg-white
rounded-xl
shadow-lg
border
border-gray-100
z-20
"

>


<a

href="{{ route('events.show', 1) }}"

class="
block
px-4
py-3
text-sm
hover:bg-gray-50
"

>

View Event

</a>



<a

href="#"

class="
block
px-4
py-3
text-sm
hover:bg-gray-50
"

>

Edit Event

</a>



<a

href="#"

class="
block
px-4
py-3
text-sm
hover:bg-gray-50
"

>

Assign Personnel

</a>



<a

href="#"

class="
block
px-4
py-3
text-sm
text-red-600
hover:bg-red-50
"

>

Cancel Event

</a>



</div>



</td>


</tr>









<!-- EVENT 2 -->


<tr class="
border-t
hover:bg-gray-50
transition
">


<td class="
px-6
py-6
">


<p class="
font-semibold
text-[#101064]
">

College Assembly

</p>


<p class="
text-sm
text-gray-500
">

General Student Activity

</p>


</td>






<td class="
px-6
py-6
text-gray-600
">

University Administration

</td>






<td class="
px-6
py-6
">


<p class="
font-medium
text-gray-700
">

October 5, 2026

</p>


<p class="
text-sm
text-gray-500
">

9:00 AM - 12:00 PM

</p>


</td>






<td class="
px-6
py-6
">


<span class="
px-3
py-1
rounded-full
text-xs
font-semibold
bg-green-50
text-green-700
">

Active

</span>


</td>







<td class="
px-6
py-6
">


<div class="
w-9
h-9
rounded-full
bg-[#101064]
text-white
flex
items-center
justify-center
font-bold
">

A

</div>


<p class="
text-xs
text-gray-500
mt-2
">

1 Assigned Personnel

</p>


</td>







<td class="
px-6
py-6
relative
">


<button

onclick="toggleMenu('menu2')"

class="
w-10
h-10
rounded-xl
border
border-gray-200
hover:bg-gray-100
transition
"

>

⋮

</button>





<div

id="menu2"

class="
hidden
absolute
right-5
mt-2
w-48
bg-white
rounded-xl
shadow-lg
border
border-gray-100
z-20
"

>


<a

href="{{ route('events.show', 2) }}"

class="
block
px-4
py-3
text-sm
hover:bg-gray-50
"

>

View Event

</a>



<a

href="#"

class="
block
px-4
py-3
text-sm
hover:bg-gray-50
"

>

Edit Event

</a>



<a

href="#"

class="
block
px-4
py-3
text-sm
hover:bg-gray-50
"

>

Assign Personnel

</a>



<a

href="#"

class="
block
px-4
py-3
text-sm
text-red-600
hover:bg-red-50
"

>

Cancel Event

</a>



</div>



</td>


</tr>






</tbody>


</table>


</div>


</div>





</div>





<script>

function toggleMenu(id){

let menu = document.getElementById(id);


document.querySelectorAll('[id^="menu"]')
.forEach(item => {

if(item.id !== id){

item.classList.add('hidden');

}

});



menu.classList.toggle('hidden');


}



document.addEventListener('click', function(event){


if(!event.target.closest('button')){


document.querySelectorAll('[id^="menu"]')
.forEach(item => {

item.classList.add('hidden');

});


}


});


</script>






</x-admin-layout>