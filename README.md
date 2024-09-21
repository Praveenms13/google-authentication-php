# Google Authentication Integration

This project demonstrates how to integrate Google Authentication using the Google API PHP client. The live version of this project can be accessed at [https://google-auth.praveenms.live/](https://google-auth.praveenms.live/).

## Overview

Google Authentication is a common method to allow users to log in to your application using their Google account credentials. This integration is achieved using the Google API PHP client, which simplifies the process of interacting with Google APIs.

## Features

- **Login with Google:** Users can sign in to the application using their Google accounts.
- **Access Google APIs:** Once authenticated, the application can access various Google APIs on behalf of the user.

## Getting Started

Follow these steps to set up the Google Authentication integration on your local environment:

1. Clone the repository:

```bash
git clone https://github.com/your-username/your-repo.git
cd your-repo
```

2. Install dependencies using Composer:

```bash
composer install
```

3. Configure Google API Credentials:

- Go to the Google Developer Console.
- Create a new project or select an existing one.
- Set up OAuth 2.0 credentials and configure the authorized redirect URIs.
- Download the JSON credentials file and save it as google_credentials.json in the project directory.

From that json file make config.json with database creds like below

```json
{
  "google": {
    "client_id": "<client id>",
    "client_secret": "client secret",
    "redirect_url": "https://google-auth.praveenms.live/"
  },
  "database": {
    "host": "<server url>",
    "username": "<username>",
    "password": "<password>",
    "name": "<dbname>"
  }
}
```

4. Start the development server:

```bash
php -S localhost:8000
```

5. Access the application in your browser: http://localhost:8000

## Usage

1. Visit the application URL.
2. Click on the "Login with Google" button.
3. You will be redirected to Google's authentication page.
4. After granting permissions, you'll be redirected back to the application.
