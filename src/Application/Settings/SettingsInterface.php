<?php

declare(strict_types=1);

namespace App\Application\Settings;

interface SettingsInterface
{
    public function get(string $key = ''): mixed;
}
