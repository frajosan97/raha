<?php

use Illuminate\Support\Facades\Storage;

/**
 * Format a given phone number to the Kenyan format.
 *
 * @param string $phoneNumber The phone number to format.
 * @return string The formatted phone number.
 */
if (!function_exists('formatPhoneNumber')) {
    function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove spaces, dashes, and parentheses
        $phoneNumber = preg_replace('/[\s()-]/', '', $phoneNumber);

        // Ensure the phone number starts with "254" (Kenyan code)
        if (preg_match('/^0[17]\d{8}$/', $phoneNumber)) {
            // Convert "07..." or "01..." to "2547..." or "2541..."
            $phoneNumber = '254' . substr($phoneNumber, 1);
        } elseif (preg_match('/^\+254/', $phoneNumber)) {
            // Remove the "+" from numbers starting with "+254"
            $phoneNumber = substr($phoneNumber, 1);
        }

        return $phoneNumber;
    }
}

/**
 * Format a phone number into a readable grouped format.
 *
 * @param string $phone The phone number to format.
 * @return string The formatted phone number.
 */
if (!function_exists('groupPhoneNumber')) {
    function groupPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        $length = strlen($phone);

        // Apply formatting based on phone number length
        if ($length === 12) {
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{3})/', '($1) $2-$3-$4', $phone);
        } elseif ($length === 10) {
            return preg_replace('/(\d{3})(\d{3})(\d{4})/', '($1) $2-$3', $phone);
        } elseif ($length === 11) {
            return preg_replace('/(\d{1})(\d{3})(\d{3})(\d{4})/', '$1 ($2) $3-$4', $phone);
        } elseif ($length === 7) {
            return preg_replace('/(\d{3})(\d{4})/', '$1-$2', $phone);
        }

        return $phone; // Return original if no format matched
    }
}

/**
 * Format an amount to two decimal places.
 *
 * @param mixed $amount The amount to format.
 * @return string The formatted amount.
 * @throws InvalidArgumentException If the input is not numeric.
 */
if (!function_exists('formatAmount')) {
    function formatAmount($amount): string
    {
        if (!is_numeric($amount)) {
            throw new InvalidArgumentException("Invalid amount provided.");
        }
        return number_format((float)$amount, 2, '.', '');
    }
}

/**
 * Store logs in a JSON file, creating or updating as necessary.
 *
 * @param string $type The type of log.
 * @param mixed $data The data to store.
 */
if (!function_exists('storeLog')) {
    function storeLog(string $type, $data): void
    {
        $filePath = "$type.json";
        $existingData = Storage::disk('public')->exists($filePath)
            ? json_decode(Storage::disk('public')->get($filePath), true)
            : [];
        $existingData[] = $data;

        Storage::disk('public')->put($filePath, json_encode($existingData, JSON_PRETTY_PRINT));
    }
}

/**
 * Generate a random key of a specified length.
 *
 * @param int $length The length of the key to generate. Default is 20.
 * @return string The generated key.
 */
if (!function_exists('generateKey')) {
    function generateKey(): string
    {
        return substr(bin2hex(random_bytes(10)), 0, 20);
    }
}

if (!function_exists('referalCode')) {
    function referalCode(): string
    {
        return strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
    }
}

if (!function_exists('formatToInteger')) {
    function formatToInteger($amount)
    {
        return (int) round(floatval($amount) * 1000);
    }
}

if (!function_exists('formatToDecimal')) {
    function formatToDecimal($amount)
    {
        return number_format($amount / 1000, 2, '.', '');
    }
}

if (!function_exists('generateOtp')) {
    function generateOtp()
    {
        return random_int(100000, 999999); // Generates a 6-digit random OTP
    }
}

/**
 * Obfuscate phone number for display.
 *
 * @param string $phone
 * @return string
 */
if (!function_exists('obfuscatePhone')) {
    function obfuscatePhone(string $phone): string
    {
        return substr($phone, 0, 5) . str_repeat('*', strlen($phone) - 8) . substr($phone, -3);
    }
}
