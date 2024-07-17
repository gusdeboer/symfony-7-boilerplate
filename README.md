# Symfony 7 Boilerplate

This is a boilerplate for Symfony 7 projects. It includes the following features:

- Symfony 7
- Docker
- PHP 8.2
- User authentication
- User registration
- Social login (Google, Azure)
- Github Actions (CI/CD)

## Installation

1. Clone the repository
2. Run `make start`
3. Run `make install`

## Usage

1. Run `make start`
2. Open your browser and go to `http://127.0.0.1:8000`

## Configuration

You can configure the following environment variables:

```dotenv
OAUTH_GOOGLE_ID=
OAUTH_GOOGLE_SECRET=
OAUTH_AZURE_ID=
OAUTH_AZURE_SECRET=
```
- [Google Credentials](https://console.cloud.google.com/apis/credentials)
- [Azure Credentials](https://portal.azure.com/#view/Microsoft_AAD_IAM/ActiveDirectoryMenuBlade/~/Overview)