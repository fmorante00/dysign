<!DOCTYPE html>
<html>
<head>
    <title>DySign Account Invitation</title>
</head>

<body style="font-family: Arial, sans-serif; background:#f4f4f4; padding:40px;">

<div style="
    max-width:600px;
    margin:auto;
    background:white;
    padding:30px;
    border-radius:10px;
">


<h2 style="color:#101064;">
    Welcome to DySign
</h2>


<p>
Hello <strong>{{ $invitation->user->name }}</strong>,
</p>


<p>
Your DySign account has been created by the administrator.
</p>


<p>
Your assigned role is:
</p>


<p>
<strong>
{{ $invitation->user->role->role_name }}
</strong>
</p>



<p>
To complete your account setup, click the button below:
</p>



<div style="text-align:center; margin:30px 0;">


<a href="{{ url('/account/setup/'.$invitation->token) }}"
style="
background:#101064;
color:white;
padding:12px 25px;
border-radius:8px;
text-decoration:none;
display:inline-block;
">

Complete Account Setup

</a>


</div>



<p>
This invitation link will expire on:
</p>


<p>
<strong>
{{ $invitation->expires_at }}
</strong>
</p>



<p>
If you did not expect this account invitation, please ignore this email.
</p>



<br>


<p>
Regards,<br>
<strong>DySign Administration</strong>
</p>


</div>

</body>
</html>