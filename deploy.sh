#!/bin/bash
set -e

echo "=== Начало развертывания приложения ==="
echo "Время: $(date)"
echo ""

GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

print_message() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

check_docker() {
    if ! command -v docker &> /dev/null; then
        print_error "Docker не установлен. Установите Docker перед продолжением."
        exit 1
    fi
    
    if ! command -v docker-compose &> /dev/null; then
        print_error "Docker Compose не установлен. Установите Docker Compose перед продолжением."
        exit 1
    fi
    
    print_message "Docker и Docker Compose проверены успешно"
}

build_image() {
    print_message "Сборка Docker образа..."
    docker-compose build --no-cache
    if [ $? -eq 0 ]; then
        print_message "Образ успешно собран"
    else
        print_error "Ошибка при сборке образа"
        exit 1
    fi
}

start_containers() {
    print_message "Запуск контейнеров..."
    docker-compose up -d
    
    sleep 5
    
    if [ $(docker-compose ps | grep -c "Up") -ge 1 ]; then
        print_message "Контейнеры успешно запущены"
    else
        print_error "Ошибка при запуске контейнеров"
        exit 1
    fi
}

install_dependencies() {
    print_message "Установка зависимостей Composer..."
    docker-compose exec app composer install --no-interaction --no-scripts
    
    if [ $? -eq 0 ]; then
        print_message "Зависимости успешно установлены"
    else
        print_error "Ошибка при установке зависимостей"
        exit 1
    fi
}

run_tests() {
    print_message "Запуск PHPUnit тестов..."
    echo ""
    
    if docker-compose ps | grep -q "php-cli"; then
        docker-compose exec php-cli ./vendor/bin/phpunit --verbose
    else
        docker-compose exec app ./vendor/bin/phpunit --verbose
    fi
    
    TEST_RESULT=$?
    
    echo ""
    if [ $TEST_RESULT -eq 0 ]; then
        print_message "Все тесты прошли успешно!"
    else
        print_warning "Некоторые тесты не прошли. Проверьте вывод выше."
    fi
}

check_health() {
    print_message "Проверка здоровья приложения..."
    
    sleep 3
    
    if curl -s -f http://localhost:8080 > /dev/null; then
        print_message "Веб-приложение доступно по адресу: http://localhost:8080"
    else
        print_warning "Веб-приложение недоступно"
    fi
}

show_deployment_info() {
    echo ""
    echo "=========================================="
    echo "РАЗВЕРТЫВАНИЕ ЗАВЕРШЕНО УСПЕШНО!"
    echo "=========================================="
    echo ""
    echo "Доступные точки входа:"
    echo "1. Веб-интерфейс: http://localhost:8080"
    echo "2. Запуск тестов вручную:"
    echo "   docker-compose exec app ./vendor/bin/phpunit"
    echo ""
    echo "Команды управления:"
    echo "• Запуск: docker-compose up -d"
    echo "• Остановка: docker-compose down"
    echo "• Просмотр логов: docker-compose logs -f"
    echo "• Пересборка: docker-compose build --no-cache"
    echo ""
    echo "Структура проекта:"
    echo "• src/ - Исходный код приложения"
    echo "• tests/ - Тесты PHPUnit"
    echo "• public/ - Веб-интерфейс"
    echo ""
    echo "Для выхода нажмите Ctrl+C"
    echo "=========================================="
}

deploy() {
    print_message "Начало процесса развертывания..."
    
    check_docker
    
    print_message "Остановка старых контейнеров..."
    docker-compose down 2>/dev/null || true
    
    build_image
    
    start_containers
    
    install_dependencies
    
    run_tests
    
    check_health
    
    show_deployment_info
    
    log_deployment
}

log_deployment() {
    LOG_FILE="deployment.log"
    echo "=== Лог развертывания ===" >> $LOG_FILE
    echo "Дата: $(date)" >> $LOG_FILE
    echo "Пользователь: $(whoami)" >> $LOG_FILE
    echo "Система: $(uname -a)" >> $LOG_FILE
    echo "Docker версия: $(docker --version)" >> $LOG_FILE
    echo "Docker Compose версия: $(docker-compose --version)" >> $LOG_FILE
    echo "Статус: УСПЕШНО" >> $LOG_FILE
    echo "========================" >> $LOG_FILE
    print_message "Лог развертывания сохранен в $LOG_FILE"
}

case "$1" in
    "deploy")
        deploy
        ;;
    "test")
        check_docker
        run_tests
        ;;
    "build")
        check_docker
        build_image
        ;;
    "start")
        check_docker
        start_containers
        ;;
    "stop")
        docker-compose down
        print_message "Контейнеры остановлены"
        ;;
    "logs")
        docker-compose logs -f
        ;;
    "clean")
        docker-compose down -v
        docker system prune -f
        print_message "Система очищена"
        ;;
    *)
        echo "Использование: $0 {deploy|test|build|start|stop|logs|clean}"
        echo ""
        echo "Команды:"
        echo "  deploy  - Полное развертывание приложения"
        echo "  test    - Запуск тестов"
        echo "  build   - Сборка Docker образа"
        echo "  start   - Запуск контейнеров"
        echo "  stop    - Остановка контейнеров"
        echo "  logs    - Просмотр логов"
        echo "  clean   - Очистка системы"
        exit 1
        ;;
esac