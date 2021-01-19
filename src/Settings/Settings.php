<?php

namespace Spatie\Ray\Settings;

class Settings
{
    protected array $settings = [];

    protected array $defaultSettings = [
        'host' => 'localhost',
        'port' => 23517,
        'remote_path' => null,
        'local_path' => null,
        'always_send_raw_values' => false,
    ];

    public function __construct(array $settings)
    {
        $this->settings = array_merge($this->defaultSettings, $settings);
    }

    public function setDefaultSettings(array $defaults): self
    {
        $this->settings = array_merge($defaults, $this->settings);

        return $this;
    }

    public function __set(string $name, $value)
    {
        $this->settings[$name] = $value;
    }

    public function __get(string $name)
    {
        return $this->settings[$name] ?? null;
    }
}
