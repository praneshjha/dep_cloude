<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<h3>Welcome to the Departure Cloud,</h3>
<p>Your registration is successfully occurred, Please Click below to verify your email address.</p>
<a class="btn btn-primary" href="http://localhost:8000/verify_email/token/{{$token_user}}/email/{{$email}}">Verify</a>

<h3>Regards,<br>
Departure Cloud</h3>
<hr>

<p>If you’re having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser: </p><a href="http://localhost:8000/verify_email/token/{{$token_user}}/email/{{$email}}">http://localhost:8000/verify_email/token/{{$token_user}}/email/{{$email}}</a>
</body>
</html>