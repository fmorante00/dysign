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
View personnel information and account lifecycle details.
</p>


</div>



<a href="{{ route('personnel.index') }}"
class="
px-5
py-3
rounded-xl
border
border-gray-300
text-gray-600
hover:bg-gray-50
transition
">

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




<!-- NEW STATUS LOGIC -->


@if($personnel->user->status == 'Inactive')


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


@elseif($personnel->user->must_change_password)


<span class="
bg-yellow-50
text-yellow-700
px-4
py-2
rounded-full
text-sm
font-semibold
">

Pending Setup

</span>


@else


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


@endif



</div>


</div>

<!-- INFORMATION -->

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">

<h2 class="text-xl font-semibold text-[#101064] mb-6">
Personnel Information
</h2>


<div class="grid grid-cols-1 md:grid-cols-2 gap-6">


<div>
<p class="text-sm text-gray-400">First Name</p>
<p class="font-semibold text-gray-800">{{ $personnel->first_name }}</p>
</div>


<div>
<p class="text-sm text-gray-400">Last Name</p>
<p class="font-semibold text-gray-800">{{ $personnel->last_name }}</p>
</div>


<div>
<p class="text-sm text-gray-400">Department</p>
<p class="font-semibold text-gray-800">{{ $personnel->department }}</p>
</div>


<div>
<p class="text-sm text-gray-400">Position</p>
<p class="font-semibold text-gray-800">{{ $personnel->position }}</p>
</div>


<div>
<p class="text-sm text-gray-400">Username</p>
<p class="font-semibold text-gray-800">{{ $personnel->user->username }}</p>
</div>


<div>
<p class="text-sm text-gray-400">Email Address</p>
<p class="font-semibold text-gray-800">{{ $personnel->user->email }}</p>
</div>


<div>
<p class="text-sm text-gray-400">Assigned Role</p>
<p class="font-semibold text-gray-800">
{{ $personnel->user->role->role_name ?? 'N/A' }}
</p>
</div>


<div>
<p class="text-sm text-gray-400">Account Status</p>

<p class="font-semibold text-gray-800">

@if($personnel->user->status == 'Inactive')
Inactive
@elseif($personnel->user->must_change_password)
Pending Setup
@else
Active
@endif

</p>

</div>


</div>

</div>





<!-- ACCOUNT LIFECYCLE -->

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">

<h2 class="text-xl font-semibold text-[#101064] mb-6">
Account Setup Details
</h2>


<div class="space-y-5">


<div class="flex justify-between">
<span class="text-gray-400">
Account Created
</span>

<span class="font-semibold text-gray-800">
{{ $personnel->created_at->format('M d, Y') }}
</span>
</div>



<div class="flex justify-between">
<span class="text-gray-400">
Invitation Status
</span>

<span class="font-semibold">

@if($personnel->user->invitation?->used_at)

<span class="text-green-600">
Completed
</span>

@else

<span class="text-yellow-600">
Pending
</span>

@endif

</span>
</div>


<div class="flex justify-between">
<span class="text-gray-400">
Invitation Expiration
</span>

<span class="font-semibold text-gray-800">

{{ $personnel->user->invitation?->expires_at ? \Carbon\Carbon::parse($personnel->user->invitation->expires_at)->format('M d, Y h:i A') : 'N/A' }}

</span>
</div>


<div class="flex justify-between">
<span class="text-gray-400">
Password Setup
</span>

<span class="font-semibold">

@if($personnel->user->must_change_password)

<span class="text-yellow-600">
Not Completed
</span>

@else

<span class="text-green-600">
Completed
</span>

@endif

</span>
</div>


</div>

</div>

<!-- ACTIONS -->

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">

<div class="flex justify-end gap-3">


@if($personnel->user->must_change_password)

<form method="POST" action="{{ route('personnel.resendInvitation',$personnel->personnel_id) }}">
@csrf

<button type="submit"
class="px-6 py-3 rounded-xl border border-[#D4A017] text-[#101064] font-semibold hover:bg-[#D4A017]/10 transition">

Resend Invitation

</button>

</form>

@endif



<a href="{{ route('personnel.edit',$personnel->personnel_id) }}"
class="px-6 py-3 rounded-xl bg-[#101064] text-white font-semibold hover:bg-[#D4A017] transition">

Edit Personnel

</a>


</div>

</div>



</div>


</x-admin-layout>   