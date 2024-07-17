# Azure oAuth

This document explains how to configure Azure oAuth in your Symfony project.

## Notice: This is a work in progress

## Azure Credentials

To configure Azure oAuth, you need to create an application in the Azure portal.

1. Go to the [Azure portal](https://portal.azure.com/#view/Microsoft_AAD_IAM/ActiveDirectoryMenuBlade/~/RegisteredApps)
2. Click on `New registration`
3. Depending on your project, you can fill in the following fields:
   - Name
   - Supported account types (`Accounts in any organizational directory (Any Microsoft Entra ID tenant - Multitenant) and personal Microsoft accounts (e.g. Skype, Xbox)
     `)
   - Redirect URI
        - Web: `http://localhost:8000/connect/azure/check`
4. Click on `Register`
5. Copy the `Application (client) ID` and add it to your `.env` file in `OAUTH_AZURE_ID`
6. Click on `Certificates & secrets`
7. Click on `New client secret`
8. Fill in the description and expiration date
9. Click on `Add`
10. Copy the `Value` and add it to your `.env` file in `OAUTH_AZURE_SECRET`
11. Click on `API permissions`
12. Click on `Add a permission`
13. Click on `Microsoft Graph`
14. Click on `Delegated permissions`
15. Select the permissions you need
16. Click on `Add permissions`
17. Click on `Grant admin consent for {your organization}`
18. Click on `Yes`
19. Click on `Overview`

## Environment Variables

Add the following environment variables to your `.env` file:

```dotenv
OAUTH_AZURE_ID=
OAUTH_AZURE_SECRET=
```