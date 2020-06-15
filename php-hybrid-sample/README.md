# PHP Web Api Sample 

    This sample expects PHP version >= 7.0

# Installing Dependencies

[DotEnv](https://github.com/vlucas/phpdotenv) - Loads environment variables from .env to getenv(), $_ENV and $_SERVER automagically.

    composer require vlucas/phpdotenv

[Twig](https://twig.symfony.com/) - Twig is a modern template engine for PHP

    composer require "twig/twig:^2.0"

[OpenID-Connect-PHP](https://github.com/jumbojett/OpenID-Connect-PHP) - A simple library that allows an application to authenticate a user through the basic OpenID Connect flow.

    composer require jumbojett/openid-connect-php

# Files

    php-hybrid-sample
    |   .env                           - Configurations, ADD THIS TERMINATION TO A .gitignore FILE.
    |   bootstrap.php                  - Initial setup code.
    |   composer.json                  - Dependencies
    |   
    +---public
    |   |   index.php                  - Entry point.
    |   |   
    |   +---css                        - Front end.
    |   |       style.css
    |   |       
    |   +---html                       - Front end.
    |           base.html
    |           index.html
    |           privacy.html
    |           privateRoute.html
    |           
    +---src 
        App.php                        - Router
        Controller.php                 - Holds common method for controllers.
        HomeController.php             - Sample controller.
        init.php                       - Application loader.
        OpenIdClient.php               - Encapsulate hybrid flow methods.
       

# Customizing GrantId's Credentials in your application

Replace the following information inside the `.env` file with your own data:

```
ISSUER="https://<your-subscription>.grantid.com"
CLIENT_ID="<your-client-id>"
CLIENT_SECRET="<your-client-secret>"
LOGIN_URI="http://localhost:8091/login"
SCOPE="openid profile <your-api-scope>"
POST_LOGOUT_REDIRECT_URI="http://localhost:8091/"
```

# Running

    php -S 127.0.0.1:8091 -t public
    
Application will be running on http://localhost:8091

# Using the WebApp

The only private route on the application is http://localhost:8091/Home/PrivateRoute, if the user is not already authenticated, when trying to access this resource, it will be redirected to the GrantId login page to perform authentication.

