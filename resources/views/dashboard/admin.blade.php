<x-admin-layout>

<div class="p-6 pb-12">


<!-- HEADER -->

<div class="mb-8">

    <h1 class="
    text-3xl
    font-bold
    text-[#11175A]
    ">

        Welcome back, Administrator

    </h1>


    <p class="
    mt-2
    text-gray-500
    ">

        Monitor DySign system activities and manage digital identity operations.

    </p>


</div>






<!-- SUMMARY CARDS -->


<div class="
grid
grid-cols-1
md:grid-cols-2
xl:grid-cols-4
gap-5
mb-8
">



<!-- USERS -->


<div class="
bg-white
rounded-2xl
shadow-sm
border
border-gray-200
p-6
">


<p class="
text-sm
text-gray-500
">

System Users

</p>


<h2 class="
text-3xl
font-bold
text-[#11175A]
mt-3
">

{{ $totalUsers }}

</h2>


</div>







<!-- ACTIVE -->


<div class="
bg-white
rounded-2xl
shadow-sm
border
border-gray-200
p-6
">


<p class="
text-sm
text-gray-500
">

Active Accounts

</p>


<h2 class="
text-3xl
font-bold
text-green-600
mt-3
">

{{ $activeUsers }}

</h2>


</div>








<!-- PERSONNEL -->


<div class="
bg-white
rounded-2xl
shadow-sm
border
border-gray-200
p-6
">


<p class="
text-sm
text-gray-500
">

Personnel

</p>


<h2 class="
text-3xl
font-bold
text-[#11175A]
mt-3
">

{{ $totalPersonnel }}

</h2>


</div>








<!-- ROLES -->


<div class="
bg-white
rounded-2xl
shadow-sm
border
border-gray-200
p-6
">


<p class="
text-sm
text-gray-500
">

System Roles

</p>


<h2 class="
text-3xl
font-bold
text-[#11175A]
mt-3
">

{{ $totalRoles }}

</h2>


</div>




</div>









<!-- CALENDAR + ACCOUNT DISTRIBUTION -->


<div class="
grid
grid-cols-1
gap-6
mb-8
">





<!-- CALENDAR -->


<div class="
bg-white
rounded-2xl
shadow-sm
border
border-gray-200
p-6
">


<div class="
flex
justify-between
items-center
mb-6
">


<div>


<h2 id="calendarTitle"
class="
text-xl
font-semibold
text-[#11175A]
">

September 2026

</h2>


<p class="
text-sm
text-gray-500
mt-1
">

System Events Calendar

</p>


</div>





<div class="
flex
gap-2
">


<button
onclick="changeMonth(-1)"
class="
border
border-gray-300
rounded-lg
px-3
py-2
text-gray-600
">

←

</button>



<button
onclick="changeMonth(1)"
class="
border
border-gray-300
rounded-lg
px-3
py-2
text-gray-600
">

→

</button>



</div>


</div>









<!-- DAYS -->


<div class="
grid
grid-cols-7
text-center
text-xs
font-semibold
text-gray-400
mb-3
">


<div>Sun</div>
<div>Mon</div>
<div>Tue</div>
<div>Wed</div>
<div>Thu</div>
<div>Fri</div>
<div>Sat</div>


</div>








<!-- DATE GRID -->


<div class="
grid
grid-cols-7
gap-3
">


@for($day = 1; $day <= 30; $day++)


<div class="
h-16
border
border-gray-200
rounded-xl
p-2
text-sm
text-gray-600
relative
hover:bg-gray-50
transition
">


<span>

{{ $day }}

</span>





@if($day == 18)


<div class="
absolute
bottom-2
left-2
w-2
h-2
rounded-full
bg-[#D4A017]
">

</div>


@endif






@if($day == 25)


<div class="
absolute
bottom-2
left-2
w-2
h-2
rounded-full
bg-[#11175A]
">

</div>


@endif



</div>


@endfor


</div>









<div class="
flex
gap-5
mt-5
text-xs
text-gray-500
">


<div class="
flex
items-center
gap-2
">


<span class="
w-2
h-2
rounded-full
bg-[#11175A]
">

</span>


Upcoming Event


</div>





<div class="
flex
items-center
gap-2
">


<span class="
w-2
h-2
rounded-full
bg-[#D4A017]
">

</span>


System Activity


</div>



</div>




</div>


 

<!-- UPCOMING EVENTS -->


<div class="
bg-white
rounded-2xl
shadow-sm
border
border-gray-200
p-6
mb-8
">



<div class="
flex
justify-between
items-center
mb-5
">


<div>


<h2 class="
text-xl
font-semibold
text-[#11175A]
">

Upcoming Events

</h2>



<p class="
text-sm
text-gray-500
mt-1
">

Scheduled activities from Event Management Module

</p>


</div>




<a

href="{{ route('events.index') }}"

class="
text-sm
font-semibold
text-[#11175A]
hover:underline
"

>

View Events

</a>



</div>








<!-- EVENT PLACEHOLDER -->

<div class="space-y-4">



<div class="
border
border-gray-200
rounded-xl
p-4
flex
justify-between
items-center
">


<div>


<h3 class="
font-semibold
text-[#11175A]
">

Leadership Seminar

</h3>



<p class="
text-sm
text-gray-500
mt-1
">

September 25, 2026 • Main Auditorium

</p>


</div>



<span class="
px-3
py-1
rounded-full
text-xs
bg-yellow-100
text-yellow-700
">

Upcoming

</span>


</div>








<div class="
border
border-gray-200
rounded-xl
p-4
flex
justify-between
items-center
">


<div>


<h3 class="
font-semibold
text-[#11175A]
">

NSTP Orientation

</h3>



<p class="
text-sm
text-gray-500
mt-1
">

October 05, 2026 • AVR Room

</p>


</div>



<span class="
px-3
py-1
rounded-full
text-xs
bg-blue-100
text-blue-700
">

Scheduled

</span>


</div>



</div>





</div>


<!-- RECENT SYSTEM ACTIVITIES -->


<div class="
bg-white
rounded-2xl
shadow-sm
border
border-gray-200
p-6
mb-8
">


<div class="
flex
justify-between
items-center
mb-5
">


<div>


<h2 class="
text-xl
font-semibold
text-[#11175A]
">

Recent System Activities

</h2>


<p class="
text-sm
text-gray-500
mt-1
">

Audit Trail monitoring and important system actions

</p>


</div>



<a

href="{{ route('logs.index') }}"

class="
text-sm
font-semibold
text-[#11175A]
hover:underline
"

>

View Logs

</a>


</div>







<div class="
space-y-4
">



<div class="
border
border-gray-200
rounded-xl
p-4
">


<p class="
font-semibold
text-[#11175A]
">

System Initialized

</p>


<p class="
text-sm
text-gray-500
mt-1
">

DySign system activity monitoring is ready.

</p>


</div>







<div class="
border
border-gray-200
rounded-xl
p-4
">


<p class="
font-semibold
text-[#11175A]
">

Personnel Management Updated

</p>


<p class="
text-sm
text-gray-500
mt-1
">

Recent account changes will appear here.

</p>


</div>



</div>




</div>




<!-- RECENT SYSTEM ACTIVITIES -->


<div class="
bg-white
rounded-2xl
shadow-sm
border
border-gray-200
p-6
mb-8
">


<div class="
flex
justify-between
items-center
mb-5
">


<div>


<h2 class="
text-xl
font-semibold
text-[#11175A]
">

Recent System Activities

</h2>


<p class="
text-sm
text-gray-500
mt-1
">

Latest system actions and updates

</p>


</div>




<a

href="{{ route('logs.index') }}"

class="
text-sm
font-semibold
text-[#11175A]
hover:underline
"

>

View Logs

</a>



</div>







<div class="space-y-4">



<div class="
border
border-gray-200
rounded-xl
p-4
">


<div class="flex items-start gap-3">


<div class="
w-3
h-3
rounded-full
bg-[#D4A017]
mt-2
">

</div>



<div>


<h3 class="
font-semibold
text-[#11175A]
">

Administrator Login

</h3>



<p class="
text-sm
text-gray-500
">

System Administrator accessed DySign portal.

</p>



<p class="
text-xs
text-gray-400
mt-1
">

September 25, 2026

</p>


</div>


</div>


</div>









<div class="
border
border-gray-200
rounded-xl
p-4
">


<div class="flex items-start gap-3">


<div class="
w-3
h-3
rounded-full
bg-[#11175A]
mt-2
">

</div>



<div>


<h3 class="
font-semibold
text-[#11175A]
">

Personnel Account Created

</h3>



<p class="
text-sm
text-gray-500
">

New personnel record added to the system.

</p>



<p class="
text-xs
text-gray-400
mt-1
">

September 18, 2026

</p>


</div>


</div>


</div>







</div>



</div>


<!-- QUICK ACTIONS -->


<div class="
bg-white
rounded-2xl
shadow-sm
border
border-gray-200
p-6
mb-8
">


<h2 class="
text-xl
font-semibold
text-[#11175A]
mb-5
">

Quick Actions

</h2>





<div class="
grid
grid-cols-1
md:grid-cols-2
xl:grid-cols-5
gap-4
">





<!-- CREATE EVENT -->


<a

href="{{ route('events.index') }}"

class="
border
border-gray-200
rounded-xl
p-5
hover:bg-gray-50
transition
"

>


<h3 class="
font-semibold
text-[#11175A]
">

Create Event

</h3>


<p class="
text-sm
text-gray-500
mt-2
">

Manage upcoming school activities.

</p>


</a>







<!-- PERSONNEL -->


<a

href="{{ route('personnel.index') }}"

class="
border
border-gray-200
rounded-xl
p-5
hover:bg-gray-50
transition
"

>


<h3 class="
font-semibold
text-[#11175A]
">

Manage Personnel

</h3>


<p class="
text-sm
text-gray-500
mt-2
">

Manage authorized accounts.

</p>


</a>







<!-- STUDENTS -->


<a

href="{{ route('students.index') }}"

class="
border
border-gray-200
rounded-xl
p-5
hover:bg-gray-50
transition
"

>


<h3 class="
font-semibold
text-[#11175A]
">

Student Records

</h3>


<p class="
text-sm
text-gray-500
mt-2
">

View student identity records.

</p>


</a>







<!-- REPORTS -->


<a

href="{{ route('reports.attendance') }}"

class="
border
border-gray-200
rounded-xl
p-5
hover:bg-gray-50
transition
"

>


<h3 class="
font-semibold
text-[#11175A]
">

View Reports

</h3>


<p class="
text-sm
text-gray-500
mt-2
">

Generate system reports.

</p>


</a>







<!-- BACKUP -->


<a

href="{{ route('backup.index') }}"

class="
border
border-gray-200
rounded-xl
p-5
hover:bg-gray-50
transition
"

>


<h3 class="
font-semibold
text-[#11175A]
">

Backup System

</h3>


<p class="
text-sm
text-gray-500
mt-2
">

Protect system records.

</p>


</a>





</div>


</div>

<!-- RECENT PERSONNEL ACCOUNTS -->


<div class="
bg-white
rounded-2xl
shadow-sm
border
border-gray-200
overflow-hidden
">


<div class="p-6 border-b border-gray-200">


<h2 class="
text-xl
font-semibold
text-[#11175A]
">

Recent Personnel Accounts

</h2>


</div>






<div class="overflow-x-auto">


<table class="w-full">


<thead class="bg-gray-50">


<tr class="
text-left
text-sm
text-gray-600
">


<th class="px-6 py-4">

Name

</th>


<th class="px-6 py-4">

Department

</th>


<th class="px-6 py-4">

Position

</th>


<th class="px-6 py-4">

Created

</th>


</tr>


</thead>






<tbody>


@forelse($recentPersonnel as $person)


<tr class="border-t">


<td class="px-6 py-4 text-gray-700">


{{ $person->first_name }}

{{ $person->last_name }}


</td>





<td class="px-6 py-4 text-gray-700">


{{ $person->department }}


</td>





<td class="px-6 py-4 text-gray-700">


{{ $person->position }}


</td>





<td class="px-6 py-4 text-gray-500">


{{ $person->created_at->format('M d, Y') }}


</td>



</tr>




@empty


<tr>


<td colspan="4"

class="
px-6
py-8
text-center
text-gray-400
">

No personnel records available.

</td>


</tr>



@endforelse




</tbody>



</table>



</div>



</div>






</div>


<script>

let currentDate = new Date(2026,8,1);


function changeMonth(direction)
{

    currentDate.setMonth(
        currentDate.getMonth() + direction
    );


    let month =
    currentDate.toLocaleString(
        'default',
        {
            month:'long'
        }
    );


    let year =
    currentDate.getFullYear();



    document.getElementById(
        'calendarTitle'
    ).innerHTML =
    month + " " + year;

}


</script>

</x-admin-layout>