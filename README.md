## ODBC integration for Laravel Framework using IBM Informix Database
This integration allows the use of <b>odbc_*</b> php function with Laravel framework instead of PDO.<br>
It emulates PDO class used by Laravel.<br>
This is a fork of the project abram/laravel-odbc, but customized for informix.


### # How to install
> `composer require mkrohn/laravel-odbc-informix` To add source in your project

### # Usage Instructions
It's very simple to configure:

**1) Add database to database.php file**
```PHP
'odbc-connection-name' => [
    'driver' => 'odbc',
    'dsn' => 'OdbcConnectionName',
    'database' => 'DatabaseName',
    'odbc' => true,
    'host' => '127.0.0.1',
    'username' => 'username',
    'password' => 'password'
    'options' => [
        'processor' => Mkrohn\Odbc\Informix\Query\Processors\InformixProcessor::class,
        'grammar' => [
            'query' => Mkrohn\Odbc\Informix\Query\Grammars\InformixGrammar::class,
            'schema' => Mkrohn\Odbc\Informix\Schema\Grammars\InformixGrammar::class
        ]
    ]
]
```

### # Eloquent ORM
You can use Laravel, Eloquent ORM and other Illuminate's components as usual.
```PHP
# Facade
$books = DB::connection('odbc-connection-name')->table('books')->where...;

# ORM
$books = Book::where...->get();
```


