<?php
 
namespace SocialiteProviders\Amazon;

//use Laravel\Socialite\Two\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;

//use Laravel\Socialite\Two\ProviderInterface;
use SocialiteProviders\Manager\Contracts\OAuth2\ProviderInterface;

use Laravel\Socialite\Two\User;

class AmazonProvider extends AbstractProvider implements ProviderInterface
{

    /**
     * Unique Provider Identifier.
     */
    public const IDENTIFIER = 'AMAZON';

    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = ['profile'];

    /**
     * The separating character for the requested scopes.
     *
     * @var string
     */
    protected $scopeSeparator = ' ';

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        $url = 'https://www.amazon.com/ap/oa';

        return $this->buildAuthUrlFromBase($url, $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://api.amazon.com/auth/o2/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return parent::getTokenFields($code) + ['grant_type' => 'authorization_code'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()
                         ->get('https://api.amazon.com/user/profile', [
                             'headers' => [
                                 'Authorization' => 'Bearer '.$token,
                             ],
                         ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'       => $user['user_id'],
            'nickname' => $user['name'] ?? '',
            'name'     => $user['name'] ?? '',
            'email'    => $user['email'] ?? '',
            'avatar'   => '',
        ]);
    }

    public static function additionalConfigKeys()
    {
        return [
                'scope',
             'user_endpoint_version',
        ];
    }
}
