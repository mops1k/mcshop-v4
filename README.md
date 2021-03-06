Minecraft Shop version 4 (McShop v4)
====================================

Проект магазина для серверов minecraft со встроенной системой управления контентом.

```
Тестовое окружение minecraft:
1. лаунчер Sashok724 v3
2. сервер Spigot 1.9.4
```

Реализованный функционал
-------------
- Новостная лента с комментированием
- Генератор статических страниц
- Гибкая система прав основанная на ролевых привилегиях
- Регистрация и авторизация новых пользователей с подтверждением E-mail адреса
- Возможность восстановления пароля
- Пополнение счета пользователя через [InterKassa][1]
- Пополнение счета пользователя кодом активации купона
- Генерация купонов
- Мультисерверная продажа статусов, предметов, регионов и т.д. через плагин [ShoppingCart][2]
- Сохранение и просмотр истории покупок
- Загрузка скинов и плащей (с поддержкой HD версий)
- RCON консоль для управления серверами
- Кеширующий мониторинг серверов
- Общие настройки системы

**Системные требования**:
- Apache 2.4 или nginx (необязательно)
- Версия PHP 7.1.3 или выше
- Расширения PHP: gd, pdo-mysql, mbstring, curl и все расширения, которые требуются для установки [Symfony 3.4][3]
- База данных [MySQL][4] 5.6 или выше (или [MariaDB][5] 10.1)
- Установленный [composer][6]
- acl (**только для linux**) (необязательно)

#### Установка системы
Клонировать репозиторий к себе:
```bash
git clone https://github.com/mops1k/mcshop-v4 mcshop
```

База данных, которую Вы указажете при установке зависимостей уже должна быть создана или пользователь, указанный в настройках
должен иметь право на создание баз данных.

Установка зависимостей:
```bash
cd mcshop/
git checkout 1.1.0
composer install
```
В процессе установки зависимостей система задаст несколько вопросов для определения настроек. Соответственно Вам необходимо
ввести Ваши настройки. Сменить эти настройки позже, вы смжете в файле `app/config/parameters.yml` вручную.

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

Теперь создаем администратора в системе выполнив команду и ответив на вопросы в ней:
```bash
php bin/console mc_shop:user:new --admin
```

Нам осталось почистить кеш и можно приступать к работе с системой:
```bash
php bin/console cache:clear -e prod
```

Теперь Вам необходимо настроить хост на папку `public_html` проекта.

### Пример конфигурации в лаунчере Sashok724 v3 (Open Source)
```yaml
# LaunchServer.cfg
address: "localhost";
bindAddress: "0.0.0.0";
port: 7240;

# Auth handler
authHandler: "mysql";
authHandlerConfig: {
        fetchAll: true;

        address: "localhost";
        port: 3306;
        username: "user";
        password: "password";
        database: "database"; # База данных

        table: "user";
        uuidColumn: "uuid";
        usernameColumn: "username";
        accessTokenColumn: "access_token";
        serverIDColumn: "server_id";
};

# Auth provider
authProvider: "request";
authProviderConfig: {
        url: "http://localhost/en/user/minecraft/%login%/%password%";
        response: "OK:(?<username>.+)";
};

# Texture provider
textureProvider: "request";
textureProviderConfig: {
        skinsURL: "http://localhost/minecraft/skins/%uuid%.png";
        cloaksURL: "http://localhost/minecraft/cloacks/%uuid%.png";
};

# Launch4J EXE binary building
launch4J: false;

# Compress files when updating using Inflate algorithm
compress: true;
```

[1]: https://www.interkassa.com/
[2]: http://rubukkit.org/threads/admn-shoppingcart-reloaded-1-2-plagin-dlja-vydachi-predmetov-iz-bd-1-4-7-1-7-2r-0-3.28052/
[3]: http://symfony.com/
[4]: https://www.mysql.com/
[5]: https://mariadb.org/
[6]: https://getcomposer.org/
