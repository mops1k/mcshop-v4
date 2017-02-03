Minecraft Shop version 4
========================

Проект магазина для серверов minecraft со встроенной системой управления контентом.

Заявленный функционал на релиз
-------------
- Новостная лента с комментированием
- Генератор статических страниц
- Гибкая система прав основанная на ролевых привилегиях
- Регистрация и авторизация новых пользователей с подтвержденией E-mail адреса
- Возможность восстановления пароля
- Пополнение счета пользователя через [InterKassa][1]
- Мультисерверная продажа статусов, предметов, регионов и т.д. через плагин [ShoppingCart][2]
- Загрузка скинов и плащей (с поддержкой HD версий)
- RCON консоль для управления серверами
- Кеширующий мониторинг серверов

**Системные требования**:
- Apache 2.4 или nginx (необязательно)
- Версия PHP 5.4 или выше (поддерживается 7 версия PHP)
- Расширения PHP: gd, pdo-mysql, mbstring, curl и все расширения, которые требуются для установки [Symfony 2.8][3]
- База данных [MySQL][4] 5.6 или выше (или [MariaDB][5] 10.1)
- Установленный [composer][6]
- acl (**только для linux**) (необязательно)

#### Установка системы
Клонировать репозиторий к себе:
```bash
git clone https://gitlab.com/mops1k/McShop-v4.git mcshop
```

Установить зависимости
```bash
cd mcshop/
composer install
```
В процессе установки зависимостей система задаст несколько вопросов для определения настроек. Соответственно Вам необходимо
ввести Ваши настройки.

Если установлен *acl* и система linux, то делаем следующее:
```bash
rm -rf app/cache/*
rm -rf app/logs/*

HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
```

Если acl не установлен делаем следующее:
```bash
rm -rf app/cache/*
rm -rf app/logs/*
chmod 0777 app/cache
chmod 0777 app/logs
```
Добавляем в файл web/app.php строку **umask(0000);**
```php
<?php

use Symfony\Component\HttpFoundation\Request;

umask(0000);

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../app/autoload.php';
include_once __DIR__.'/../app/bootstrap.php.cache';
....
```

База данных, которую Вы указали при установке зависимостей уже должна быть создана или пользователь, указанный в настройках
должен иметь право на создание баз данных.
Если пользователь имеет право на создание баз данных тогда выполняем:
```bash
php app/console doctrine:database:create
```
После этого инициализируем схему базы и загрузим в нее системные роли:
```bash
php app/console doctrine:migrations:migrate
php app/console doctrine:fixtures:load --append
```

Теперь создаем администратора в системе выполнив команду и ответив на вопросы в ней:
```bash
php app/console mc_shop:user:new --admin
```

Нам осталось почистить кеш и можно приступать к работе с системой:
```bash
php app/console cache:clear -e prod
```

Если у Вас стоит Apache или nginx, тогда необходимо настроить хост на папку web проекта.
Если же у вас не стоит ни того и ни другого, то Вы можете запустить проект командой:
```bash
php app/console server:run
```

[1]: https://www.interkassa.com/
[2]: http://rubukkit.org/threads/admn-shoppingcart-reloaded-1-2-plagin-dlja-vydachi-predmetov-iz-bd-1-4-7-1-7-2r-0-3.28052/
[3]: http://symfony.com/
[4]: https://www.mysql.com/
[5]: https://mariadb.org/
[6]: https://getcomposer.org/
