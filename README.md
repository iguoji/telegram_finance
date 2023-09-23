## 开始运行

```bash
composer install
php artisan migrate
php artisan telegram:hook
php artisan telegram:command
php artisan queue:work

```
## 环境部署

```bash
# PHP
apt install php8.1 php8.1-bcmath php8.1-common php8.1-curl php8.1-dev php8.1-fpm php8.1-gd php8.1-gmp php8.1-mbstring php8.1-msgpack php8.1-mysql php8.1-opcache php8.1-redis php8.1-xml php8.1-yaml php8.1-zip zip unzip
systemctl enable php8.1-fpm
systemctl start php8.1-fpm

# Composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
sudo mv composer.phar /usr/local/bin/composer

# phpmyadmin
cd /home/wwwroot
composer create-project phpmyadmin/phpmyadmin
cd phpmyadmin
cp config.sample.inc.php config.inc.php

vi config.inc.php
$cfg['blowfish_secret'] = 'qq123567231qq123567231qq12356723'

mkdir tmp && chmod -R 777 tmp

# Redis
apt install redis
systemctl enable redis-server
systemctl start redis-server

# MariaDb
apt install mariadb-server
systemctl enable mariadb
systemctl start mariadb
mysql_secure_installation

# Swoole
pecl install swoole
vi /etc/php/8.1/cli/conf.d/swoole.ini
extension=swoole.so

# Nginx
apt install nginx
systemctl enable nginx
systemctl start nginx
```

## Nginx配置

> vi /etc/nginx/conf.d/finance.conf

```conf
map $http_upgrade $connection_upgrade {
    default upgrade;
    ''      close;
}

server {
    listen       80;
    server_name  nl.zidongjizhang.com;
    #return 301 https://nl.zidongjizhang.com$uri;
    #将所有HTTP请求通过rewrite指令重定向到HTTPS。
    rewrite ^(.*)$ https://$host$1;
}

server {
    listen 443 ssl;
    listen [::]:443 ssl;
    server_name nl.zidongjizhang.com;
    server_tokens off;

    #填写证书文件名称
    ssl_certificate /home/certs/nl.zidongjizhang.com.pem;
    #填写证书私钥文件名称
    ssl_certificate_key /home/certs/nl.zidongjizhang.com.key;

    ssl_session_cache shared:SSL:1m;
    ssl_session_timeout 5m;

    #自定义设置使用的TLS协议的类型以及加密套件（以下为配置示例，请您自行评估是否需要配置）
    #TLS协议版本越高，HTTPS通信的安全性越高，但是相较于低版本TLS协议，高版本TLS协议对浏览器的兼容性较差。
    ssl_ciphers ECDHE-RSA-AES128-GCM-SHA256:ECDHE:ECDH:AES:HIGH:!NULL:!aNULL:!MD5:!ADH:!RC4;
    ssl_protocols TLSv1.1 TLSv1.2 TLSv1.3;

    #表示优先使用服务端加密套件。默认开启
    ssl_prefer_server_ciphers on;

    root /home/wwwroot/telegram_finance/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location /index.php {
        try_files /not_exists @octane;
    }

    location / {
        try_files $uri $uri/ @octane;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    access_log off;
    error_log  /var/log/nginx/domain.com-error.log error;

    error_page 404 /index.php;

    location @octane {
        set $suffix "";

        if ($uri = /index.php) {
            set $suffix ?$query_string;
        }

        proxy_http_version 1.1;
        proxy_set_header Host $http_host;
        proxy_set_header Scheme $scheme;
        proxy_set_header SERVER_PORT $server_port;
        proxy_set_header REMOTE_ADDR $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection $connection_upgrade;

        proxy_pass http://127.0.0.1:8000$suffix;
    }
}
```

> vi /etc/nginx/conf.d/phpmyadmin.conf

```conf
server {
    listen 33066;
    server_name nl_zidongjizhang.com;
    root /home/wwwroot/phpmyadmin;
    index index.php index.html index.htm;

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include /etc/nginx/fastcgi_params;
        fastcgi_pass unix:/var/run/php/php-fpm.sock;
    }
}
```

## Systemctl

> vi /usr/lib/systemd/system/octane.service

```conf
[Unit]
Description=Laravel Octane Server
After=php8.1-fpm.service

[Service]
ExecStart=php /home/wwwroot/telegram_finance/artisan octane:start
ExecReload=php /home/wwwroot/telegram_finance/artisan octane:reload
ExecStop=php /home/wwwroot/telegram_finance/artisan octane:stop

[Install]
WantedBy=multi-user.target
```

> vi /usr/lib/systemd/system/queue.service

```conf
[Unit]
Description=Laravel Queue Server
After=octane.service

[Service]
ExecStart=php /home/wwwroot/telegram_finance/artisan queue:work

[Install]
WantedBy=multi-user.target
```

## Docker

```bash
# Add Docker's official GPG key:
sudo apt-get update
sudo apt-get install ca-certificates curl gnupg
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg

# Add the repository to Apt sources:
echo \
  "deb [arch="$(dpkg --print-architecture)" signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
  "$(. /etc/os-release && echo "$VERSION_CODENAME")" stable" | \
  sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt-get update

sudo apt-get install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
```

## Telegram-bot-api

> 从 https://my.telegram.org/apps 得到 api_id 和 api_hash

```bash
docker run -d -p 8081:8081 --name=telegram-bot-api --restart=always -v telegram-bot-api-data:/var/lib/telegram-bot-api -e TELEGRAM_API_ID=<api_id> -e TELEGRAM_API_HASH=<api-hash> aiogram/telegram-bot-api:latest
```

## Update Json

- 私聊指令

```json
{
	"update_id": 18761354,
	"message": {
		"message_id": 341,
		"from": {
			"id": 6352088817,
			"is_bot": false,
			"first_name": "日行一善",
			"username": "emituofooo",
			"language_code": "zh-hans"
		},
		"chat": {
			"id": 6352088817,
			"first_name": "日行一善",
			"username": "emituofooo",
			"type": "private"
		},
		"date": 1695295264,
		"text": "/start",
		"entities": [{
			"offset": 0,
			"length": 6,
			"type": "bot_command"
		}]
	}
}
```

- 私聊普通

```json
{
	"update_id": 18761355,
	"message": {
		"message_id": 344,
		"from": {
			"id": 6352088817,
			"is_bot": false,
			"first_name": "日行一善",
			"username": "emituofooo",
			"language_code": "zh-hans"
		},
		"chat": {
			"id": 6352088817,
			"first_name": "日行一善",
			"username": "emituofooo",
			"type": "private"
		},
		"date": 1695295354,
		"text": "hello"
	}
}
```

- 群聊指令

```json
{
	"update_id": 18761356,
	"message": {
		"message_id": 50,
		"from": {
			"id": 6352088817,
			"is_bot": false,
			"first_name": "日行一善",
			"username": "emituofooo",
			"language_code": "zh-hans"
		},
		"chat": {
			"id": -1001908192385,
			"title": "日行一善 & 自动记账机器人",
			"type": "supergroup"
		},
		"date": 1695295409,
		"text": "/start",
		"entities": [{
			"offset": 0,
			"length": 6,
			"type": "bot_command"
		}]
	}
}
```
