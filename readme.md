# Laravel API Key Authentication Package

This Laravel package provides a simple API key authentication mechanism for your Laravel applications. It allows you to protect your API endpoints by validating API keys sent with each request.

## Requirements

- PHP 7.3 or higher

Tested on Laravel ^8.75

## Installation

You can install this package via Composer:

```bash
composer require ibra4/api-key
```

Next, you should publish the package's configuration file:

```bash
php artisan vendor:publish --tag=api_key
```

Then run migrations
```bash
php artisan migrate
```

This command will publish the `api_key.php` configuration file to your `config` directory.

## Configuration

After publishing the configuration file, you can modify the settings in `config/api_key.php` to fit your application's requirements. This file allows you to define various aspects of API key authentication, such as key length, expiration duration, etc.

## Usage

### Prepare your model

- Implement `HasApiKeyInterface` interface
- Use `HasApiKey` trait
  ```patch
  <?php 

  + use Ibra\ApiKey\Interfaces\HasApiKeyInterface;
  + use Ibra\ApiKey\Traits\HasApiKey;
  - class User extends Authenticatable {
  + class User extends Authenticatable implements HasApiKeyInterface {
  +    use HasApiKey;
  
  }
  ```

### Generating API Keys

To generate an API key, you can use the provided artisan command:

```bash
php artisan api_key:generate ibra 1
```

This command will generate a new API key and associate it with a App\Models\User model with id 1.

Arguments

- client_id: client's name
- id: Model id
- model <i>(optional)</i>: Model class name (default: App\Models\User)
- description <i>(optional)</i>: Description

### Protecting Routes

To protect your API routes with API key authentication, you can use the `simple_api_key` middleware provided by this package. Simply apply this middleware to the routes you want to protect:

```php
Route::middleware('simple_api_key')->get('/api/resource', 'ResourceController@index');
```

This middleware will verify the API key sent with each request and authenticate the associated user.

### Deactivating API Keys

You can deactivate an API key using the provided artisan command:

```bash
php artisan api-key:deactivate {client_id}
```

Replace `{client_id}` with the API key you want to deactivate.

### Removing API Keys

To remove an API key from the system, you can use the following artisan command:

```bash
php artisan api-key:remove {client_id}
```

Replace `{client_id}` with the API key you want to remove.

### Listing API Keys

You can list all API keys stored in the database using the following artisan command:

```bash
php artisan api-key:list
```

This command will display a list of all API keys along with their associated user and status, like.

<table>
    <thead>
        <tr>
            <th>client_id</th>
            <th>description</th>
            <th>model</th>
            <th>model_id</th>
            <th>key</th>
            <th>is_active</th>
            <th>expires_at</th>
            <th>created_at</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>ibrahim</td>
            <td>Hello World</td>
            <td>App\Models\User</td>
            <td>2</td>
            <td>6af97902bfb6f1c15fea8e079babeca731ee9fb04dd08bb7b6efb80baaed1eb6</td>
            <td>1</td>
            <td>2024-04-19T18:25:58.000000Z</td>
            <td>2024-03-20T18:25:58.000000Z</td>
        </tr>
        <tr>
            <td>lara</td>
            <td></td>
            <td>App\Models\Client</td>
            <td>1</td>
            <td>daabe8a2ed4b84f2156a12dca5b29d8aa4b8fbf4b27813aac077bdc654f57c7b</td>
            <td>0</td>
            <td>2024-04-19T18:33:15.000000Z</td>
            <td>2024-03-20T18:33:15.000000Z</td>
        </tr>
    </tbody>
</table>

## Middleware Logic

The `ApiKeyMiddleware` included in this package is responsible for authenticating API requests based on the provided API key. It checks the validity and status of the API key and logs in the associated user if the key is valid and active.

## Contributing

Contributions are welcome! If you encounter any issues or have suggestions for improvements, please feel free to open an issue or submit a pull request on GitHub.

## License

This package is open-source software licensed under the [MIT license](LICENSE.md).

## Credits

This package is developed and maintained by [Ibrahim Hammad](https://github.com/ibra4).

---

Feel free to add any additional sections or customize the content as per your project's requirements. This README provides a basic overview of the package and its usage.
