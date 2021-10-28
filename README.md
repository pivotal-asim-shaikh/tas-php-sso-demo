# tas-php-sso-demo
A demo of a TAS application written in PHP that integrates with the SSO tile.

## Pre-requisite

You'll need a TAS environment with the SSO tile installed and an SSO instance created. This demo does rely on two third party libraries. 
- https://oauth2-client.thephpleague.com/ on the client side to act as an OAuth2 client.
- https://github.com/firebase/php-jwt on the resource side to help decode the jwt token

I've included the vendor directories in this repo. for convenience. Otherwise you can grab them with Composer.

```
cd client
composer require league/oauth2-server
cd ../resource
composer require firebase/php-jwt
```
Just be aware that if you bring them in from scratch you'll neeed to update the file **tas-php-sso-demo/client/vendor/league/oauth2-client/src/Provider/GenericProvider.php** with the following method. This is because the Generic Provider doesn't return any authorization headers by default.

```
    /**
     * Returns the authorization headers used by this provider.
     *
     * Typically this is "Bearer" or "MAC". For more information see:
     * http://tools.ietf.org/html/rfc6749#section-7.1
     *
     * No default is provided, providers must overload this method to activate
     * authorization headers.
     *
     * @param  mixed|null $token Either a string or an access token instance
     * @return array
     */
    protected function getAuthorizationHeaders($token = null)
    {        
        return array("HTTP_AUTHORIZATION" => "Bearer " + $token->getToken());        
    }
```


## Instructions

This demo uses two applications deployed to TAS (Cloud Foundry). One is a web application secured with the SSO tile using an Authorization Token grant type. This application is acting as an OAuth2 client. 

To deploy the application first fill out the fields in the client/index.php file. These are mostly values you'll find in the developer dashboard of your SSO instance.

Once you've edited the file, make sure you're in the client directory and ```cf push```

Next switch into the resource directory.

Update the resource/index.php file with your SSO instance token issuer public key from https://<sso-domain>.login.sys.<domain>/token_keys, the App ID, and Token URL. The last two items can be found in the SSO instance developer dashboard under credentials.
  
Then switch into the resource directory and ```cf push```
  
You should now have two applications running. If you hit the resource directly you will find that it prevents access as you're not authenticated. If you hit the client application it will send you to your identity provider to authenticate and then serve up the contents of the resource.
