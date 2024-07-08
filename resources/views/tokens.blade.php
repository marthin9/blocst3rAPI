<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blocst3r</title>
</head>
<body>
  <h1>Your Auth Token Access</h1>
  <p>Token Type : {{ $tokenData['token_type'] }}</p>
  <p>Access Token : {{ $tokenData['access_token'] }}</p>
  <p>Refresh Token : {{ $tokenData['refresh_token'] }}</p>
  <p>Expires : {{ $tokenData['expires_in'] }}</p>



</body>
</html>