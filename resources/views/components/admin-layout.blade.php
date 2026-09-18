<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>DySign Admin</title>


@vite([
'resources/css/app.css',
'resources/js/app.js'
])

</head>


<body class="bg-gray-100">



<div class="h-screen flex overflow-hidden">



<!-- SIDEBAR -->

<aside
class="
fixed
left-0
top-0
h-screen
w-80
bg-[#101064]
text-white
shadow-xl
overflow-hidden
"
>


<div
class="
absolute
inset-0
bg-cover
bg-center
opacity-10
"
style="
background-image:url('{{ asset('images/school.jpg') }}');
"
>
</div>





<div
class="
relative
z-10
h-full
flex
flex-col
">





<!-- BRAND -->

<div
class="
px-6
py-6
border-b
border-[#D4A017]
">

<div
class="
flex
items-center
gap-4
">


<img
src="{{ asset('images/logo.png') }}"
class="
w-16
h-16
object-contain
">


<div>


<h1
class="
text-3xl
font-bold
">

Dy<span class="text-[#D4A017]">
Sign
</span>

</h1>


<p
class="
text-xs
text-blue-200
">

Digital Identity System

</p>


</div>


</div>


</div>








<nav
class="
flex-1
p-5
">



<p
class="
text-xs
uppercase
text-blue-300
mb-3
px-4
">

Main

</p>






<!-- DASHBOARD -->

<a

href="/dashboard"

class="
group
flex
items-center
gap-3
px-4
py-3
rounded-xl
mb-5

{{ request()->is('dashboard')
?
'bg-white/10'
:
'hover:bg-white/10'
}}

transition
"

>


<span
class="
transition
{{ request()->is('dashboard')
?
'text-[#D4A017]'
:
'group-hover:text-[#D4A017]'
}}
">

Dashboard

</span>


</a>






<p
class="
text-xs
uppercase
text-blue-300
mb-3
px-4
">

Management

</p>







<!-- PERSONNEL -->

<a

href="/personnel"

class="
group
flex
items-center
px-4
py-3
rounded-xl
transition

{{ request()->routeIs('personnel.*')
?
'bg-white/10'
:
'hover:bg-white/10'
}}

"

>


<span

class="
transition

{{ request()->routeIs('personnel.*')
?
'text-[#D4A017]'
:
'group-hover:text-[#D4A017]'
}}

">

Personnel Management

</span>


</a>






<!-- STUDENT DATA -->

<a

href="#"

class="
group
flex
items-center
px-4
py-3
rounded-xl
hover:bg-white/10
transition
">

<span
class="
group-hover:text-[#D4A017]
transition
">

Student Data

</span>

</a>






<!-- RFID -->

<a

href="#"

class="
group
flex
items-center
px-4
py-3
rounded-xl
hover:bg-white/10
transition
">

<span
class="
group-hover:text-[#D4A017]
transition
">

RFID Management

</span>

</a>

<!-- SYSTEM SECTION -->


<p
class="
text-xs
uppercase
text-blue-300
mt-6
mb-3
px-4
">

System

</p>





@foreach([
    'Roles & Access',
    'Backup Management',
    'Activity Logs'
] as $item)


<a

href="#"

class="
group
flex
px-4
py-3
rounded-xl
hover:bg-white/10
transition
"

>


<span

class="
group-hover:text-[#D4A017]
transition
"

>

{{ $item }}

</span>


</a>


@endforeach





</nav>






</div>


</aside>









<!-- MAIN AREA -->


<div

class="
flex-1
ml-80
h-screen
flex
flex-col
"

>





<!-- NAVBAR -->


<div

class="
sticky
top-0
z-40
"

>


<x-navbar />


</div>








<!-- CONTENT -->


<main

class="
flex-1
overflow-y-auto
p-8
"

>


{{ $slot }}


</main>




</div>






</div>




</body>


</html>