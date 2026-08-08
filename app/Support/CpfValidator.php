<?php

namespace App\Support;

final class CpfValidator
{
    public static function normalize(?string $cpf): string
    {
        return preg_replace('/\D+/', '', (string) $cpf);
    }

    public static function isValid(?string $cpf): bool
    {
        $cpf = self::normalize($cpf);

        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($digit = 9; $digit < 11; $digit++) {
            $sum = 0;
            for ($index = 0; $index < $digit; $index++) {
                $sum += ((int) $cpf[$index]) * (($digit + 1) - $index);
            }
            $check = (10 * $sum) % 11;
            $check = $check === 10 ? 0 : $check;
            if ((int) $cpf[$digit] !== $check) {
                return false;
            }
        }

        return true;
    }
}
