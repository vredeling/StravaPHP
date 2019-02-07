<?php
namespace Strava\API;

use League\OAuth2\Client\Entity\User;
use League\OAuth2\Client\Token\AccessToken as AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait as BearerAuthorizationTrait;
use League\OAuth2\Client\Provider\AbstractProvider as AbstractProvider;
use Psr\Http\Message\ResponseInterface;

/**
 * Strava OAuth
 * The Strava implementation of the OAuth client
 *
 * @see: https://github.com/thephpleague/oauth2-client
 * @author Bas van Dorst
 */
class OAuth extends AbstractProvider
{
    use BearerAuthorizationTrait;

    public $scopes = ['activity:write', 'profile:write'];
    public $responseType = 'json';

    /**
     * @see AbstractProvider::urlAuthorize
     */
    public function urlAuthorize()
    {
        return 'https://www.strava.com/oauth/authorize';
    }

    /**
     * @see AbstractProvider::urlAuthorize
     */
    public function urlDeauthorize()
    {
        return 'https://www.strava.com/oauth/deauthorize';
    }

    /**
     * @see AbstractProvider::urlAccessToken
     */
    public function urlAccessToken()
    {
        return 'https://www.strava.com/oauth/token';
    }

    /**
     * @see AbstractProvider::urlUserDetails
     */
    public function urlUserDetails(AccessToken $token)
    {
        return 'https://www.strava.com/api/v3/athlete';
    }

    /**
     * @see AbstractProvider::userDetails
     */
    public function userDetails($response, AccessToken $token)
    {
        $user = new \stdClass;

        $user->uid = $response->id;
        $user->name = implode(' ', [$response->firstname, $response->lastname]);
        $user->firstName = $response->firstname;
        $user->lastName = $response->lastname;
        $user->email = $response->email;
        $user->location = $response->country;
        $user->imageUrl = $response->profile;
        $user->gender = $response->sex;

        return $user;
    }

    /**
     * @see AbstractProvider::userUid
     */
    public function userUid($response, AccessToken $token)
    {
        return $response->id;
    }

    /**
     * @see AbstractProvider::userEmail
     */
    public function userEmail($response, AccessToken $token)
    {
        return isset($response->email) && $response->email ? $response->email : null;
    }

    /**
     * @see AbstractProvider::userScreenName
     */
    public function userScreenName($response, AccessToken $token)
    {
        return implode(' ', [$response->firstname, $response->lastname]);
    }

    /**
     * @see AbstractProvider::getBaseAuthorizationUrl
     */
    public function getBaseAuthorizationUrl()
    {
        return 'https://www.strava.com/oauth/authorize';
    }

    /**
     * @see AbstractProvider::getBaseAccessTokenUrl
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return 'https://www.strava.com/oauth/token';
    }

    /**
     * @see AbstractProvider::getResourceOwnerDetailsUrl
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return '';
    }

    /**
     * @see AbstractProvider::getDefaultScopes
     */
    protected function getDefaultScopes()
    {
        return ['write'];
    }

    /**
     * @see AbstractProvider::checkResponse
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
    }

    /**
     * @see AbstractProvider::createResourceOwner
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
    }
}
