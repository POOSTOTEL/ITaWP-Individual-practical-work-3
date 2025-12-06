<?php

class CurrencyConverter
{
    private static $exchangeRates = [
        'USD' => 1.0,
        'EUR' => 0.85,
        'GBP' => 0.75,
        'JPY' => 110.0,
        'RUB' => 75.0
    ];

    /**
     * Конвертирует сумму из одной валюты в другую
     * 
     * @param float $amount Сумма для конвертации
     * @param string $fromCurrency Исходная валюта (код из 3 букв)
     * @param string $toCurrency Целевая валюта (код из 3 букв)
     * @return float Конвертированная сумма
     * @throws InvalidArgumentException Если валюта не поддерживается
     * @throws DivisionByZeroError Если курс валюты равен 0
     */
    public static function convert(float $amount, string $fromCurrency, string $toCurrency): float
    {
        $fromCurrency = strtoupper($fromCurrency);
        $toCurrency = strtoupper($toCurrency);

        if (!isset(self::$exchangeRates[$fromCurrency])) {
            throw new InvalidArgumentException("Неподдерживаемая исходная валюта: {$fromCurrency}");
        }

        if (!isset(self::$exchangeRates[$toCurrency])) {
            throw new InvalidArgumentException("Неподдерживаемая целевая валюта: {$toCurrency}");
        }

        $rateFrom = self::$exchangeRates[$fromCurrency];
        $rateTo = self::$exchangeRates[$toCurrency];

        if ($rateFrom == 0) {
            throw new DivisionByZeroError("Курс исходной валюты не может быть равен 0");
        }

        $amountInUsd = $amount / $rateFrom;
        return $amountInUsd * $rateTo;
    }

    /**
     * Добавляет новую валюту или обновляет существующий курс
     * 
     * @param string $currency Код валюты
     * @param float $rate Курс к USD
     * @throws InvalidArgumentException Если курс отрицательный
     */
    public static function addCurrency(string $currency, float $rate): void
    {
        $currency = strtoupper($currency);
        
        if ($rate < 0) {
            throw new InvalidArgumentException("Курс валюты не может быть отрицательным");
        }
        
        self::$exchangeRates[$currency] = $rate;
    }

    /**
     * Возвращает все доступные курсы валют
     * 
     * @return array Массив валют и их курсов
     */
    public static function getAvailableCurrencies(): array
    {
        return self::$exchangeRates;
    }
}