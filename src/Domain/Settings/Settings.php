<?php
declare(strict_types=1);

namespace App\Domain\Settings;

class Settings implements SettingsInterface
{
    /**
     * @var array
     */
    private $settings;

    /**
     * Settings constructor.
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param string $key
     * @return array
     */
    public function get(string $key = ''): array
    {
        return (empty($key)) ? $this->settings : $this->settings[$key];
    }
}