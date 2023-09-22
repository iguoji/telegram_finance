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

## About Systemctl

```bash
vi /usr/lib/systemd/system/octane.service

[Unit]
Description=Laravel Octane Server
After=nginx.service

[Service]
ExecStart=php /home/wwwroot/telegram/finance/artisan octane:start
ExecReload=php /home/wwwroot/telegram/finance/artisan octane:reload
ExecStop=php /home/wwwroot/telegram/finance/artisan octane:stop

[Install]
WantedBy=multi-user.target


vi /usr/lib/systemd/system/queue.service

[Unit]
Description=Laravel Queue Server
After=octane.service

[Service]
ExecStart=php /home/wwwroot/telegram/finance/artisan queue:work

[Install]
WantedBy=multi-user.target
```

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
