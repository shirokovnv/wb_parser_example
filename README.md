# Wildberries Parser Skeleton

## Dependencies

- [Docker][link-docker]
- [Make][link-make]
- [PHP][link-php] v8.1
- [CakePHP][link-cake-php] v4.x
- [Clickhouse][link-clickhouse] v22.x

## Project setup

1. clone this repository:

```bash
    git clone https://github.com/shirokovnv/wb_parser_example.git && cd wb_parser_examle
```

2. Rename `auth.json.example` to `auth.json` and put your github oauth token there
3. run `make build` to build docker image
4. run `make install` to install php dependencies
5. run `make up` to run application containers

Visit `http://localhost:8080/`

Run `make down` to shutting down application containers.

Run `make` to see available commands.

## License

MIT. Please see the [license file](LICENSE.md) for more information.

[link-php]: https://www.php.net/
[link-docker]: https://www.docker.com/
[link-make]: https://www.gnu.org/software/make/manual/make.html
[link-cake-php]: https://cakephp.org/
[link-clickhouse]: https://clickhouse.com/
