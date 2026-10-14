<?php


namespace PixellWeb\Vosfactures\app;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Psr\SimpleCache\InvalidArgumentException;


class Api
{
    protected ?string $base_uri = null;


    /**
     * Api constructor.
     */
    public function __construct(?int $cache_time = null)
    {
        $this->base_uri = config('vosfactures.url').'/';

    }



    /**
     * @param string $method
     * @param string $ressource_path
     * @param array $parameters
     * @param null $query
     * @return string
     * @throws GuzzleException
     * @throws InvalidArgumentException
     * @throws VosfacturesException
     */
    public function request(string $method, string $ressource_path, array $parameters = [], array $query = null): array
    {
        $client = new Client(['base_uri' => $this->base_uri]);
        $options = [
            'headers' => [
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ],
            'query' => [
                'api_token' => config('vosfactures.token')
            ],
        ];

        if ($query !== null) {
            $options['query'] = array_merge($options['query'], $query);
        }

        $parameters = collect($parameters)->reject(fn ($value) => is_null($value))->toArray();
        $options['body'] = json_encode($parameters);

        try {

            $response = $client->request($method, $ressource_path.'.json', $options);

            $return  = $response->getBody()->getContents() ? json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR) : [];

            if ($method === 'DELETE') {
                if ($return !== 'ok') {
                    throw new VosfacturesException('Erreur lors de la suppression : '. $return);
                }
                $return = [];
            }
            return $return;

        } catch (RequestException $exception) {
            throw new VosfacturesException("Request::".$method." : " . $exception->getMessage() . " " . $exception->getResponse()->getBody()->getContents() . ' '.print_r($parameters,true), $exception->getCode(), $exception);
        } catch (\JsonException $exception) {
            throw new VosfacturesException("Request::".$method." : " . $exception->getMessage(), $exception->getCode(), $exception);
        }
    }


    /**
     * @param string $ressource_path
     * @param array $params
     * @return string
     * @throws GuzzleException
     * @throws VosfacturesException|InvalidArgumentException
     */
    public function get(string $ressource_path, array $params = []): array
    {
        return $this->request('GET', $ressource_path, $params);
    }

    /**
     * @param string $ressource_path
     * @param array $params
     * @param null $query
     * @return string
     * @throws GuzzleException
     * @throws InvalidArgumentException
     * @throws VosfacturesException
     */
    public function post(string $ressource_path, array $params = [], $query = null): array
    {
        return $this->request('POST', $ressource_path, $params, $query);
    }


    /**
     * @param string $ressource_path
     * @param array $params
     * @param null $query
     * @return string
     * @throws GuzzleException
     * @throws InvalidArgumentException
     * @throws VosfacturesException
     */
    public function put(string $ressource_path, array $params = [], $query = null): array
    {
        return $this->request('PUT', $ressource_path, $params, $query);
    }


    /**
     * @param string $ressource_path
     * @param array $params
     * @param null $query
     * @return string
     * @throws GuzzleException
     * @throws InvalidArgumentException
     * @throws VosfacturesException
     */
    public function patch(string $ressource_path, array $params = [], $query = null): array
    {
        return $this->request('PATCH', $ressource_path, $params, $query);
    }


    public function delete(string $ressource_path, array $params = [], $query = null): array
    {
        return $this->request('DELETE', $ressource_path, $params, $query);
    }

}
