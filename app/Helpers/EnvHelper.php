<?php

namespace App\Helpers;

class EnvHelper
{
    /**
     * Update .env file with new values
     * 
     * @param array $data Key-value pairs to update
     * @return bool
     */
    public static function updateEnv(array $data)
    {
        $envFile = base_path('.env');
        
        if (!file_exists($envFile)) {
            return false;
        }

        // Read the .env file
        $envContent = file_get_contents($envFile);
        
        // Update each key-value pair
        foreach ($data as $key => $value) {
            // Escape special characters in value
            $escapedValue = self::escapeEnvValue($value);
            
            // Pattern to match the key (with or without quotes)
            $pattern = "/^{$key}=(.*)$/m";
            
            // Check if key exists
            if (preg_match($pattern, $envContent)) {
                // Replace existing value
                $envContent = preg_replace($pattern, "{$key}={$escapedValue}", $envContent);
            } else {
                // Append new key-value pair
                $envContent .= "\n{$key}={$escapedValue}\n";
            }
        }
        
        // Write back to .env file
        $result = file_put_contents($envFile, $envContent);
        
        // Clear config cache
        if (function_exists('artisan')) {
            \Artisan::call('config:clear');
        }
        
        return $result !== false;
    }
    
    /**
     * Escape value for .env file
     * 
     * @param string $value
     * @return string
     */
    private static function escapeEnvValue($value)
    {
        // If value contains spaces or special characters, wrap in quotes
        if (preg_match('/[\s#=]/', $value) || empty($value)) {
            // Escape quotes and backslashes
            $value = str_replace(['\\', '"'], ['\\\\', '\\"'], $value);
            return '"' . $value . '"';
        }
        
        return $value;
    }
    
    /**
     * Get value from .env file
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getEnv($key, $default = null)
    {
        return env($key, $default);
    }
}

