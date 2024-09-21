<?php

// Include Google API client library
require_once '../vendor/autoload.php';

// Include configuration file
$configFile = __DIR__ . '/../config.json';

if (!file_exists($configFile)) {
    throw new RuntimeException(sprintf('Configuration file "%s" not found.', $configFile));
}
$config = json_decode(file_get_contents($configFile), true);

// google api configuration
define('GOOGLE_CLIENT_ID', $config['google']['client_id']);
define('GOOGLE_CLIENT_SECRET', $config['google']['client_secret']);
define('GOOGLE_REDIRECT_URL', $config['google']['redirect_url']);

// Database configuration
define('DB_HOST', $config['database']['host']);
define('DB_USERNAME', $config['database']['username']);
define('DB_PASSWORD', $config['database']['password']);
define('DB_NAME', $config['database']['name']);

// Start session
if (!session_id()) {
    session_start();
}

// Call Google API
$googleClient = new Google\Client();
$googleClient->setApplicationName('Google Login');
$googleClient->setClientId(GOOGLE_CLIENT_ID);
$googleClient->setClientSecret(GOOGLE_CLIENT_SECRET);
$googleClient->setRedirectUri(GOOGLE_REDIRECT_URL);
$googleClient->setScopes([
    'email',
    'profile'
]);

$googleOauthService = new Google\Service\Oauth2($googleClient);
