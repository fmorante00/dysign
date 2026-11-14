<?php

namespace App\Support;

/**
 * Format/known-placeholder guard, not a guarantee that an inbox exists.
 */
final class AnnouncementEmailAddress
{
    public static function isSendable(?string $address): bool
    {
        $address = strtolower(trim((string) $address));
        if (!filter_var($address, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $domain = substr(strrchr($address, '@'), 1);
        if (in_array($domain, ['example.com', 'example.net', 'example.org', 'localhost'], true)) {
            return false;
        }

        foreach (['.test', '.invalid', '.example', '.localhost'] as $suffix) {
            if (str_ends_with($domain, $suffix)) {
                return false;
            }
        }

        return true;
    }

    public static function preferred(?string ...$addresses): ?string
    {
        foreach ($addresses as $address) {
            $email = trim((string) $address);
            if (self::isSendable($email)) {
                return $email;
            }
        }

        return null;
    }
}
