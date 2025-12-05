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
        try {
            $envFile = app()->environmentFilePath();
            
            if (!file_exists($envFile)) {
                throw new \Exception('.env file not found at: ' . $envFile);
            }

            // Check if file is writable
            if (!is_writable($envFile)) {
                throw new \Exception('.env file is not writable. Please check file permissions.');
            }

            // Read the .env file
            $envContent = file_get_contents($envFile);
            
            if ($envContent === false) {
                throw new \Exception('Unable to read .env file.');
            }
            
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
            $result = @file_put_contents($envFile, $envContent, LOCK_EX);
            
            if ($result === false) {
                throw new \Exception('Failed to write to .env file. Please check file permissions.');
            }
            
            // Clear config cache
            try {
                \Artisan::call('config:clear');
            } catch (\Exception $e) {
                // Log but don't fail if cache clear fails
                \Log::warning('Failed to clear config cache: ' . $e->getMessage());
            }
            
            return true;
        } catch (\Exception $e) {
            \Log::error('EnvHelper::updateEnv failed: ' . $e->getMessage());
            throw $e;
        }
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


