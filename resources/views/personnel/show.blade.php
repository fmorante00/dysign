<x-admin-layout>

<div class="space-y-8">


<!-- PAGE HEADER -->

<div class="flex justify-between items-center">

<div>

<h1 class="
text-3xl
font-bold
text-[#101064]
">
Personnel Profile
</h1>


<p class="
mt-2
text-gray-500
">
View personnel information and account details.
</p>


</div>



<a

href="{{ route('personnel.index') }}"

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






<!-- PROFILE HEADER -->


<div class="
bg-white
rounded-3xl
border
border-gray-100
shadow-sm
p-8
">


<div class="
flex
items-center
gap-6
">



<div class="
w-20
h-20
rounded-full
bg-[#101064]
text-white
flex
items-center
justify-center
text-3xl
font-bold
">


{{ strtoupper(substr($personnel->first_name,0,1)) }}


</div>





<div class="flex-1">


<h2 class="
text-2xl
font-bold
text-[#101064]
">

{{ $personnel->first_name }}
{{ $personnel->last_name }}

</h2>


<p class="
text-gray-500
mt-1
">

{{ $personnel->position }}

</p>


<p class="
text-sm
text-gray-400
">

{{ $personnel->department }}

</p>


</div>




@if($personnel->user->status == 'Active')


<span class="
bg-green-50
text-green-700
px-4
py-2
rounded-full
text-sm
font-semibold
">

Active Account

</span>



@else


<span class="
bg-gray-100
text-gray-600
px-4
py-2
rounded-full
text-sm
font-semibold
">

Inactive Account

</span>



@endif



</div>


</div>










<!-- INFORMATION -->

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

Personnel Information

</h2>



<div class="
grid
grid-cols-1
md:grid-cols-2
gap-x-16
gap-y-6
">





<div>

<p class="text-sm text-gray-400">
First Name
</p>

<p class="font-semibold text-gray-800">
{{ $personnel->first_name }}
</p>

</div>





<div>

<p class="text-sm text-gray-400">
Last Name
</p>

<p class="font-semibold text-gray-800">
{{ $personnel->last_name }}
</p>

</div>






<div>

<p class="text-sm text-gray-400">
Department
</p>

<p class="font-semibold text-gray-800">
{{ $personnel->department }}
</p>

</div>






<div>

<p class="text-sm text-gray-400">
Position
</p>

<p class="font-semibold text-gray-800">
{{ $personnel->position }}
</p>

</div>





<div>

<p class="text-sm text-gray-400">
Username
</p>

<p class="font-semibold text-gray-800">
{{ $personnel->user->username }}
</p>

</div>






<div>

<p class="text-sm text-gray-400">
Email Address
</p>

<p class="font-semibold text-gray-800">
{{ $personnel->user->email }}
</p>

</div>







<div>

<p class="text-sm text-gray-400">
Assigned Role
</p>

<p class="font-semibold text-gray-800">

{{ $personnel->user->role->role_name ?? 'N/A' }}

</p>

</div>






<div>

<p class="text-sm text-gray-400">
Account Status
</p>

<p class="font-semibold text-gray-800">

{{ $personnel->user->status }}

</p>

</div>





</div>



</div>









<!-- ACTIONS -->

<div class="
flex
justify-end
gap-3
">


<a

href="{{ route('personnel.edit',$personnel->personnel_id) }}"

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

Edit Personnel

</a>



</div>





</div>


</x-admin-layout>