# Wildberries Parser Skeleton

Application for parsing catalog items from `search.wb.ru`

Contains:

1. Console command for parsing 1000 items per iteration and saving into ClickHouse.
2. User-Form and Table View for visualizing data from ClickHouse.

## Dependencies

- [Docker][link-docker]
- [Make][link-make]
- [PHP][link-php] v8.1
- [CakePHP][link-cake-php] v4.x
- [ClickHouse][link-clickhouse] v22.x

## Project setup

1. _Clone this repository:_

```bash
    git clone https://github.com/shirokovnv/wb_parser_example.git && cd wb_parser_example
```

2. Rename `auth.json.example` to `auth.json` and put your github oauth token there (needs for pulling `eggheads/cakephp-clickhouse` library from VCS repo)
3. Rename `./config/app_local.example.php` to `./config/app_local.php`
4. Run `make build` to build docker image
5. Run `make install` to install php dependencies
6. Run `make up` to run application containers

Visit `http://localhost/`

## ClickHouse Commands

Run `make shell` to enter inside php-app container

1. Create Table:

```bash
bin/cake click_house_init
```

2. Drop Table:

```bash
bin/cake click_house_drop
```

3. Parse products:

```bash
bin/cake parse_products "QUERY STRING"
```

## Test

1. Create `tmp` dir in project root folder
2. Run `make test` for unit-testing.

## Help

Run `make help` to see available commands.

## Shutdown

Run `make down` for shutting down application containers.

## License

MIT. Please see the [license file](LICENSE.md) for more information.

[link-php]: https://www.php.net/
[link-docker]: https://www.docker.com/
[link-make]: https://www.gnu.org/software/make/manual/make.html
[link-cake-php]: https://cakephp.org/
[link-clickhouse]: https://clickhouse.com/
