<x-guest-layout>

<div class="min-h-screen bg-gray-50 flex items-center justify-center px-5">


<div class="
w-full
max-w-md
bg-white
rounded-3xl
shadow-xl
border
border-gray-100
p-8
">



<!-- LOGO -->

<div class="text-center mb-8">


<img

src="{{ asset('images/logo.png') }}"

class="
w-20
h-20
mx-auto
object-contain
mb-4
"

>


<h1 class="
text-3xl
font-bold
text-[#101064]
">

Dy<span class="text-[#D4A017]">Sign</span>

</h1>


<p class="
text-sm
text-gray-500
mt-2
">

Digital Identity System

</p>


</div>









<!-- TITLE -->


<h2 class="
text-xl
font-semibold
text-[#101064]
text-center
">

Forgot Password?

</h2>



<p class="
text-sm
text-gray-500
text-center
mt-3
leading-relaxed
">

No problem. Enter your registered email address and we will send you a password reset link.

</p>









@if (session('status'))

<div class="
mt-5
p-4
rounded-xl
bg-green-50
text-green-700
text-sm
">

{{ session('status') }}

</div>

@endif







<!-- FORM -->


<form

method="POST"

action="{{ route('password.email') }}"

class="mt-6"

>

@csrf






<label

class="
block
text-sm
font-medium
text-gray-700
mb-2
"

>

Email Address

</label>




<input

type="email"

name="email"

value="{{ old('email') }}"

required

autofocus

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
focus:border-transparent
"

placeholder="Enter your email"

>



@error('email')

<p class="
text-sm
text-red-500
mt-2
">

{{ $message }}

</p>

@enderror







<button

type="submit"

class="
w-full
mt-6
py-3
rounded-xl
bg-[#101064]
text-white
font-semibold
transition
hover:bg-[#D4A017]
"

>

Send Reset Link

</button>







</form>









<div class="
text-center
mt-6
">


<a

href="{{ route('login') }}"

class="
text-sm
text-[#101064]
hover:text-[#D4A017]
font-medium
transition
"

>

← Back to Login

</a>


</div>








</div>


</div>


</x-guest-layout>