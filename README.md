# CodeigniterTwig

Integrate Twig to CodeIgniter 4.

## Installation

Install with Composer:

```shell
composer require dginanjar/codeigniter-twig
```

## Usage

### Loading Twig

Add trait to your `BaseController`:

```php
use \Dginanjar\CodeigniterTwig\Twig;
```

Inside `initController()`, add a this code to fire up Twig.

```php
$this->setupTwig();
```

### Display template

Inside your controller:

```php
$this->display('hello');
```

It will look for the file `hello.twig` inside app`/Views`.

To display the template with some variables, use second parameter:

```php
$employees = (New Employee())->findAll();

$this->display('employee/index', compact('employees'))
```

By default, Twig templates are stored in the `app/Views/`, but you can define different locations.

To do so, create a new config file `app/Config/Twig.php`.

```php
<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Twig extends BaseConfig
{
    public function __construct()
    {
        parent::__construct();
        
        $this->path = [
            APPPATH . 'Views',
            ROOTPATH . 'templates',
            // so on
        ];
    }
}
```

### Extending Twig

Here you can register global variables, filters and functions to extend Twig. For more information about extending Twig, please refer to the [Twig Documentation](https://twig.symfony.com/doc/3.x/advanced.html).

#### Global

A global variable is like any other template variable, except that it's available in all templates and macros:

```php
$this->twig->addGlobal('scatman', 'Ski Ba Bop Ba Dop Bop');
```

You can then use the `scatman` variable anywhere in a template:

```
{{ scatman }}

{# will output Ski Ba Bop Ba Dop Bop #}
```

#### Filter

Filters can be used to modify variables. Filters are separated from the variable by a pipe symbol. They may have optional arguments in parentheses. Multiple filters can be chained. The output of one filter is applied to the next.

```php
$this->twig->addFilter('str_rot13');
```

And here is how to use it in a template:

```
{{ 'Twig'|rot13 }}

{# will output Gjvt #}
```

To add multiple filters at once:

```php
$this->twig->addFilter(['foo', 'bar']);
```

or...

```php
$this->twig->addFilter([
    [
        'foo', function($foo) { return $foo; }
    ],
    ['bar'],
    'baz'
]);
```

#### Function

Filters and Functions are similar but Filters are meant to transform data.

```php
$this->addFunction('site_url');
```

Then inside template:

```
{{ 'news/local/123' }}

{# will output http://example.com/index.php/news/local/123 #}
```
