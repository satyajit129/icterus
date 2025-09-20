<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;

class EncryptionService
{
    /**
     * Encrypt a value
     */
    public static function encrypt($value)
    {
        return Crypt::encryptString($value);
    }

    /**
     * Decrypt a value
     */
    public static function decrypt($encryptedValue)
    {
        try {
            return Crypt::decryptString($encryptedValue);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Encrypt product ID for URL
     */
    public static function encryptProductId($productId)
    {
        return base64_encode(self::encrypt($productId));
    }

    /**
     * Decrypt product ID from URL
     */
    public static function decryptProductId($encryptedId)
    {
        try {
            $decoded = base64_decode($encryptedId);
            return self::decrypt($decoded);
        } catch (\Exception $e) {
            return null;
        }
    }
}
