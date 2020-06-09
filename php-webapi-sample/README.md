# PHP Web Api Sample 

    This sample expects PHP version >= 7.0

# Installing Dependencies

[DotEnv](https://github.com/vlucas/phpdotenv) - Loads environment variables from .env to getenv(), $_ENV and $_SERVER automagically.

    composer require vlucas/phpdotenv

[PHP-JWT](https://github.com/firebase/php-jwt) - A simple library to encode and decode JSON Web Tokens (JWT) in PHP, conforming to RFC 7519.


    composer require firebase/php-jwt

# Files

    php-webapi-sample
    ¦   .env                             - Configurations, ADD THIS TERMINATION TO A .gitignore FILE 
    ¦   bootstrap.php                    - Initial setup code.
    ¦   composer.json                    - Dependencies
    ¦   
    +---public
    ¦       index.php                    - Main function and request router.
    ¦       
    +---src
        AuthenticationService.php        - Authentication manager: Call verification methods and setup authenticated user info
        BaseController.php               - Holds common method for rest controllers.
        DecodeUtil.php                   - Encapsulate decoding methods.
        JwtService.php                   - Encapsulate JWT verification methods.
        ResourceController.php           - Sample rest controller.

# Customizing GrantId's Credentials in your application

Replace the following information inside the `.env` file with your own data:

```
ISSUER="https://<your_subscription>.grantid.com"
API_SCOPE="<your_api_scope>"
```
# Running

    php -S 127.0.0.1:8092 -t public
    
Application will be running on http://localhost:8092

# Using the endpoints

This sample offers three endpoints `/home` which requires **no authentication**, `/secret` and `/claims` both requiring a bearer token
issued by https://lacuna-dev.grantid.com.

**tip:** you can easily obtain a valid token for this api by running [this]() webapp.

