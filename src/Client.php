<?php
declare(strict_types=1);

namespace ZoiloMora\OceanSmartClient;

use \DateTime;
use GuzzleHttp\RequestOptions;

use function json_decode;

final class Client
{
    private $client;
    private $auth;

    public function __construct(\GuzzleHttp\Client $client)
    {
        $this->client = $client;
        $this->auth = $this->currentAuth();
    }

    private function currentAuth(): array
    {
        $response = $this->client->get(
            '/data/auth/actual',
            [
                RequestOptions::QUERY => [
                    'force' => true,
                ],
            ]
        );

        return json_decode(
            $response->getBody()->getContents(),
            true
        );
    }

    public function userData(): array
    {
        $response = $this->client->get(
            '/data/usuarios/' . $this->auth['Usuario']['Id']
        );

        return json_decode(
            $response->getBody()->getContents(),
            true
        );
    }

    public function markings(DateTime $from, DateTime $to): array
    {
        $response = $this->client->get(
            '/data/marcajes',
            [
                RequestOptions::QUERY => [
                    'Desde' => $from->format('Y-m-d'),
                    'EmpleadoId' => $this->auth['Usuario']['Id'],
                    'Hasta' => $to->format('Y-m-d'),
                    'Tipo' => 'P',
                ],
            ]
        );

        return json_decode(
            $response->getBody()->getContents(),
            true
        );
    }

    public function marking(float $latitude, float $longitude): void
    {
        $this->client->post(
            '/data/marcajes/realizar-manual',
            [
                RequestOptions::JSON => [
                    'GeoLat' => $latitude,
                    'GeoLong' => $longitude,
                    'IncidenciaId' => null,
                    'Nota' => null,
                    'Tipo' => 'P',
                    'TipoProd' => 1,
                ],
            ]
        );
    }
}
