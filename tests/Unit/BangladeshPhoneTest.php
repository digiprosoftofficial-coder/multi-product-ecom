<?php

namespace Tests\Unit;

use App\Rules\BangladeshPhone;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class BangladeshPhoneTest extends TestCase
{
    #[DataProvider('validNumbers')]
    public function test_accepts_valid_bangladesh_mobiles(string $number): void
    {
        $this->assertTrue(BangladeshPhone::isValid($number));
    }

    #[DataProvider('invalidNumbers')]
    public function test_rejects_invalid_numbers(string $number): void
    {
        $this->assertFalse(BangladeshPhone::isValid($number));
    }

    public function test_normalizes_to_local_format(): void
    {
        $this->assertSame('01712345678', BangladeshPhone::normalize('+880 1712-345678'));
        $this->assertSame('01712345678', BangladeshPhone::normalize('01712345678'));
        $this->assertSame('8801712345678', BangladeshPhone::toWhatsAppDigits('01712-345678'));
    }

    public static function validNumbers(): array
    {
        return [
            ['01712345678'],
            ['01312345678'],
            ['01912345678'],
            ['+8801712345678'],
            ['8801712345678'],
            ['01712-345678'],
            ['01712 345678'],
            ['+880 1712 345678'],
        ];
    }

    public static function invalidNumbers(): array
    {
        return [
            ['123'],
            ['abc'],
            ['01112345678'],
            ['0171234567'],
            ['017123456789'],
            ['+441712345678'],
            ['880171234567'],
        ];
    }
}
