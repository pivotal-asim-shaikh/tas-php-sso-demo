<?php
require 'vendor/autoload.php';

session_start();

// The below values can all be grabbed from the environment variables under VCAP_SERVICES if this app is bound to SSO.
// Otherwise, it can be found in the SSO developer dashboard for the appplication.
$provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => 'be92e5a2-e7b1-483b-9b6d-164011d1076a',    // The client ID assigned to you by the provider
    'clientSecret'            => '923cc01b-ef08-4ced-928b-65c50714ba94',   // The client password assigned to you by the provider
    'redirectUri'             => 'https://php-client.apps.<domain>',
    'scopes'                  => 'openid todo.read',
    'urlAuthorize'            => 'https://<sso-domain>.login.sys.<domain>.com/oauth/authorize',
    'urlAccessToken'          => 'https://<sso-domain>.login.sys.<domain>/oauth/token',
    'urlResourceOwnerDetails' => 'https://<sso-domain>.login.sys.<domain>/userinfo', 
    'verify'                  => false
]);

// If we don't have an authorization code then get one
if (!isset($_GET['code'])) {

    // Fetch the authorization URL from the provider; this returns the
    // urlAuthorize option and generates and applies any necessary parameters
    // (e.g. state).
    $authorizationUrl = $provider->getAuthorizationUrl();

    // Get the state generated for you and store it to the session.
    $_SESSION['oauth2state'] = $provider->getState();

    // Redirect the user to the authorization URL.
    header('Location: ' . $authorizationUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {

    if (isset($_SESSION['oauth2state'])) {
        unset($_SESSION['oauth2state']);
    }

    exit('Invalid state');

} else {

    try {

        // Try to get an access token using the authorization code grant.
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        // We have an access token, which we may use in authenticated
        // requests against the service provider's API.
        echo 'Access Token: ' . $accessToken->getToken() . "<br>";
        echo 'Refresh Token: ' . $accessToken->getRefreshToken() . "<br>";
        echo 'Expires in: ' . $accessToken->getExpires() . "<br>";
        echo 'Already expired? ' . ($accessToken->hasExpired() ? 'expired' : 'not expired') . "<br>";

        // Using the access token, we may look up details about the
        // resource owner.
        $resourceOwner = $provider->getResourceOwner($accessToken);

        var_export($resourceOwner->toArray());
        echo "<br>";

        // The provider provides a way to get an authenticated API request for
        // the service, using the access token; it returns an object conforming
        // to Psr\Http\Message\RequestInterface.
        
        // Below we make a request to an example API endpoint
        $request = $provider->getAuthenticatedRequest(
            'GET',
            'https://php-resource.apps.<domain>',
            $accessToken
        );

        $response = $provider->getResponse($request);
        echo $response->getBody();

    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

        // Failed to get the access token or user details.
        exit($e->getMessage());

    }

}
