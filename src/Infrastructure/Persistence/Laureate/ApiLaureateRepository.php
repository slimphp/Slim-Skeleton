<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Laureate;

use App\Domain\Laureate\Laureate;
use App\Domain\Laureate\LaureateNotFoundException;
use App\Domain\Laureate\LaureateRepository;
use GuzzleHttp\Client;

class ApiLaureateRepository implements LaureateRepository
{
    const URL = 'https://api.nobelprize.org/2.1/laureates';

    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        $response = $this->client->get(self::URL)->getBody()->getContents();
        $laureates = json_decode($response, true)['laureates'];

        $laureates = $this->limit($laureates);
        $laureates = $this->filter($laureates);
        $laureates = $this->sortByDate($laureates);

        return $laureates;
    }

    /**
     * @param int $id
     * @return Laureate
     * @throws LaureateNotFoundException
     */
    public function findLaureateOfId(int $id): Laureate
    {
        $response = $this->client->get(self::URL, [
            'query' => [
                'id' => $id
            ]
        ])->getBody()->getContents();

        $laureates = json_decode($response, true)['laureates'] ?? [];
        $laureates = $this->filter($laureates);
        
        if (empty($laureates)) {
            throw new LaureateNotFoundException();
        }

        $laureate = $laureates[0];

        return new Laureate(
            $laureate['fullName'],
            $laureate['birth'],
            $laureate['nobelPrizes']
        );
    }

    /**
     * @param array $laureates
     * @param bool $order
     * @return array
     */
    private function sortByDate(array $laureates, bool $order = true): array
    {
        usort($laureates, function ($a, $b) use ($order) {
            $dateA = isset($a['nobelPrizes'][0]['dateAwarded']) ? strtotime($a['nobelPrizes'][0]['dateAwarded']) : 0;
            $dateB = isset($b['nobelPrizes'][0]['dateAwarded']) ? strtotime($b['nobelPrizes'][0]['dateAwarded']) : 0;
    
            if (!$order) {
                return $dateA <=> $dateB;
            } else {
                return $dateB <=> $dateA;
            }
        });
    
        return $laureates;
    }

    /**
     * @param array $laureates
     * @param int $limit
     * @return array
     */
    private function limit(array $laureates, int $limit = 20): array
    {
        return array_slice($laureates, 0, $limit);
    }

    /**
     * @param array $laureates
     * @return array
     */
    public function filter(array $laureates): array
    {
        return array_map(function ($laureate) {
            $birth = [
                'date' => $laureate['birth']['date'],
                'country' => $laureate['birth']['place']['country'] ?? null,
            ];

            return [
                'id' => $laureate['id'],
                'fullName' => $laureate['fullName'],
                'birth' => $birth,
                'nobelPrizes' => array_map(function ($nobelPrize) {
                    return [
                        'category' => $nobelPrize['category'],
                        'dateAwarded' => $nobelPrize['dateAwarded'] ?? null,
                    ];
                }, $laureate['nobelPrizes'])
            ];
        }, $laureates);
    }
}