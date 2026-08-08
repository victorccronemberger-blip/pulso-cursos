<?php

namespace Tests\Unit\Support;

use App\Support\CpfValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CpfValidatorTest extends TestCase
{
    #[DataProvider('validCpfProvider')]
    public function test_it_accepts_valid_cpf_values(string $cpf): void
    {
        $this->assertTrue(CpfValidator::isValid($cpf));
    }

    public static function validCpfProvider(): array
    {
        return [
            ['529.982.247-25'],
            ['52998224725'],
        ];
    }

    #[DataProvider('invalidCpfProvider')]
    public function test_it_rejects_invalid_cpf_values(string $cpf): void
    {
        $this->assertFalse(CpfValidator::isValid($cpf));
    }

    public static function invalidCpfProvider(): array
    {
        return [
            ['111.111.111-11'],
            ['529.982.247-24'],
            ['123'],
        ];
    }
}
