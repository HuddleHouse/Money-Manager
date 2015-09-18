<!-- resources/views/emails/password.blade.php -->
Hello,<br>
This email is being sent you because someone requested a password reset for Money Manager at matthuddleston.com.<br><br>
Have a great day!
<br><br>
Click here to reset your password: {{ url('password/reset/'.$token) }}