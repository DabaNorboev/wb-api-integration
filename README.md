# WB Parser

### Тестовое задание

#### Бд вышла за лимиты, поэтому создана новая.

## Стек технологий

- **PHP** 8.3
- **Laravel** 12
- **MySQL** 8.0 (filess.io free plan 10mb)
- **Docker** / docker-compose

## Установка и запуск

1. Клонировать репозиторий:
   ```bash
   git clone <https://github.com/DabaNorboev/wb-parser>
   cd wb-parser
   ```
   
2. Запустить контейнеры:
    ```bash
    docker-compose up -d
   ```
   
3. Подключиться к контейнеру:
    ```bash
   docker-compose exec parser bash
   ```
   
4. Установить зависимости:
    ```bash
   composer install
   ```
   
5. Выполнить миграции:
    ```bash
    php artisan migrate
   ```
   
## Загрузка данных из API
- Команды для получения данных (период можно указать опцией --days):
    ```bash
    # Продажи (по умолчанию 30 дней)
    php artisan fetch:sales
    
    # Заказы (по умолчанию 30 дней)
    php artisan fetch:orders
    
    # Доходы (по умолчанию 30 дней)
    php artisan fetch:incomes
  
    # Остатки на складах (только сегодня)
    php artisan fetch:stocks
    ```
  
- Период можно указать опцией --days, например:
    ```bash
    php artisan fetch:sales --days=14
    ```

## Доступы к базе данных

| Параметр | Значение                                   |
|----------|--------------------------------------------|
| Хост | `vb2f8t.h.filess.io`                       |
| Порт | `61001`                                    |
| База данных | `wb_data_tightwhyif`                       |
| Пользователь | `wb_data_tightwhyif`                       |
| Пароль | `d3e0a996ab6332fc8b0ad9eb6c528e719b9d2c87` |

## Структура базы данных

| Таблица | Назначение | Уникальный ключ |
|---------|------------|-----------------|
| `sales` | Продажи | `sale_id` |
| `orders` | Заказы | `g_number` |
| `stocks` | Остатки на складах | `barcode` + `warehouse_name` + `date` |
| `incomes` | Доходы | `income_id` |

### Эндпоинты

| Эндпоинт | Параметры |
|----------|-----------|
| `/api/sales` | `dateFrom`, `dateTo` |
| `/api/orders` | `dateFrom`, `dateTo` |
| `/api/stocks` | `dateFrom` (текущий день) |
| `/api/incomes` | `dateFrom`, `dateTo` |


