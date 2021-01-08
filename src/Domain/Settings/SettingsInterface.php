<?php
declare(strict_types=1);

namespace App\Domain\Settings;

interface SettingsInterface
{
    /**
     * @param string $key
     * @return array
     */
    public function get(string $key = ''): array;
}