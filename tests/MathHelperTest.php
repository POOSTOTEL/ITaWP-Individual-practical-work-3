<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/MathHelper.php';

class MathHelperTest extends TestCase
{
    public function testAdd(): void
    {
        $this->assertEquals(5, MathHelper::add(2, 3));
        $this->assertEquals(0, MathHelper::add(-2, 2));
        $this->assertEquals(1.5, MathHelper::add(1, 0.5));
    }

    public function testSubtract(): void
    {
        $this->assertEquals(1, MathHelper::subtract(3, 2));
        $this->assertEquals(-1, MathHelper::subtract(2, 3));
        $this->assertEquals(0.5, MathHelper::subtract(1, 0.5));
    }

    public function testMultiply(): void
    {
        $this->assertEquals(6, MathHelper::multiply(2, 3));
        $this->assertEquals(0, MathHelper::multiply(2, 0));
        $this->assertEquals(-6, MathHelper::multiply(2, -3));
    }

    public function testDivide(): void
    {
        $this->assertEquals(2, MathHelper::divide(6, 3));
        $this->assertEquals(0.5, MathHelper::divide(1, 2));
        $this->assertEquals(-2, MathHelper::divide(6, -3));
    }

    public function testDivideByZero(): void
    {
        $this->expectException(DivisionByZeroError::class);
        $this->expectExceptionMessage('Деление на ноль невозможно');
        
        MathHelper::divide(5, 0);
    }

    public function testIsEven(): void
    {
        $this->assertTrue(MathHelper::isEven(2));
        $this->assertTrue(MathHelper::isEven(0));
        $this->assertFalse(MathHelper::isEven(3));
        $this->assertFalse(MathHelper::isEven(-1));
    }

    public function testFactorial(): void
    {
        $this->assertEquals(1, MathHelper::factorial(0));
        $this->assertEquals(1, MathHelper::factorial(1));
        $this->assertEquals(120, MathHelper::factorial(5));
    }

    public function testFactorialNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Факториал отрицательного числа не определен');
        
        MathHelper::factorial(-5);
    }

    /**
     * @dataProvider additionProvider
     */
    public function testAddWithDataProvider(float $a, float $b, float $expected): void
    {
        $this->assertEquals($expected, MathHelper::add($a, $b));
    }

    public function additionProvider(): array
    {
        return [
            [1, 1, 2],
            [0, 0, 0],
            [-1, 1, 0],
            [2.5, 2.5, 5.0],
        ];
    }

    public function testAssertContains(): void
    {
        $numbers = [1, 2, 3, 5, 8];
        $this->assertContains(5, $numbers);
        $this->assertNotContains(4, $numbers);
    }
}