<x-admin-layout>


<div class="space-y-8">


<!-- HEADER -->

<div>

<h1 class="
text-3xl
font-bold
text-[#101064]
">

Participation Evaluation Management

</h1>


<p class="
mt-2
text-gray-500
">

Evaluate and classify student participation using approved participation criteria and decision rules.

</p>


</div>









<!-- SUMMARY CARDS -->

<div class="
grid
grid-cols-1
md:grid-cols-4
gap-6
">



<div class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-6
">


<p class="
text-sm
text-gray-500
">

Students Evaluated

</p>


<h2 class="
text-3xl
font-bold
text-[#D4A017]
mt-2
">

1,542

</h2>


</div>







<div class="
bg-green-50
rounded-2xl
border
border-green-100
p-6
">


<p class="
text-sm
text-green-700
">

Highly Participative

</p>


<h2 class="
text-3xl
font-bold
text-green-700
mt-2
">

430

</h2>


</div>







<div class="
bg-blue-50
rounded-2xl
border
border-blue-100
p-6
">


<p class="
text-sm
text-blue-700
">

Participative

</p>


<h2 class="
text-3xl
font-bold
text-blue-700
mt-2
">

700

</h2>


</div>







<div class="
bg-red-50
rounded-2xl
border
border-red-100
p-6
">


<p class="
text-sm
text-red-700
">

Low Participation

</p>


<h2 class="
text-3xl
font-bold
text-red-700
mt-2
">

120

</h2>


</div>



</div>









<!-- EVALUATION TABLE -->


<div class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
overflow-hidden
">


<div class="
p-6
border-b
border-gray-100
">


<h2 class="
text-xl
font-semibold
text-[#101064]
">

Student Participation Evaluation

</h2>


</div>







<div class="overflow-x-auto">


<table class="
w-full
text-left
">


<thead class="bg-gray-50">


<tr>


<th class="
px-6
py-4
text-xs
uppercase
text-gray-500
">

Student

</th>


<th class="
px-6
py-4
text-xs
uppercase
text-gray-500
">

Attendance Rate

</th>


<th class="
px-6
py-4
text-xs
uppercase
text-gray-500
">

Participation Rate

</th>


<th class="
px-6
py-4
text-xs
uppercase
text-gray-500
">

Compliance

</th>


<th class="
px-6
py-4
text-xs
uppercase
text-gray-500
">

Evaluation Result

</th>


</tr>


</thead>







<tbody>


<tr class="border-t">


<td class="
px-6
py-5
font-semibold
text-[#101064]
">

Juan Dela Cruz

</td>


<td class="px-6 py-5">

95%

</td>


<td class="px-6 py-5">

92%

</td>


<td class="px-6 py-5">

Completed

</td>


<td class="px-6 py-5">


<span class="
px-3
py-1
rounded-full
bg-green-100
text-green-700
text-xs
font-semibold
">

Highly Participative

</span>


</td>


</tr>









<tr class="border-t">


<td class="
px-6
py-5
font-semibold
text-[#101064]
">

Maria Santos

</td>


<td class="px-6 py-5">

80%

</td>


<td class="px-6 py-5">

75%

</td>


<td class="px-6 py-5">

Incomplete

</td>


<td class="px-6 py-5">


<span class="
px-3
py-1
rounded-full
bg-yellow-100
text-yellow-700
text-xs
font-semibold
">

Moderately Participative

</span>


</td>


</tr>



</tbody>


</table>


</div>


</div>















<!-- ACTION -->

<div class="
flex
justify-end
">


<button

onclick="openEvaluationModal()"

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

Re-Evaluate Students

</button>


</div>







</div>


<!-- RE-EVALUATION MODAL -->

<div

id="evaluationModal"

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


<div

class="
bg-white
rounded-3xl
w-full
max-w-md
p-8
shadow-xl
"

>


<div class="text-center">


<h2 class="
text-xl
font-bold
text-[#101064]
">

Re-Evaluate Students?

</h2>



<p class="
mt-3
text-sm
text-gray-500
leading-relaxed
">

This will recalculate student participation classifications using the latest participation records, indicators, compliance data, and approved evaluation criteria.

</p>


</div>







<div class="
mt-8
flex
justify-center
gap-3
">


<button

onclick="closeEvaluationModal()"

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
transition
"

>

Confirm Evaluation

</button>



</div>



</div>


</div>







<script>

function openEvaluationModal(){

document
.getElementById('evaluationModal')
.classList
.remove('hidden');


document
.getElementById('evaluationModal')
.classList
.add('flex');

}




function closeEvaluationModal(){

document
.getElementById('evaluationModal')
.classList
.add('hidden');


document
.getElementById('evaluationModal')
.classList
.remove('flex');

}

</script>

</x-admin-layout>