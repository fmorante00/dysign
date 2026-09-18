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
"

>




<!-- BRAND -->

<div

class="
px-6
py-6
border-b
border-[#D4A017]
"

>


<div

class="
flex
items-center
gap-4
"

>


<img

src="{{ asset('images/logo.png') }}"

class="
w-14
h-14
object-contain
"

>


<div>

<h1

class="
text-3xl
font-bold
"

>

Dy<span class="text-[#D4A017]">Sign</span>

</h1>


<p class="
text-xs
text-blue-200
">

Digital Identity System

</p>


</div>


</div>


</div>







<!-- NAVIGATION -->

<nav

class="
flex-1
p-5
overflow-y-auto
"

>



@php

$role = auth()->user()->role->role_name;

@endphp







@if($role === 'Administrator')



<!-- ADMIN DASHBOARD -->

<p class="
px-4
mb-3
text-xs
uppercase
tracking-wider
text-blue-300
">

Main

</p>




<a

href="/dashboard"

class="
flex
items-center
gap-4
px-4
py-3
rounded-xl
mb-2
transition

{{ request()->is('dashboard')
?
'bg-white/10 text-[#D4A017]'
:
'hover:bg-white/10'
}}

"

>

<span>

▣

</span>


<span>

Dashboard

</span>


</a>









<!-- MANAGEMENT -->

<p class="
px-4
mt-6
mb-3
text-xs
uppercase
tracking-wider
text-blue-300
">

Management

</p>








<a

href="/personnel"

class="
flex
items-center
gap-4
px-4
py-3
rounded-xl
transition

{{ request()->routeIs('personnel.*')
?
'bg-white/10 text-[#D4A017]'
:
'hover:bg-white/10'
}}

"

>

<span>

♙

</span>


<span>

Personnel Management

</span>


</a>







<a

href="/students"

class="
flex
items-center
gap-4
px-4
py-3
rounded-xl
transition

{{ request()->routeIs('students.*')
?
'bg-white/10 text-[#D4A017]'
:
'hover:bg-white/10'
}}

"

>

<span>

▤

</span>


<span>

Student Data

</span>


</a>







<a

href="{{ route('events.index') }}"

class="
flex
items-center
gap-4
px-4
py-3
rounded-xl
transition

{{ request()->routeIs('events.*')
?
'bg-white/10 text-[#D4A017]'
:
'hover:bg-white/10'
}}

"

>

<span>

◇

</span>


<span>

Event Management

</span>


</a>







<a

href="/rfid"

class="
flex
items-center
gap-4
px-4
py-3
rounded-xl
transition

{{ request()->routeIs('rfid.*')
?
'bg-white/10 text-[#D4A017]'
:
'hover:bg-white/10'
}}

"

>

<span>

◉

</span>


<span>

RFID Management

</span>


</a>







<!-- SYSTEM -->

<p class="
px-4
mt-6
mb-3
text-xs
uppercase
tracking-wider
text-blue-300
">

System

</p>






<a

href="{{ route('roles.index') }}"

class="
flex
items-center
gap-4
px-4
py-3
rounded-xl
transition

{{ request()->routeIs('roles.*')
?
'bg-white/10 text-[#D4A017]'
:
'hover:bg-white/10'
}}

"

>

<span>

⚙

</span>


<span>

Roles & Access

</span>


</a>






<a

href="{{ route('backup.index') }}"

<a

href="{{ route('backup.index') }}"

class="
flex
items-center
gap-4
px-4
py-3
rounded-xl
transition

{{ request()->routeIs('backup.*')
?
'bg-white/10 text-[#D4A017]'
:
'hover:bg-white/10'
}}

"
>



<span>

↻

</span>


<span>

Backup Management

</span>


</a>






<a

href="{{ route('logs.index') }}"

<a

href="{{ route('logs.index') }}"

class="
flex
items-center
gap-4
px-4
py-3
rounded-xl
transition

{{ request()->routeIs('logs.*')
?
'bg-white/10 text-[#D4A017]'
:
'hover:bg-white/10'
}}

"


>

<span>

☷

</span>


<span>

Activity Logs

</span>


</a>

<!-- ================= ATTENDANCE PERSONNEL ================= -->

@elseif($role === 'Attendance Personnel')



<p class="
px-4
mb-3
text-xs
uppercase
tracking-wider
text-blue-300
">

Main

</p>






<a

href="/dashboard"

class="
flex
items-center
gap-4
px-4
py-3
rounded-xl
mb-2
transition

{{ request()->is('dashboard')
?
'bg-white/10 text-[#D4A017]'
:
'hover:bg-white/10'
}}

"

>


<span>

▣

</span>


<span>

Dashboard

</span>


</a>








<p class="
px-4
mt-6
mb-3
text-xs
uppercase
tracking-wider
text-blue-300
">

Attendance Operations

</p>








<a

href="{{ route('my-events.index') }}"

class="
flex
items-center
gap-4
px-4
py-3
rounded-xl
transition

{{ request()->routeIs('my-events.*')
?
'bg-white/10 text-[#D4A017]'
:
'hover:bg-white/10'
}}

"

>


<span>

◷

</span>


<span>

My Assigned Events

</span>


</a>








<a

href="{{ route('attendance.index') }}"

class="
flex
items-center
gap-4
px-4
py-3
rounded-xl
transition

{{ request()->routeIs('attendance.*')
?
'bg-white/10 text-[#D4A017]'
:
'hover:bg-white/10'
}}

"

>


<span>

◉

</span>


<span>

Attendance Scanner

</span>


</a>













<!-- ================= REGISTRAR STAFF ================= -->

@elseif($role === 'Registrar Staff')





<p class="
px-4
mb-3
text-xs
uppercase
tracking-wider
text-blue-300
">

Main

</p>








<a

href="/dashboard"

class="
flex
items-center
gap-4
px-4
py-3
rounded-xl
transition

{{ request()->is('dashboard')
?
'bg-white/10 text-[#D4A017]'
:
'hover:bg-white/10'
}}

"

>


<span>

▣

</span>


<span>

Dashboard

</span>


</a>








<p class="
px-4
mt-6
mb-3
text-xs
uppercase
tracking-wider
text-blue-300
">

Student Records

</p>







<a

href="/students"

class="
flex
items-center
gap-4
px-4
py-3
rounded-xl
transition

{{ request()->routeIs('students.*')
?
'bg-white/10 text-[#D4A017]'
:
'hover:bg-white/10'
}}

"

>


<span>

▤

</span>


<span>

Student Data

</span>


</a>








@endif




</nav>




</div>


</aside>