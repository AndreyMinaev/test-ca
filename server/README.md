# Апи сервер приложения
Сервер при помощи которого клиент приложения осуществляет взаимодействие с данными

## Требования
1. PHP >= 5.4.0
2. composer 1.2.0
3. MySQL 14.14

## Установка
1. скопируйте файлы проекта
```
  git clone https://github.com/AndreyMinaev/test-ca.git
  cd test-ca/server
```
2. создайте конфиг файл и укажите соответсвующие [настройки](#Настройки)
```
  cp application/configs/local.ini.example application/configs/local.ini
```
3. установите зависимости
```
  composer install
```
4. проведите миграции данных
```
  php vendor/bin/zf.php update database-schema production
```
5. запустите веб сервер. пример:
```
  php -S localhost:8000
```

## Настройки
| поле | описание |
| ---- | -------- |
| resources.db.adapter | Адаптер |
| resources.db.username | имя пользователя для подключения |
| resources.db.password | пароль пользователя |
| resources.db.host | адрес сервера базы данных |
| resources.db.dbname | имя базы |

## Доступные ресурсы
### rates
| метод | урл | тело | описание |
| ----- | --- | ---- | -------- |
| GET | /rates | | возвращает список всех валют |
| GET | /rates/:id | | возвращает валюту |
| PUT | /rates/:id/toggle | `{ data: bool }` | устанавливает статус отображения |
| GET | /rates/refresh | | обновляет список валют и возвращает актуальный |