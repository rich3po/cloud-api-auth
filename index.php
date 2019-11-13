<?php
require __DIR__ . '/vendor/autoload.php';

use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use GuzzleHttp\Client;

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

// See https://docs.acquia.com/acquia-cloud/develop/api/auth/
// for how to generate a client ID and Secret.
$clientId = getenv('API_KEY');
$clientSecret = getenv('API_SECRET');

$provider = new GenericProvider([
    'clientId'                => $clientId,
    'clientSecret'            => $clientSecret,
    'urlAuthorize'            => '',
    'urlAccessToken'          => 'https://accounts.acquia.com/api/auth/oauth/token',
    'urlResourceOwnerDetails' => '',
]);

try {
    // Try to get an access token using the client credentials grant.
    $accessToken = $provider->getAccessToken('client_credentials');

//    $url = 'https://cloud.acquia.com/api/account';
    $url = 'https://cloud.acquia.com/api/applications/acf4a1ad-b1cd-4ad4-a28f-4c05fc302055/notifications';

    // Generate a request object using the access token.
    $request = $provider->getAuthenticatedRequest(
        'GET',
        $url,
        $accessToken
    );

    // Send the request.
    $client = new Client();
    $response = $client->send($request);

    $responseBody = $response->getBody()->getContents();
    print_r($responseBody);


} catch (IdentityProviderException $e) {
    // Failed to get the access token.
    exit($e->getMessage());
}
