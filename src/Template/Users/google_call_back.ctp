<?php
$gClient = new Google_Client();
$gClient->setClientId("738449930784-cmknopccke5ooekalt60bkgh38bblg39.apps.googleusercontent.com");
$gClient->setClientSecret("IMw-oqh9OYTNuU22bvsN4cKA");
$gClient->setApplicationName("Login Test");
$gClient->setRedirectUri("http://localhost:8765/users/login");
$gClient->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");



$this->saveAccessToken(json_encode($accessToken));
$this->saveRefreshToken($accessToken['refresh_token']);
?>