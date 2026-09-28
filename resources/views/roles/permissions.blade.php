<x-admin-layout>


<div 
class="space-y-8"
x-data="{
    selectAll:false
}"
>

@if(session('success'))

<div class="
bg-green-100
border
border-green-300
text-green-700
px-5
py-3
rounded-xl
">

{{ session('success') }}

</div>

@endif



<!-- HEADER -->

<div>


<h1 class="
text-3xl
font-bold
text-[#101064]
">

Manage Permissions

</h1>


<p class="
mt-2
text-gray-500
">

Assign system access privileges for {{ $role->role_name }}.

</p>


</div>







<!-- ROLE INFORMATION -->


<div class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-6
">


<h2 class="
text-xl
font-semibold
text-[#101064]
">

{{ $role->role_name }}

</h2>



<p class="
text-gray-500
mt-2
">

{{ $role->description }}

</p>


</div>








<!-- PERMISSIONS -->


<div class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-6
">


<form method="POST"

action="{{ route('roles.permissions.update', $role->role_id) }}"

>


@csrf

@method('PUT')




<h2 class="
text-xl
font-semibold
text-[#101064]
mb-6
">

Available Permissions

</h2>

<label class="
flex
items-center
gap-3
mb-5
cursor-pointer
">


<input

type="checkbox"

x-model="selectAll"

@change="
document.querySelectorAll('.permission-checkbox')
.forEach(cb => cb.checked = selectAll)
"

class="w-5 h-5"

>


<span class="
font-semibold
text-gray-700
">

Select All Permissions

</span>


</label>



<div class="space-y-4">



@foreach($permissions as $permission)


<label class="
flex
items-center
gap-3
border
border-gray-200
rounded-xl
p-4
cursor-pointer
">


<input

type="checkbox"

name="permissions[]"

value="{{ $permission->permission_id }}"

class="w-5 h-5 permission-checkbox"


@if($role->permissions->contains('permission_id', $permission->permission_id))

checked

@endif

>




<div>


<p class="
font-semibold
text-gray-700
">

{{ ucwords(str_replace('_', ' ', $permission->permission_name)) }}

</p>


<p class="
text-sm
text-gray-500
">

{{ $permission->description }}

</p>


</div>


</label>



@endforeach



</div>







<div class="
flex
justify-between
mt-8
">



<a

href="{{ route('roles.index') }}"

class="
px-5
py-2
rounded-lg
border
border-gray-300
text-gray-700
hover:bg-gray-100
"

>

← Back

</a>





<div class="flex gap-3">



<a

href="{{ route('roles.permissions',$role->role_id) }}"

class="
px-5
py-2
rounded-lg
border
border-gray-300
text-gray-700
hover:bg-gray-100
"

>

Cancel

</a>




<button

type="submit"

class="
px-5
py-2
rounded-lg
bg-[#101064]
text-white
hover:bg-[#D4A017]
"

>

Save Permissions

</button>



</div>



</div>



</form>



</div>

</div>


</x-admin-layout>