#Authentication Doctrine User Repository
This is an authentication user repository intended for use with [mezzio-authentication](https://github.com/mezzio/mezzio-authentication). If you are using doctrine with your mezzio application, this will prevent you from writing redundant SQL queries or opening a new database connection. Instead, we directly fetch arrays with the entity manager.

## Installation

You can install Authentication Doctrine User Repository using Composer:

```bash
$ composer require tigerman55/authentication-doctrine-user-repository
```

## Example configuration

A complete example configuration can be found in [example/full-config.php](example/full-config.php). 
