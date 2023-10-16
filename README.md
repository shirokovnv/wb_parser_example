# Wildberries Parser Skeleton

## Dependencies

- [Docker][link-docker]
- [Make][link-make]
- [PHP][link-php] v8.1
- [CakePHP][link-cake-php] v4.x
- [Clickhouse][link-clickhouse] v22.x

## Project setup

1. _Clone this repository:_

```bash
    git clone https://github.com/shirokovnv/wb_parser_example.git && cd wb_parser_examle
```

2. Rename `auth.json.example` to `auth.json` and put your github oauth token there (needs for pulling `eggheads/cakephp-clickhouse` library from VCS repo)
3. Rename `./config/app_local.example.php` to `./config/app_local.php`
4. Run `make build` to build docker image
5. Run `make install` to install php dependencies
6. Run `make up` to run application containers

Visit `http://localhost:8080/`

**Clickhouse commands:**

1. Run `make shell` to enter inside php-app
2. Run `bin/cake click_house_init` to create DB table
3. Run `bin/cake click_house_drop` to drop DB table

Run `make down` to shutting down application containers.

Run `make` to see available commands.

## License

MIT. Please see the [license file](LICENSE.md) for more information.

[link-php]: https://www.php.net/
[link-docker]: https://www.docker.com/
[link-make]: https://www.gnu.org/software/make/manual/make.html
[link-cake-php]: https://cakephp.org/
[link-clickhouse]: https://clickhouse.com/
