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
Create Event
</h1>

<p class="
mt-2
text-gray-500
">
Register an approved event and assign personnel for attendance operations.
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
md:grid-cols-4
gap-4
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
Event Information
</p>

</div>



<div class="
bg-gray-50
rounded-xl
p-4
">

<p class="
text-xs
text-gray-400
">
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

<p class="
text-xs
text-gray-400
">
STEP 3
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

<p class="
text-xs
text-gray-400
">
STEP 4
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
bg-white
px-4
py-3
shadow-sm
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
bg-white
px-4
py-3
shadow-sm
focus:outline-none
focus:ring-2
focus:ring-[#D4A017]
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
bg-white
px-4
py-3
shadow-sm
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

Event Status

</label>


<select

class="
w-full
rounded-xl
border
border-gray-300
bg-white
px-4
py-3
shadow-sm
focus:outline-none
focus:ring-2
focus:ring-[#D4A017]
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







<!-- SCHEDULE -->


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
bg-white
px-4
py-3
shadow-sm
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

Start Time

</label>


<input

type="time"

class="
w-full
rounded-xl
border
border-gray-300
bg-white
px-4
py-3
shadow-sm
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

End Time

</label>


<input

type="time"

class="
w-full
rounded-xl
border
border-gray-300
bg-white
px-4
py-3
shadow-sm
focus:outline-none
focus:ring-2
focus:ring-[#D4A017]
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
bg-white
px-4
py-3
shadow-sm
focus:outline-none
focus:ring-2
focus:ring-[#D4A017]
"

></textarea>


</div>



</div>









<hr class="my-10">









<!-- ASSIGN PERSONNEL -->


<div>


<h2 class="
text-xl
font-semibold
text-[#101064]
">

③ Assign Event Personnel

</h2>


<p class="
text-sm
text-gray-500
mt-1
mb-6
">

Choose personnel responsible for handling the event.

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
hover:bg-gray-50
transition
">


<input

type="checkbox"

class="
w-5
h-5
"

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
hover:bg-gray-50
transition
">


<input

type="checkbox"

class="
w-5
h-5
"

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

④ Review Before Creating

</h3>


<p class="
text-sm
text-gray-500
mt-2
">

Make sure all event information and assigned personnel are correct before saving.

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
transition
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
transition
"

>

Create Event

</button>


</div>


</div>


</div>

<!-- CREATE EVENT MODAL -->

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
w-full
max-w-md
p-8
shadow-xl
">


<div class="
text-center
">


<div class="
mx-auto
w-14
h-14
rounded-full
bg-[#F8F5E8]
flex
items-center
justify-center
text-[#D4A017]
text-2xl
mb-4
">

!

</div>




<h2 class="
text-xl
font-bold
text-[#101064]
">

Create Event?

</h2>



<p class="
text-gray-500
mt-3
text-sm
">

You are about to add this event to the DySign system. Please confirm that all information is correct.

</p>



</div>





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
rounded-xl
border
border-gray-300
text-gray-600
hover:bg-gray-50
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
font-semibold
hover:bg-[#D4A017]
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

</x-admin-layout>