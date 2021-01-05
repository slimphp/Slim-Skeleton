<?php


namespace App\Domain\Settings;


interface SettingsInterface
{
    public function get(string $key = ''): array;

    public function set(array $settings): SettingsInterface;
}