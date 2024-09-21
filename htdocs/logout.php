<?php

include 'config.php';

// remove token and user data from the session
unset($_SESSION['google_access_token']);
unset($_SESSION['userData']);

// reset OAuth access token
$googleClient->revokeToken();

// destroy entire session
session_destroy();

// redirect to the homepage
header("Location: index.php");
exit();
