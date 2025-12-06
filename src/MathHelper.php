<?php

class MathHelper
{
    /**
     * Сложение двух чисел
     * 
     * @param float $a Первое число
     * @param float $b Второе число
     * @return float Результат сложения
     */
    public static function add(float $a, float $b): float
    {
        return $a + $b;
    }

    /**
     * Вычитание двух чисел
     * 
     * @param float $a Первое число
     * @param float $b Второе число
     * @return float Результат вычитания
     */
    public static function subtract(float $a, float $b): float
    {
        return $a - $b;
    }

    /**
     * Умножение двух чисел
     * 
     * @param float $a Первое число
     * @param float $b Второе число
     * @return float Результат умножения
     */
    public static function multiply(float $a, float $b): float
    {
        return $a * $b;
    }

    /**
     * Деление двух чисел
     * 
     * @param float $a Делимое
     * @param float $b Делитель
     * @return float Результат деления
     * @throws DivisionByZeroError Если делитель равен 0
     */
    public static function divide(float $a, float $b): float
    {
        if ($b == 0) {
            throw new DivisionByZeroError("Деление на ноль невозможно");
        }
        
        return $a / $b;
    }

    /**
     * Проверка, является ли число четным
     * 
     * @param int $number Число для проверки
     * @return bool true если четное, false если нечетное
     */
    public static function isEven(int $number): bool
    {
        return $number % 2 == 0;
    }

    /**
     * Вычисление факториала числа
     * 
     * @param int $n Число
     * @return int Факториал числа
     * @throws InvalidArgumentException Если число отрицательное
     */
    public static function factorial(int $n): int
    {
        if ($n < 0) {
            throw new InvalidArgumentException("Факториал отрицательного числа не определен");
        }
        
        $result = 1;
        for ($i = 2; $i <= $n; $i++) {
            $result *= $i;
        }
        
        return $result;
    }
}