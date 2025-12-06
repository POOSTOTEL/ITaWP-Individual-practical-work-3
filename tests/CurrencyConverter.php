<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/CurrencyConverter.php';

class CurrencyConverterTest extends TestCase
{
    protected function setUp(): void
    {
        CurrencyConverter::addCurrency('USD', 1.0);
        CurrencyConverter::addCurrency('EUR', 0.85);
        CurrencyConverter::addCurrency('GBP', 0.75);
        CurrencyConverter::addCurrency('JPY', 110.0);
        CurrencyConverter::addCurrency('RUB', 75.0);
    }

    public function testConvertUsdToEur(): void
    {
        $result = CurrencyConverter::convert(100, 'USD', 'EUR');
        $this->assertEquals(85.0, $result);
    }

    public function testConvertEurToUsd(): void
    {
        $result = CurrencyConverter::convert(100, 'EUR', 'USD');
        $this->assertEqualsWithDelta(117.647, $result, 0.001);
    }

    public function testConvertWithDifferentCase(): void
    {
        $result = CurrencyConverter::convert(100, 'usd', 'eur');
        $this->assertEquals(85.0, $result);
    }

    public function testConvertSameCurrency(): void
    {
        $result = CurrencyConverter::convert(100, 'USD', 'USD');
        $this->assertEquals(100, $result);
    }

    public function testConvertZeroAmount(): void
    {
        $result = CurrencyConverter::convert(0, 'USD', 'EUR');
        $this->assertEquals(0, $result);
    }

    public function testConvertNegativeAmount(): void
    {
        $result = CurrencyConverter::convert(-100, 'USD', 'EUR');
        $this->assertEquals(-85.0, $result);
    }

    public function testInvalidFromCurrency(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Не поддерживаемая исходная валюта: XYZ');
        
        CurrencyConverter::convert(100, 'XYZ', 'USD');
    }

    public function testInvalidToCurrency(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Не поддерживаемая целевая валюта: XYZ');
        
        CurrencyConverter::convert(100, 'USD', 'XYZ');
    }

    public function testAddNewCurrency(): void
    {
        CurrencyConverter::addCurrency('CNY', 6.5);
        
        $result = CurrencyConverter::convert(100, 'USD', 'CNY');
        $this->assertEquals(650.0, $result);
    }

    public function testAddCurrencyWithNegativeRate(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Курс валюты не может быть отрицательным');
        
        CurrencyConverter::addCurrency('TEST', -1.0);
    }

    public function testAddCurrencyWithZeroRate(): void
    {
        CurrencyConverter::addCurrency('ZERO', 0.0);
        
        $this->expectException(DivisionByZeroError::class);
        CurrencyConverter::convert(100, 'ZERO', 'USD');
    }

    public function testGetAvailableCurrencies(): void
    {
        $currencies = CurrencyConverter::getAvailableCurrencies();
        
        $this->assertIsArray($currencies);
        $this->assertArrayHasKey('USD', $currencies);
        $this->assertArrayHasKey('EUR', $currencies);
        $this->assertCount(5, $currencies);
    }

    public function testConvertComplexChain(): void
    {
        $result1 = CurrencyConverter::convert(100, 'USD', 'EUR');
        $result2 = CurrencyConverter::convert($result1, 'EUR', 'GBP');
        $result3 = CurrencyConverter::convert($result2, 'GBP', 'USD');
        
        $this->assertEqualsWithDelta(100, $result3, 0.01);
    }

    /**
     * @dataProvider conversionProvider
     */
    public function testMultipleConversions(float $amount, string $from, string $to, float $expected): void
    {
        $result = CurrencyConverter::convert($amount, $from, $to);
        $this->assertEqualsWithDelta($expected, $result, 0.01);
    }

    public function conversionProvider(): array
    {
        return [
            [100, 'USD', 'EUR', 85.0],
            [50, 'EUR', 'GBP', 44.12],
            [1000, 'GBP', 'JPY', 146666.67],
            [5000, 'JPY', 'RUB', 3409.09],
        ];
    }

    public function testIncompleteTest(): void
    {
        $this->markTestIncomplete('Требуется реализация конвертации с плавающим курсом');
    }

    public function testSkippedTest(): void
    {
        $this->markTestSkipped('Пропускаем из-за проблем с API');
    }
}