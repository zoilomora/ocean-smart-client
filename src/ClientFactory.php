<?php
declare(strict_types=1);

namespace ZoiloMora\OceanSmartClient;

use GuzzleHttp\RequestOptions;

use function json_decode;

final class ClientFactory
{
    private $client;

    public function __construct(\GuzzleHttp\Client $client)
    {
        $this->client = $client;
    }

    public function build(string $user, string $password): Client
    {
        $token = $this->getToken($user, $password);

        $config = $this->client->getConfig();
        $config['headers']['Authorization'] = 'Bearer ' . $token;

        return new Client(
            new \GuzzleHttp\Client($config)
        );
    }

    private function getToken(string $user, string $password): string
    {
        $response = $this->auth($user, $password);

        if ($this->wasItStarted($response['Token'], $response['SesionYaIniciada'])) {
            $this->unlock(
                $response['Usuario']['EmpresaId'],
                $response['Usuario']['Id'],
                $response['TokenDesbloqueo']
            );

            $response = $this->auth($user, $password);
        }

        return $response['Token'];
    }

    private function wasItStarted(?string $token, bool $startedSession): bool
    {
        if (null !== $token) {
            return false;
        }

        return $startedSession;
    }

    private function auth(string $user, string $password): array
    {
        $response = $this->client->request(
            'POST',
            '/data/auth',
            [
                RequestOptions::JSON => [
                    'ConnId' => null,
                    'Ldap' => false,
                    'Login' => $user,
                    'Password' => $password,
                    'SSOId' => null,
                ],
            ]
        );

        return json_decode(
            $response->getBody()->getContents(),
            true
        );
    }

    private function unlock(int $companyId, int $userId, string $unlockToken): void
    {
        $this->client->request(
            'POST',
            '/data/auth/unlock',
            [
                RequestOptions::JSON => [
                    'empresaId' => $companyId,
                    'tokenDesbloqueo' => $unlockToken,
                    'usuarioId' => $userId,
                ],
            ]
        );
    }
}
