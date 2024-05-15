<?php

declare(strict_types=1);

namespace Tests\Domain\Laureate;

use App\Domain\Laureate\Laureate;
use Tests\TestCase;

class LaureateTest extends TestCase
{
    public function laureatesProvider(): array
    {
        return [
            [
                [
                    'en' => 'Aaron Ciechanover',
                    'se' => 'Aaron Ciechanover'
                ],
                [
                    'date' => '1922-06-19',
                    'country' => [
                        'en' => 'British Protectorate of Palestine',
                        'no' => 'British i Palestina',
                        'se' => 'British Protectorate of Palestine'
                    ],
                ],
                [
                    [
                        'category' => [
                            'en' => 'Physics',
                            'no' => 'Fysikk',
                            'se' => 'Fysik'
                        ],
                        'dateAwarded' => '1975-10-17',
                    ],
                    [
                        'category' => [
                            'en' => 'Chemistry',
                            'no' => 'Kjemi',
                            'se' => 'Kemi'
                        ],
                        'dateAwarded' => '1980-12-08',
                    ]
                ]
            ]
        ];
    }
    

    /**
     * @dataProvider laureatesProvider
     *
     * @param array $fullName
     * @param array $birth
     * @param array $nobelPrizes
     * @return void
     */
    public function testInstansiate(array $fullName, array $birth, array $nobelPrizes): void
    {
        $laureate = new Laureate(
            $fullName,
            $birth,
            $nobelPrizes,
        );

        $this->assertInstanceOf(Laureate::class, $laureate);
    }

    /**
     * @dataProvider laureatesProvider
     *
     * @param array $fullName
     * @param array $birth
     * @param array $nobelPrizes
     * @return void
     */
    public function testGetters(array $fullName, array $birth, array $nobelPrizes)
    {
        $laureate = new Laureate(
            $fullName,
            $birth,
            $nobelPrizes
        );

        $this->assertEquals($fullName['en'], $laureate->getFullName());
        $this->assertEquals($birth['date'], $laureate->getBirthDate());
        $this->assertEquals($birth['country']['en'], $laureate->getNativeCountry());
        $this->assertEquals($nobelPrizes[0]['category']['en'], $laureate->getCategory());
        $this->assertEquals($nobelPrizes[1]['category']['en'], $laureate->getCategory(1));
        $this->assertEquals($nobelPrizes[1]['dateAwarded'], $laureate->getAwardDate(1));
    }

    /**
     * @dataProvider laureatesProvider
     *
     * @param array $fullName
     * @param array $birth
     * @param array $nobelPrizes
     * @return void
     */
    public function testDifferentKeyOnGetters(array $fullName, array $birth, array $nobelPrizes)
    {
        $laureate = new Laureate(
            $fullName,
            $birth,
            $nobelPrizes
        );

        $this->assertEquals($fullName['se'], $laureate->getFullName('se'));
        $this->assertEquals($birth['date'], $laureate->getBirthDate());
        $this->assertEquals($birth['country']['se'], $laureate->getNativeCountry('se'));
        $this->assertEquals($nobelPrizes[0]['category']['se'], $laureate->getCategory(0,'se'));
        $this->assertEquals($nobelPrizes[0]['dateAwarded'], $laureate->getAwardDate());
    }

    /**
     * @dataProvider laureatesProvider
     *
     * @param array $fullName
     * @param array $birth
     * @param array $nobelPrizes
     * @return void
     */
    public function testJsonSerialize(array $fullName, array $birth, array $nobelPrizes)
    {
        $laureate = new Laureate(
            $fullName,
            $birth,
            $nobelPrizes
        );

        $expectedPayloAd = json_encode([
            'fullName' => $fullName,
            'birth' => $birth,
            'nobelPrizes' => $nobelPrizes,
        ]);

        $this->assertEquals($expectedPayloAd, json_encode($laureate));
    }
}