<?php

declare(strict_types=1);

namespace App\Domain\Laureate;

use JsonSerializable;

class Laureate implements JsonSerializable
{
    private array $fullName;
    private array $birth;
    private array $nobelPrizes;

    public function __construct(array $fullName, array $birth, array $nobelPrizes)
    {
        $this->fullName = $fullName;
        $this->birth = $birth;
        $this->nobelPrizes = $nobelPrizes;
    }

    /**
     * @param string $language
     * @return string
     */
    public function getFullName(string $language = 'en'): string
    {
        return $this->fullName[$language];
    }

    /**
     * @return string
     */
    public function getBirthDate(): string
    {
        return $this->birth['date'];
    }

    /**
     * @param string $language
     * @return string|null
     */
    public function getNativeCountry(string $language = 'en'): ?string
    {
        return $this->birth['country'][$language];
    }

    /**
     * @param int $index
     * @param string $language
     * @return string
     */
    public function getCategory(int $index = 0, string $language = 'en'): string
    {
        return $this->nobelPrizes[$index]['category'][$language];
    }

    /**
     * @param int $index
     * @return string
     */
    public function getAwardDate(int $index = 0): string
    {
        return $this->nobelPrizes[$index]['dateAwarded'];
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'fullName' => $this->fullName,
            'birth' => $this->birth,
            'nobelPrizes' => $this->nobelPrizes,
        ];
    }
}
