<x-admin-layout>

<div
class="space-y-8"

x-data="{

    openMenu:null,

    showModal:false,

    formId:'',

    action:'',

    personnelName:'',


    openDropdown(id){

        this.openMenu = this.openMenu === id ? null : id;

    },


    openConfirm(form, action, name){

        this.formId = form;

        this.action = action;

        this.personnelName = name;

        this.showModal = true;

        this.openMenu = null;

    },


    submitForm(){

        document.getElementById(this.formId).submit();

    }

}"

@click.outside="openMenu=null"

>





<!-- HEADER -->


<div>

<h1 class="
text-3xl
font-bold
text-[#101064]
">

Personnel Management

</h1>


<p class="
mt-2
text-gray-500
">

Manage authorized personnel accounts and system access.

</p>


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

Total Personnel

</p>


<h2 class="
mt-2
text-3xl
font-bold
text-[#101064]
">

{{ $personnel->count() }}

</h2>


<p class="text-xs text-gray-400 mt-1">

Registered accounts

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

Active Accounts

</p>


<h2 class="
mt-2
text-3xl
font-bold
text-green-600
">

{{ $personnel->where('user.status','Active')->count() }}

</h2>


<p class="text-xs text-gray-400 mt-1">

Currently active users

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

Access Management

</p>


<h2 class="
mt-2
text-3xl
font-bold
text-[#D4A017]
">

Enabled

</h2>


<p class="text-xs text-gray-400 mt-1">

Personnel control module

</p>


</div>



</div>









<!-- TABLE CARD -->


<div class="
bg-white
rounded-3xl
border
border-gray-100
shadow-sm
overflow-visible
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

Personnel Directory

</h2>


<p class="
text-sm
text-gray-500
mt-1
">

View and manage authorized system personnel.

</p>


</div>






<div class="flex gap-3">


<input

type="text"

placeholder="Search personnel..."

class="
border
border-gray-200
rounded-xl
px-4
py-2
text-sm
outline-none
focus:ring-2
focus:ring-[#101064]/20
"


>




<a

href="{{ route('personnel.create') }}"

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

+ Add Personnel

</a>


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

Personnel

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

Position

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

Action

</th>


</tr>


</thead>






<tbody>


@foreach($personnel as $person)


<tr class="
border-t
hover:bg-gray-50
transition
">


<td class="
px-6
py-6
">


<div class="
flex
items-center
gap-4
">


<div class="
w-11
h-11
rounded-full
bg-[#101064]
text-white
flex
items-center
justify-center
font-bold
">


{{ strtoupper(substr($person->first_name,0,1)) }}


</div>



<div>


<p class="
font-semibold
text-[#101064]
">

{{ $person->first_name }}

{{ $person->last_name }}

</p>


<p class="
text-sm
text-gray-500
">

{{ $person->user->email }}

</p>


</div>


</div>


</td>

<!-- DEPARTMENT -->

<td class="
px-6
py-6
text-gray-600
">

{{ $person->department }}

</td>





<!-- POSITION -->

<td class="
px-6
py-6
text-gray-600
">

{{ $person->position }}

</td>






<!-- STATUS -->

<td class="
px-6
py-6
">


@if($person->user->status == 'Active')


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







<!-- ACTION -->

<td class="
px-6
py-6
relative
">


<div class="relative">


<button

@click="openDropdown({{ $person->personnel_id }})"

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








<!-- DROPDOWN -->


<div

x-show="openMenu === {{ $person->personnel_id }}"

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

href="{{ route('personnel.show',$person->personnel_id) }}"

class="
block
px-5
py-3
text-sm
text-gray-700
hover:bg-gray-50
transition
"

>

View Details

</a>







<a

href="{{ route('personnel.edit',$person->personnel_id) }}"

class="
block
px-5
py-3
text-sm
text-gray-700
hover:bg-gray-50
transition
"

>

Edit Personnel

</a>







@if($person->user->status == 'Active')


<form

id="deactivate-{{ $person->personnel_id }}"

method="POST"

action="{{ route('personnel.destroy',$person->personnel_id) }}"

>


@csrf

@method('DELETE')




<button

type="button"

@click="
openConfirm(
'deactivate-{{ $person->personnel_id }}',
'deactivate',
'{{ $person->first_name }} {{ $person->last_name }}'
)
"

class="
w-full
text-left
px-5
py-3
text-sm
text-[#8A6D00]
hover:bg-[#F8F5E8]
transition
"

>

Deactivate Account

</button>


</form>




@else




<form

id="activate-{{ $person->personnel_id }}"

method="POST"

action="{{ route('personnel.activate',$person->personnel_id) }}"

>


@csrf

@method('PATCH')




<button

type="button"

@click="
openConfirm(
'activate-{{ $person->personnel_id }}',
'activate',
'{{ $person->first_name }} {{ $person->last_name }}'
)
"

class="
w-full
text-left
px-5
py-3
text-sm
text-[#8A6D00]
hover:bg-[#F8F5E8]
transition
"

>

Activate Account

</button>


</form>



@endif



</div>


</div>


</td>





</tr>


@endforeach


</tbody>


</table>


</div>


</div>










<!-- CONFIRMATION MODAL -->


<div

x-show="showModal"

x-transition

class="
fixed
inset-0
z-[9999]
flex
items-center
justify-center
bg-black/40
"

style="display:none;"

>


<div

@click.outside="showModal=false"

class="
bg-white
rounded-3xl
shadow-xl
max-w-md
w-full
p-8
mx-4
"

>




<div class="text-center">


<div class="
w-14
h-14
mx-auto
rounded-full
bg-[#F8F5E8]
flex
items-center
justify-center
mb-5
">


<span class="
text-2xl
font-bold
text-[#D4A017]
">

!

</span>


</div>







<h2 class="
text-xl
font-bold
text-[#101064]
">

Confirm Account Action

</h2>







<p class="
mt-4
text-gray-500
">

Are you sure you want to

<span

class="
font-semibold
text-[#101064]
"

x-text="action"

>

</span>


this account?


<br>


<span

class="
font-semibold
text-[#101064]
"

x-text="personnelName"

>

</span>


</p>







<div class="
mt-8
flex
justify-center
gap-3
">


<button

@click="showModal=false"

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

Cancel

</button>







<button

@click="submitForm()"

class="
px-5
py-3
rounded-xl
bg-[#101064]
text-white
font-semibold
hover:bg-[#D4A017]
transition
"

>

Confirm

</button>



</div>




</div>


</div>


</div>






</div>


</x-admin-layout>