<?php

namespace Spatie\Ray\Settings;

class SettingsFactory
{
    public static function createFromConfigFile(): settings
    {
        $settingValues = (new static())->getSettingsFromConfigFile();

        return new Settings($settingValues);
    }

    public function getSettingsFromConfigFile(): array
    {
        $configFilePath = $this->getConfigFileLocation();

        if (! file_exists($configFilePath)) {
            return [];
        }

        $options = include_once $configFilePath;

        return $options;
    }

    protected function getConfigFileLocation(): string
    {
        $configNames = [
            'ray.php',
        ];

        $configDirectory = getcwd();

        while (is_dir($configDirectory)) {
            foreach ($configNames as $configName) {
                $configFullPath = $configDirectory.DIRECTORY_SEPARATOR.$configName;

                if (file_exists($configFullPath)) {
                    return $configFullPath;
                }
            }

            $parentDirectory = dirname($configDirectory);

            // We do a direct comparison here since there's a difference between
            // the root directories on windows / *nix systems which does not
            // let us compare it against the DIRECTORY_SEPARATOR directly
            if ($parentDirectory === $configDirectory) {
                return '';
            }

            $configDirectory = $parentDirectory;
        }

        return '';
    }
}
