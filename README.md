# WB API integration

Импорт данных из API Wildberries: sales, stocks, incomes, orders.

## Стек

- PHP 8.3, Laravel 12, MySQL 8.0, docker

## Установка

```bash
git clone git@github.com:DabaNorboev/wb-api-integration.git
cd wb-api-integration
docker-compose up -d --build
docker exec wb_php composer install
docker exec wb_php php artisan migrate --seed
```

## Команды

### Добавление сущностей

```bash
php artisan add:company
php artisan add:account
php artisan add:api-service
php artisan add:token-type
php artisan add:token
```

### Загрузка данных

```bash
php artisan fetch:sales --days=100
php artisan fetch:orders --days=90
php artisan fetch:incomes --days=80
php artisan fetch:stocks
```

Опция `--days` задаёт период загрузки (по умолчанию 120). Загружаются только свежие данные.

### Тестовые данные

```bash
php artisan migrate:fresh --seed
```

Создаёт компанию, 5 аккаунтов и API-сервис Wildberries с типом токена `api-key`. Все аккаунты получают идентичный токен.

## Расписание

Ежедневное обновление данных дважды в день, время по МСК. Настроено через `wb_scheduler` контейнер.

| Команда           | Первый запуск | Второй запуск |
|-------------------|---------------|---------------|
| `fetch:incomes`   | 02:00         | 14:00         |
| `fetch:orders`    | 02:30         | 14:30         |
| `fetch:sales`     | 03:00         | 15:00         |
| `fetch:stocks`    | 03:30         | 15:30         |

Часовой пояс при желании можно изменить в 'docker-compose.yml'

```yaml
environment:
  - TZ=Europe/Moscow
```

## Структура БД

| Таблица        | Назначение   | Уникальный ключ                                      |
|----------------|--------------|------------------------------------------------------|
| `companies`    | Компании     | `id`                                                 |
| `accounts`     | Аккаунты     | `id`                                                 |
| `api_services` | API-сервисы  | `id`                                                 |
| `token_types`  | Типы токенов | `id`                                                 |
| `tokens`       | Токены       | `account_id` + `api_service_id`                      |
| `sales`        | Продажи      | `sale_id` + `account_id`                             |
| `orders`       | Заказы       | `g_number` + `account_id`                            |
| `stocks`       | Остатки      | `barcode` + `warehouse_name` + `date` + `account_id` |
| `incomes`      | Доходы       | `income_id` + `account_id`                           |
```
