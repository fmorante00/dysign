<x-admin-layout>


<div class="space-y-8">



<!-- HEADER -->

<div>

<h1 class="
text-3xl
font-bold
text-[#101064]
">

Roles & Access

</h1>


<p class="
mt-2
text-gray-500
">

Manage user roles and system access privileges.

</p>


</div>


<!-- ROLES TABLE -->


<div class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
overflow-hidden
">



<div class="p-6 border-b border-gray-100">


<h2 class="
text-xl
font-semibold
text-[#101064]
">

System Roles

</h2>


</div>







<div class="overflow-x-auto">


<table class="w-full text-left">


<thead class="bg-gray-50">


<tr>


<th class="
px-6
py-4
text-xs
uppercase
text-gray-500
">

Role

</th>



<th class="
px-6
py-4
text-xs
uppercase
text-gray-500
">

Description

</th>




<th class="
px-6
py-4
text-xs
uppercase
text-gray-500
">

Users

</th>




<th class="
px-6
py-4
text-xs
uppercase
text-gray-500
">

Status

</th>




<th class="
px-6
py-4
text-xs
uppercase
text-gray-500
">

Action

</th>


</tr>


</thead>







<tbody>



@foreach($roles as $role)


<tr class="border-t">


<td class="
px-6
py-5
font-semibold
text-[#101064]
">

{{ $role->role_name }}

</td>





<td class="
px-6
py-5
text-gray-600
">

{{ $role->description }}

</td>





<td class="
px-6
py-5
text-gray-700
">

{{ $role->users_count }}

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


{{ $role->status }}


</span>


</td>





<td class="px-6 py-5">


<a

href="{{ route('roles.permissions', $role->role_id) }}"

class="
px-4
py-2
rounded-lg
bg-[#101064]
text-white
text-sm
hover:bg-[#D4A017]
transition
inline-block
"

>

Manage Permissions

</a>


</td>


</tr>



@endforeach



</tbody>



</table>


</div>



</div>





</div>


</x-admin-layout>