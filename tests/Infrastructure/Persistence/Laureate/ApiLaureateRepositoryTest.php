<?php

namespace Testst\Infrastructure\Persistence\Laureate;

use App\Domain\Laureate\Laureate;
use App\Domain\Laureate\LaureateNotFoundException;
use App\Infrastructure\Persistence\Laureate\ApiLaureateRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ApiLaureateRepositoryTest extends TestCase
{
    public function testFindAll()
    {
        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('get')
            ->with('https://api.nobelprize.org/2.1/laureates')
            ->willReturn(
                new Response(
                    200, 
                    [], 
                    file_get_contents(__DIR__ . '/laureates.json')  
                )
            );
            
        $repository = new ApiLaureateRepository($client);
        $laureates = $repository->findAll();

        $this->assertIsArray($laureates);
        $this->assertCount(20, $laureates);
    }

    public function testFindLaureateOfId()
    {
        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('get')
            ->with('https://api.nobelprize.org/2.1/laureates', ['query' => ['id' => 102]])
            ->willReturn(
                new Response(
                    200, 
                    [], 
                    file_get_contents(__DIR__ . '/laureate.json')  
                )
            );

        $repository = new ApiLaureateRepository($client);
        $laureate = $repository->findLaureateOfId(102);

        $this->assertInstanceOf(Laureate::class, $laureate);
        $this->assertEquals('Aage Niels Bohr', $laureate->getFullName());
        $this->assertEquals('1922-06-19', $laureate->getBirthDate());
        $this->assertEquals('Denmark', $laureate->getNativeCountry());
        $this->assertEquals('Physics', $laureate->getCategory());
        $this->assertEquals('1975-10-17', $laureate->getAwardDate());
    }

    public function testNotFoundException()
    {
        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('get')
            ->with('https://api.nobelprize.org/2.1/laureates', ['query' => ['id' => 1337]])
            ->willReturn(
                new Response(
                    404, 
                    [],
                    '{"laureates":[]}'
                )
            );

        $repository = new ApiLaureateRepository($client);
    
        $this->expectException(LaureateNotFoundException::class);
        $repository->findLaureateOfId(1337);
    }

    public function testSortByDateDesc()
    {
        $client = $this->createMock(Client::class);
        $client->expects($this->once())
            ->method('get')
            ->with('https://api.nobelprize.org/2.1/laureates')
            ->willReturn(
                new Response(
                    200, 
                    [], 
                    file_get_contents(__DIR__ . '/laureates.json')  
                )
            );

        $repository = new ApiLaureateRepository($client);
        $laureates = $repository->findAll();

        $this->assertIsArray($laureates);
        $this->assertCount(20, $laureates);
        $this->assertEquals($laureates[0]['fullName']['en'], 'Alain Aspect');
        $this->assertEquals($laureates[0]['nobelPrizes'][0]['dateAwarded'], '2022-10-04');
        
        $this->assertEquals($laureates[1]['fullName']['en'], 'Abdulrazak Gurnah');
        $this->assertEquals($laureates[1]['nobelPrizes'][0]['dateAwarded'], '2021-10-07');
    }
}