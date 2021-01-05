<?php


namespace App\Domain\Settings;


class Settings implements SettingsInterface
{
    private $settings;

    public function get(string $key = ''): array
    {
        if (empty($key)) {
            return $this->settings;
        }

        return $this->settings[$key];
    }

    public function set(array $settings): SettingsInterface
    {
        $this->settings = $settings;
        return $this;
    }
}