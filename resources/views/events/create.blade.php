<x-admin-layout>

<div class="space-y-8 pb-10 overflow-y-auto max-h-[calc(100vh-100px)]">


<!-- HEADER -->

<div class="flex justify-between items-center">

<div>

<h1 class="
text-3xl
font-bold
text-[#101064]
">
Create Event
</h1>

<p class="
mt-2
text-gray-500
">
Register an approved event and configure attendance rules, eligibility, and personnel assignment.
</p>

</div>


<a

href="{{ route('events.index') }}"

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









<!-- PROCESS GUIDE -->

<div class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-5
">


<p class="
font-semibold
text-[#101064]
mb-4
">

Event Creation Process

</p>


<div class="
grid
grid-cols-1
md:grid-cols-7
gap-3
">


<div class="
bg-[#101064]
text-white
rounded-xl
p-4
">

<p class="text-xs">
STEP 1
</p>

<p class="font-semibold">
Event Info
</p>

</div>



<div class="
bg-gray-50
rounded-xl
p-4
">

<p class="text-xs text-gray-400">
STEP 2
</p>

<p class="font-semibold text-gray-600">
Schedule
</p>

</div>




<div class="
bg-gray-50
rounded-xl
p-4
">

<p class="text-xs text-gray-400">
STEP 3
</p>

<p class="font-semibold text-gray-600">
Attendance Rules
</p>

</div>





<div class="
bg-gray-50
rounded-xl
p-4
">

<p class="text-xs text-gray-400">
STEP 4
</p>

<p class="font-semibold text-gray-600">
Eligibility
</p>

</div>





<div class="
bg-gray-50
rounded-xl
p-4
">

<p class="text-xs text-gray-400">
STEP 5
</p>

<p class="font-semibold text-gray-600">
Roster
</p>

</div>





<div class="
bg-gray-50
rounded-xl
p-4
">

<p class="text-xs text-gray-400">
STEP 6
</p>

<p class="font-semibold text-gray-600">
Personnel
</p>

</div>





<div class="
bg-gray-50
rounded-xl
p-4
">

<p class="text-xs text-gray-400">
STEP 7
</p>

<p class="font-semibold text-gray-600">
Review
</p>

</div>



</div>


</div>









<!-- MAIN FORM -->


<div class="
bg-white
rounded-3xl
border
border-gray-100
shadow-sm
p-8
">







<!-- EVENT INFORMATION -->


<div>


<h2 class="
text-xl
font-semibold
text-[#101064]
">

① Event Information

</h2>


<p class="
text-sm
text-gray-500
mt-1
mb-6
">

Provide the basic details of the approved event.

</p>





<div class="
grid
grid-cols-1
md:grid-cols-2
gap-6
">





<div>


<label class="
block
text-sm
font-medium
text-gray-700
mb-2
">

Event Name *

</label>


<input

type="text"

placeholder="Example: Leadership Seminar"

class="
w-full
rounded-xl
border
border-gray-300
px-4
py-3
focus:outline-none
focus:ring-2
focus:ring-[#D4A017]
"

>


</div>







<div>


<label class="
block
text-sm
font-medium
text-gray-700
mb-2
">

Event Type

</label>


<select

class="
w-full
rounded-xl
border
border-gray-300
px-4
py-3
"

>

<option>
Seminar
</option>

<option>
Workshop
</option>

<option>
Assembly
</option>

<option>
Training
</option>


</select>


</div>







<div>


<label class="
block
text-sm
font-medium
text-gray-700
mb-2
">

Department / Organizer

</label>


<input

type="text"

placeholder="Example: College of Business"

class="
w-full
rounded-xl
border
border-gray-300
px-4
py-3
"

>


</div>







<div>


<label class="
block
text-sm
font-medium
text-gray-700
mb-2
">

Event Status

</label>


<select

class="
w-full
rounded-xl
border
border-gray-300
px-4
py-3
"

>

<option>
Upcoming
</option>

<option>
Active
</option>


</select>


</div>




</div>


</div>









<hr class="my-10">







<!-- SCHEDULE DETAILS -->


<div>


<h2 class="
text-xl
font-semibold
text-[#101064]
">

② Schedule Details

</h2>


<p class="
text-sm
text-gray-500
mt-1
mb-6
">

Set the date and duration of the event.

</p>



<div class="
grid
grid-cols-1
md:grid-cols-3
gap-6
">



<div>

<label class="
block
text-sm
font-medium
text-gray-700
mb-2
">

Event Date

</label>

<input

type="date"

class="
w-full
rounded-xl
border
border-gray-300
px-4
py-3
"

>

</div>





<div>

<label class="
block
text-sm
font-medium
text-gray-700
mb-2
">

Start Time

</label>

<input

type="time"

class="
w-full
rounded-xl
border
border-gray-300
px-4
py-3
"

>

</div>





<div>

<label class="
block
text-sm
font-medium
text-gray-700
mb-2
">

End Time

</label>

<input

type="time"

class="
w-full
rounded-xl
border
border-gray-300
px-4
py-3
"

>

</div>


</div>




<div class="mt-6">

<label class="
block
text-sm
font-medium
text-gray-700
mb-2
">

Event Description

</label>


<textarea

rows="4"

placeholder="Describe the purpose of this event..."

class="
w-full
rounded-xl
border
border-gray-300
px-4
py-3
"

></textarea>


</div>



</div>

<!-- ATTENDANCE POLICY & EXIT RULES -->

<hr class="my-10">





<div>


<h2 class="
text-xl
font-semibold
text-[#101064]
">

③ Attendance Policy & Exit Rules

</h2>


<p class="
text-sm
text-gray-500
mt-1
mb-6
">

Configure the attendance rules that DySign will apply during the event.

</p>







<!-- TIME SETTINGS -->


<div class="
grid
grid-cols-1
md:grid-cols-2
gap-6
">






<div>


<label class="
block
text-sm
font-medium
text-gray-700
mb-2
">

Time-In Start

</label>


<input

type="time"

class="
w-full
rounded-xl
border
border-gray-300
px-4
py-3
"

>


<p class="
text-xs
text-gray-400
mt-2
">

When students are allowed to start checking in.

</p>


</div>







<div>


<label class="
block
text-sm
font-medium
text-gray-700
mb-2
">

On-Time Attendance Deadline

</label>


<input

type="time"

class="
w-full
rounded-xl
border
border-gray-300
px-4
py-3
"

>


<p class="
text-xs
text-gray-400
mt-2
">

Students checking in after this time will be marked late.

</p>


</div>









<div>


<label class="
block
text-sm
font-medium
text-gray-700
mb-2
">

Late Attendance Starts

</label>


<input

type="time"

class="
w-full
rounded-xl
border
border-gray-300
px-4
py-3
"

>


<p class="
text-xs
text-gray-400
mt-2
">

Defines when attendance status changes to Late.

</p>


</div>







<div>


<label class="
block
text-sm
font-medium
text-gray-700
mb-2
">

Official Time-Out

</label>


<input

type="time"

class="
w-full
rounded-xl
border
border-gray-300
px-4
py-3
"

>


<p class="
text-xs
text-gray-400
mt-2
">

Required time for completing attendance.

</p>


</div>







</div>









<!-- EXIT RULES -->


<div class="
mt-8
bg-gray-50
rounded-2xl
p-6
border
border-gray-100
">


<h3 class="
font-semibold
text-[#101064]
mb-5
">

Temporary Exit Rules

</h3>






<div class="
grid
grid-cols-1
md:grid-cols-2
gap-6
">






<div>


<label class="
block
text-sm
font-medium
text-gray-700
mb-2
">

Allow Temporary Exit?

</label>


<select

class="
w-full
rounded-xl
border
border-gray-300
px-4
py-3
"

>


<option>
Allowed
</option>


<option>
Not Allowed
</option>


</select>


</div>









<div>


<label class="
block
text-sm
font-medium
text-gray-700
mb-2
">

Maximum Exit Duration

</label>


<div class="
flex
gap-3
">


<input

type="number"

placeholder="30"

class="
w-full
rounded-xl
border
border-gray-300
px-4
py-3
"

>


<span

class="
flex
items-center
text-gray-500
"

>

minutes

</span>


</div>


</div>







</div>







<div class="mt-6">


<label class="
block
text-sm
font-medium
text-gray-700
mb-2
">

Prolonged Exit Action

</label>


<select

class="
w-full
rounded-xl
border
border-gray-300
px-4
py-3
"

>


<option>
Mark for Review

</option>


<option>
Mark Attendance Incomplete

</option>


<option>
Notify Assigned Personnel

</option>


</select>


</div>






</div>









<!-- COMPLETION REQUIREMENTS -->


<div class="
mt-8
border
border-gray-100
rounded-2xl
p-6
">


<h3 class="
font-semibold
text-[#101064]
mb-5
">

Attendance Completion Requirements

</h3>






<div class="
space-y-4
">





<label class="
flex
items-center
gap-3
"

>

<input

type="checkbox"

checked

class="
w-5
h-5
"

>


<span class="
text-gray-700
">

Time-In must be recorded

</span>


</label>







<label class="
flex
items-center
gap-3
"

>

<input

type="checkbox"

checked

class="
w-5
h-5
"

>


<span class="
text-gray-700
">

Time-Out must be recorded

</span>


</label>







<label class="
flex
items-center
gap-3
"

>

<input

type="checkbox"

checked

class="
w-5
h-5
"

>


<span class="
text-gray-700
">

No excessive exit duration

</span>


</label>






</div>



</div>






</div>


<!-- STUDENT ELIGIBILITY RULES -->

<hr class="my-10">





<div>


<h2 class="
text-xl
font-semibold
text-[#101064]
">

④ Student Eligibility Rules

</h2>


<p class="
text-sm
text-gray-500
mt-1
mb-6
">

Define the conditions for determining which students are eligible to participate.

</p>








<div class="
border
border-gray-100
rounded-2xl
p-6
">






<label class="
flex
items-center
gap-3
mb-6
">

<input

type="radio"

name="eligibility"

checked

class="
w-5
h-5
"

>


<span class="
font-medium
text-gray-700
">

Open for All Students

</span>


</label>







<label class="
flex
items-center
gap-3
mb-6
">

<input

type="radio"

name="eligibility"

class="
w-5
h-5
"

>


<span class="
font-medium
text-gray-700
">

Apply Specific Eligibility Conditions

</span>


</label>








<div class="
grid
grid-cols-1
md:grid-cols-3
gap-6
mt-6
">





<div>


<label class="
block
text-sm
font-medium
text-gray-700
mb-2
">

Program / Course

</label>


<select

class="
w-full
rounded-xl
border
border-gray-300
px-4
py-3
"

>


<option>
All Programs
</option>


<option>
BSIT
</option>


<option>
BSACC
</option>


<option>
BSBA
</option>


</select>


</div>







<div>


<label class="
block
text-sm
font-medium
text-gray-700
mb-2
">

Year Level

</label>


<select

class="
w-full
rounded-xl
border
border-gray-300
px-4
py-3
"

>


<option>
All Year Levels
</option>


<option>
First Year
</option>


<option>
Second Year
</option>


<option>
Third Year
</option>


<option>
Fourth Year
</option>


</select>


</div>







<div>


<label class="
block
text-sm
font-medium
text-gray-700
mb-2
">

Section

</label>


<select

class="
w-full
rounded-xl
border
border-gray-300
px-4
py-3
"

>


<option>
All Sections
</option>


<option>
1-1
</option>


<option>
1-2
</option>


<option>
2-1
</option>


</select>


</div>






</div>







<div class="mt-6">


<label class="
block
text-sm
font-medium
text-gray-700
mb-2
">

Specific Students (Optional)

</label>



<input

type="text"

placeholder="Search student name or ID"

class="
w-full
rounded-xl
border
border-gray-300
px-4
py-3
"

>


<p class="
text-xs
text-gray-400
mt-2
">

Use this when selecting individual students.

</p>


</div>






</div>


</div>









<!-- PARTICIPANT ROSTER -->

<hr class="my-10">





<div>


<h2 class="
text-xl
font-semibold
text-[#101064]
">

⑤ Participant Roster

</h2>


<p class="
text-sm
text-gray-500
mt-1
mb-6
">

Maintain the expected participants generated from student eligibility rules.

</p>







<div class="
bg-gray-50
rounded-2xl
border
border-gray-100
p-6
">






<div class="
flex
justify-between
items-center
mb-6
">



<div>


<p class="
text-sm
text-gray-500
">

Expected Participants

</p>


<h3 class="
text-3xl
font-bold
text-[#D4A017]
">

245

</h3>


</div>





<button

type="button"

class="
px-5
py-3
rounded-xl
bg-[#101064]
text-white
text-sm
font-semibold
hover:bg-[#D4A017]
transition
"

>

Refresh Roster

</button>



</div>









<div class="
overflow-x-auto
">


<table class="
w-full
text-left
bg-white
rounded-xl
overflow-hidden
">


<thead class="bg-gray-100">


<tr>


<th class="
px-5
py-3
text-xs
uppercase
text-gray-500
">

Student ID

</th>


<th class="
px-5
py-3
text-xs
uppercase
text-gray-500
">

Name

</th>


<th class="
px-5
py-3
text-xs
uppercase
text-gray-500
">

Program

</th>


<th class="
px-5
py-3
text-xs
uppercase
text-gray-500
">

Status

</th>


</tr>


</thead>







<tbody>


<tr class="border-t">


<td class="px-5 py-4">
2026-001
</td>


<td class="px-5 py-4">
Juan Dela Cruz
</td>


<td class="px-5 py-4">
BSIT
</td>


<td class="px-5 py-4">

<span class="
px-3
py-1
rounded-full
bg-green-100
text-green-700
text-xs
font-semibold
">

Eligible

</span>


</td>


</tr>





<tr class="border-t">


<td class="px-5 py-4">
2026-002
</td>


<td class="px-5 py-4">
Maria Santos
</td>


<td class="px-5 py-4">
BSACC
</td>


<td class="px-5 py-4">

<span class="
px-3
py-1
rounded-full
bg-green-100
text-green-700
text-xs
font-semibold
">

Eligible

</span>


</td>


</tr>



</tbody>


</table>


</div>




</div>


</div>









<!-- ASSIGN PERSONNEL -->


<hr class="my-10">





<div>


<h2 class="
text-xl
font-semibold
text-[#101064]
">

⑥ Assign Event Personnel

</h2>


<p class="
text-sm
text-gray-500
mt-1
mb-6
">

Choose personnel responsible for attendance operations.

</p>






<div class="
grid
grid-cols-1
md:grid-cols-2
gap-5
">





<label class="
flex
items-center
gap-4
border
border-gray-300
rounded-2xl
p-5
cursor-pointer
hover:border-[#D4A017]
transition
">


<input

type="checkbox"

class="w-5 h-5"

>


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







<label class="
flex
items-center
gap-4
border
border-gray-300
rounded-2xl
p-5
cursor-pointer
hover:border-[#D4A017]
transition
">


<input

type="checkbox"

class="w-5 h-5"

>


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

Event Staff

</p>


</div>


</label>





</div>


</div>








<hr class="my-10">





<!-- REVIEW -->


<div class="
bg-gray-50
rounded-2xl
p-6
border
border-gray-100
">


<h3 class="
font-semibold
text-[#101064]
">

⑦ Review Before Creating

</h3>


<p class="
text-sm
text-gray-500
mt-2
">

Review event information, attendance policies, eligibility rules, participant roster, and assigned personnel.

</p>


</div>









<!-- BUTTONS -->


<div class="
flex
justify-end
gap-3
mt-8
">


<a

href="{{ route('events.index') }}"

class="
px-6
py-3
rounded-xl
border
border-gray-300
text-gray-600
hover:bg-gray-50
"

>

Cancel

</a>



<button

type="button"

onclick="openCreateEventModal()"

class="
px-8
py-3
rounded-xl
bg-[#101064]
text-white
font-semibold
hover:bg-[#D4A017]
"

>

Create Event

</button>


</div>



</div>







<!-- MODAL -->


<div

id="createEventModal"

class="
fixed
inset-0
bg-black/40
hidden
items-center
justify-center
z-50
"

>


<div class="
bg-white
rounded-3xl
max-w-md
w-full
p-8
">


<h2 class="
text-xl
font-bold
text-[#101064]
text-center
">

Create Event?

</h2>



<p class="
text-center
text-gray-500
mt-3
">

Confirm all event details before saving.

</p>




<div class="
flex
justify-center
gap-3
mt-8
">


<button

onclick="closeCreateEventModal()"

class="
px-6
py-3
border
rounded-xl
"

>

Cancel

</button>



<button

class="
px-6
py-3
rounded-xl
bg-[#101064]
text-white
"

>

Confirm

</button>


</div>



</div>


</div>







<script>


function openCreateEventModal(){

document
.getElementById('createEventModal')
.classList
.remove('hidden');


document
.getElementById('createEventModal')
.classList
.add('flex');

}



function closeCreateEventModal(){

document
.getElementById('createEventModal')
.classList
.add('hidden');


document
.getElementById('createEventModal')
.classList
.remove('flex');

}


</script>



</div>


</x-admin-layout>