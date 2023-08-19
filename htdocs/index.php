<?php
include 'config.php';
include 'user.class.php';

if (isset($_GET['code'])) {
    $googleClient->authenticate($_GET['code']);
    $_SESSION['google_access_token'] = $googleClient->getAccessToken();
    header('Location: ' . filter_var(GOOGLE_REDIRECT_URL, FILTER_SANITIZE_URL));
    exit();
}

if (isset($_SESSION['google_access_token'])) {
    $googleClient->setAccessToken($_SESSION['google_access_token']);
}

if ($googleClient->isAccessTokenExpired()) {
    unset($_SESSION['google_access_token']);
}

if ($googleClient->getAccessToken()) {
    $gpUserProfile = $googleOauthService->userinfo->get();
    $user = new User();

    // Getting user profile info
    $gpUserData = array();
    $gpUserData['oauth_provider'] = 'google';
    $gpUserData['oauth_uid'] = !empty($gpUserProfile['id']) ? $gpUserProfile['id'] : '';
    $gpUserData['first_name'] = !empty($gpUserProfile['given_name']) ? $gpUserProfile['given_name'] : '';
    $gpUserData['last_name'] = !empty($gpUserProfile['family_name']) ? $gpUserProfile['family_name'] : '';
    $gpUserData['email'] = !empty($gpUserProfile['email']) ? $gpUserProfile['email'] : '';
    $gpUserData['gender'] = !empty($gpUserProfile['gender']) ? $gpUserProfile['gender'] : '';
    $gpUserData['locale'] = !empty($gpUserProfile['locale']) ? $gpUserProfile['locale'] : '';
    $gpUserData['picture'] = !empty($gpUserProfile['picture']) ? $gpUserProfile['picture'] : '';

    // Insert or update user data to the database
    $gpUserData['oauth_uid'] = $user->CheckUser($gpUserData);
    $userData = $user->CheckUser($gpUserData['oauth_uid']);

    // Storing user data in the session
    $_SESSION['userData'] = $userData;

    // Render user profile data
    if (!empty($userData)) {
        $output = '<h2>Google Account Details</h2>';
        $output .= '<div class="ac-data">';
        $output .= '<img src="' . $userData['picture'] . '" width="300" height="220">';
        $output .= '<p><b>Google ID:</b> ' . $userData['oauth_uid'] . '</p>';
        $output .= '<p><b>Name:</b> ' . $userData['first_name'] . ' ' . $userData['last_name'] . '</p>';
        $output .= '<p><b>Email:</b> ' . $userData['email'] . '</p>';
        $output .= '<p><b>Gender:</b> ' . $userData['gender'] . '</p>';
        $output .= '<p><b>Locale:</b> ' . $userData['locale'] . '</p>';
        $output .= '<p><b>Logged in with:</b> Google</p>';
        $output .= '<p><a href="' . $userData['link'] . '" target="_blank">Click to visit Google+</a></p>';
        $output .= '<p><b>Logout from <a href="logout.php">Google</a></b></p>';
        $output .= '</div>';
    } else {
        $output = '<h3 style="color:red">Some problem occurred, please try again.</h3>';
    }
} else {
    // Get login url
    $authUrl = $googleClient->createAuthUrl();
    $output = '<a href="' . filter_var($authUrl, FILTER_SANITIZE_URL) . '" class="login-btn">Sign in with google</a>';
}
?>
<!DOCTYPE html>
<html lang="en-US">

<head>
  <title>Google OAuth Login using PHP</title>
  <meta charset="utf-8">
  <style type="text/css">
    h1 {
      font-family: Arial, Helvetica, sans-serif;
      color: #999999;
    }

    .ac-data {
      width: 960px;
      margin: 0 auto;
      background-color: #F3F3F3;
      padding: 20px;
    }
  </style>
</head>

<body>
  <div class="container">
    <?php echo '<h1>Google OAuth Login using PHP</h1>'; ?>
    <?php echo $output; ?>
  </div>
</body>

</html>