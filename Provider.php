<?php

namespace SocialiteProviders\ContestKit;

use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider
{
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'CONTESTKIT';

    public static function additionalConfigKeys()
    {
        return [
            'host',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected $scopes = [];

    /**
     * {@inheritdoc}
     */
    protected function getPath(string $for): string
    {
        $host = data_get($this->config, 'host', 'https://contestkit.app');

        return match ($for) {
            'authorize' => "{$host}/oauth/authorize",
            'access_token' => "{$host}/oauth/token",
            'user' => "{$host}/api/v1/user",
        };
    }

    protected function getAuthUrl($state): string
    {
        return $this->buildAuthUrlFromBase($this->getPath(for: 'authorize'), $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl(): string
    {
        return $this->getPath(for: 'access_token');
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->getPath(for: 'user'), [
            'headers' => [
                'Authorization' => "Bearer {$token}",
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
            'id' => data_get($user, 'id'),
            'nickname' => data_get($user, 'nickname'),
            'name' => data_get($user, 'name'),
            'email' => data_get($user, 'email'),
            'avatar' => data_get($user, 'avatar'),
        ]);
    }
}
