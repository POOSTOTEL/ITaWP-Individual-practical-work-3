<?php
require_once __DIR__ . '/../src/CurrencyConverter.php';
require_once __DIR__ . '/../src/MathHelper.php';

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тестирование PHP приложения</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .section { margin-bottom: 30px; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 3px; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Тестирование серверного приложения</h1>
        <p>Вариант 15: CurrencyConverter и MathHelper</p>
        
        <div class="section">
            <h2>Демонстрация работы CurrencyConverter</h2>
            <?php
            try {
                echo "<h3>Доступные валюты:</h3>";
                echo "<pre>";
                print_r(CurrencyConverter::getAvailableCurrencies());
                echo "</pre>";
                
                echo "<h3>Примеры конвертации:</h3>";
                
                $examples = [
                    ['amount' => 100, 'from' => 'USD', 'to' => 'EUR'],
                    ['amount' => 50, 'from' => 'EUR', 'to' => 'GBP'],
                    ['amount' => 1000, 'from' => 'GBP', 'to' => 'JPY'],
                ];
                
                foreach ($examples as $example) {
                    $result = CurrencyConverter::convert(
                        $example['amount'],
                        $example['from'],
                        $example['to']
                    );
                    echo "<p>{$example['amount']} {$example['from']} = " 
                         . number_format($result, 2) . " {$example['to']}</p>";
                }
                
                echo "<p class='success'>Конвертация выполнена успешно!</p>";
                
            } catch (Exception $e) {
                echo "<p class='error'>Ошибка: " . $e->getMessage() . "</p>";
            }
            ?>
        </div>
        
        <div class="section">
            <h2>Демонстрация работы MathHelper</h2>
            <?php
            echo "<h3>Математические операции:</h3>";
            
            echo "<p>Сложение: 2 + 3 = " . MathHelper::add(2, 3) . "</p>";
            echo "<p>Вычитание: 5 - 2 = " . MathHelper::subtract(5, 2) . "</p>";
            echo "<p>Умножение: 4 * 3 = " . MathHelper::multiply(4, 3) . "</p>";
            echo "<p>Деление: 10 / 2 = " . MathHelper::divide(10, 2) . "</p>";
            
            echo "<h3>Дополнительные функции:</h3>";
            echo "<p>Четность числа 4: " . (MathHelper::isEven(4) ? 'четное' : 'нечетное') . "</p>";
            echo "<p>Четность числа 7: " . (MathHelper::isEven(7) ? 'четное' : 'нечетное') . "</p>";
            echo "<p>Факториал 5: " . MathHelper::factorial(5) . "</p>";
            ?>
        </div>
        
        <div class="section">
            <h2>Инструкция по запуску тестов</h2>
            <pre>
# Установка зависимостей
composer install

# Запуск всех тестов
./vendor/bin/phpunit

# Запуск конкретного теста
./vendor/bin/phpunit tests/CurrencyConverterTest.php
./vendor/bin/phpunit tests/MathHelperTest.php

# Запуск с подробным выводом
./vendor/bin/phpunit --verbose

# Запуск тестов с покрытием кода (если установлен Xdebug)
./vendor/bin/phpunit --coverage-html coverage
            </pre>
        </div>
    </div>
</body>
</html>