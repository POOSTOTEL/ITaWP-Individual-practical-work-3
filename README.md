# Тестирование серверного приложения с помощью PHPUnit

## Вариант 15: CurrencyConverter и MathHelper

### Описание проекта
Проект демонстрирует принципы модульного тестирования серверного приложения на PHP с использованием PHPUnit. Реализованы два класса с тестами, покрывающими основную функциональность и обработку ошибок.

### Структура проекта
```
├── src/ # Исходный код
│ ├── CurrencyConverter.php
│ └── MathHelper.php
├── tests/ # Тесты PHPUnit
│ ├── CurrencyConverterTest.php
│ └── MathHelperTest.php
├── public/ # Веб-интерфейс
│ └── index.php
├── composer.json # Зависимости Composer
├── phpunit.xml # Конфигурация PHPUnit
├── Dockerfile # Конфигурация Docker
├── docker-compose.yml # Docker Compose конфигурация
├── deploy.sh # Скрипт автоматического развертывания
└── README.md # Документация
```
### Требования
- PHP 7.4 или выше
- Composer
- Docker и Docker Compose (для контейнеризации)

### Установка и запуск

#### Способ 1: Локальная установка
```bash
# Клонирование репозитория
git clone <repository-url>
cd project

# Установка зависимостей
composer install

# Запуск тестов
./vendor/bin/phpunit

# Запуск веб-приложения
php -S localhost:8000 -t public
```
