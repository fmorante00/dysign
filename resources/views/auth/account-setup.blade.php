<x-guest-layout>

<div class="min-h-screen flex items-center justify-center bg-gray-100">

<div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">

<div class="text-center mb-6">

<h1 class="text-3xl font-bold text-[#101064]">
Dy<span class="text-[#D4A017]">Sign</span>
</h1>

<p class="text-gray-500 mt-2">
Complete your account setup
</p>

</div>


<div class="bg-gray-50 rounded-lg p-4 mb-6">

<p class="text-sm text-gray-500">
Welcome,
</p>

<p class="font-semibold text-[#101064]">
{{ $invitation->user->name }}
</p>

<p class="text-sm text-gray-500 mt-2">
Assigned Role:
</p>

<p class="font-semibold text-gray-800">
{{ $invitation->user->role->role_name ?? 'N/A' }}
</p>

</div>



<form method="POST" action="{{ route('account.setup.store', $invitation->token) }}">

@csrf


<div class="mb-4">

<label class="block text-sm font-medium">
New Password
</label>

<input
type="password"
name="password"
class="mt-1 w-full rounded-lg border-gray-300 focus:border-[#101064] focus:ring-[#101064]"
required>

@error('password')
<p class="text-red-500 text-sm mt-1">
{{ $message }}
</p>
@enderror

</div>



<div class="mb-4">

<label class="block text-sm font-medium">
Confirm Password
</label>

<input
type="password"
name="password_confirmation"
class="mt-1 w-full rounded-lg border-gray-300 focus:border-[#101064] focus:ring-[#101064]"
required>

</div>



<div class="bg-blue-50 rounded-lg p-3 mb-6 text-sm text-gray-600">

<p class="font-semibold text-[#101064] mb-1">
Password Requirements:
</p>

<ul class="list-disc ml-5">
<li>Minimum of 8 characters</li>
<li>Use a strong password</li>
<li>Do not share your password</li>
</ul>

</div>



<button
class="w-full bg-[#101064] text-white py-3 rounded-lg hover:bg-[#0c0c4d] transition">

Complete Account Setup

</button>


</form>


</div>

</div>

</x-guest-layout>