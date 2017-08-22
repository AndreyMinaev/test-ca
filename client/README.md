# Клиент приложения
Клиент предстовляет из себя набор статики, и, как следствие, требует минимум установки

## Требования
1. Веб сервер, который может хостить статические файлы.

## Установка
1. скопируйте файлы проекта
```
  git clone https://github.com/AndreyMinaev/test-ca.git
  cd test-ca/client
```
2. создайте конфиг файл и укажите соответсвующие [настройки](#Настройки)
```
  cp config.js.example config.js
```
3. запустите веб сервер
```
  python -m SimpleHTTPServer 8000
```

## Настройки
| поле | описание |
| ---- | -------- |
| baseUrl | url адрес сервера апи |