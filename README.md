# Sinsis

A university project for a fiction enterprise. The objective was to create a professional website with a contact form and dashboard to show the data about the company sales and inventory.

The system is a CRUD to get, store, edit and delete data from the database.

## Installation

Use the package manager [composer](https://getcomposer.org/) to install all packages to run the system.

```bash
composer install
```

## Migration
To run the migrations and seeders please run the next commands.
```bash
php artisan migrate
php artisan db:seed
```
## Env
To connect the database with the system you need to create a *.env* file follow the example:
```env
DB_HOST=127.0.0.1
DB_DATABASE=sinsis
DB_USERNAME=
DB_PASSWORD=
```
## Start the server
To start the server just run the following command:
```bash
php artisan serve
```
The application will run on the port:8000 


## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
[MIT License](https://choosealicense.com/licenses/mit/)
