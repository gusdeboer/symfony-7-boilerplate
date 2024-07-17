# Google oAuth

## Notice: This is a work in progress

## Google Credentials

To configure Google oAuth, you need to create an application in the Google Cloud Console.

1. Go to the [Google Cloud Console](https://console.cloud.google.com/apis/credentials)
2. Click on `Create Project`
3. Fill in the project name and click on `Create`
4. Click on `Create credentials`
5. Click on `OAuth client ID`
6. Select `Web application`
7. Fill in the following fields:
   - Name
   - Authorized redirect URIs
     - `http://localhost:8000/connect/google/check`
     - `http://127.0.0.1:8000/connect/google/check`
   - Authorized JavaScript origins
     - `http://localhost:8000`
     - `http://127.0.0.1:8000`
8. Click on `Create`
9. Copy the `Client ID` and add it to your `.env` file in `OAUTH_GOOGLE_ID`
10. Copy the `Client secret` and add it to your `.env` file in `OAUTH_GOOGLE_SECRET`
11. Click on `Credentials`
12. Click on `OAuth consent screen`
13. Fill in the following fields:
    - Application name
    - User support email
    - Developer contact information
    - Logo
    - Privacy policy URL
    - Terms of service URL
    - Authorized domains
    - Application homepage link
    - Application description
14. Click on `Save`
15. Click on `Publish app`

## Environment Variables

Add the following environment variables to your `.env` file:

```dotenv
OAUTH_GOOGLE_ID=
OAUTH_GOOGLE_SECRET=
```