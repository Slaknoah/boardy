# Boardy
Boardy - менеджер контактов

## Фреймворки
- Laravel 8.12
- NuxtJS 2.15.3

## Установка
Перед установкой убедитесь, что на вашем компьютере установлено следующее
- Docker 
- Node JS

```bash
# Клонировать проект
$ git clone git@github.com:Slaknoah/boardy.git

# Перейти в папку проекта
$ cd boardy

# Получить подмодули
$ git submodule update --init --recursive
```
#### Установка API
```bash
# Перейти в папку api (.../boardy/api)
$ cd api

# Копировать файл .env
$ cp .env.example .env

# Перейти в папку laradock (.../boardy/api/laradock)
$ cd laradock

# Копировать файл .env
$ cp env-example .env

# Запустить докер
$ docker-compose up -d nginx postgres
$ docker-compose exec workspace bash
```
Введите следующие команды в открывшемся bash
```bash
# Установить ( dependencies )
$ composer install

# Миграция
$ php artisan migrate:fresh --seed

$ exit
```

[Документация](https://documenter.getpostman.com/view/5709349/TzJybvYE) 
#### Установка Frontend
```bash
# Перейти в папку frontend  (.../boardy/frontend)
$ cd frontend

# Копировать файл .env
$ cp env-example .env

# Установить ( dependencies )
$ npm install

# Запуск
$ npm run dev
```
Загрузить [Boardy](http://localhost:4000)

### Демо-пользователь
- Эл. адрес: user@mail.com
- Пароль: password

## Лицензия

[MIT](https://opensource.org/licenses/MIT)