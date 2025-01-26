<?php

declare(strict_types=1);

namespace App\Application\Settings;

class Settings implements SettingsInterface
{
    /**
     * @param array<string, mixed> $settings
     */
    public function __construct(private array $settings)
    {
        $this->settings = $settings;
    }

    public function get(string $key = ''): mixed
    {
        return empty($key) ? $this->settings : $this->settings[$key];
    }
}
