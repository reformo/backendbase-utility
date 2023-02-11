# Backendbase Stdlib

[![Build Status](https://travis-ci.com/reformo/backendbase-utility.svg?branch=master)](https://travis-ci.com/reformo/backendbase-utility) [![Coverage Status](https://coveralls.io/repos/github/reformo/backendbase-utility/badge.svg?branch=master)](https://coveralls.io/github/reformo/backendbase-utility?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/reformo/backendbase-utility/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/reformo/backendbase-utility/?branch=master)

Standard Library for Backendbase and other libraries.



## EqualsBuilder

This class provides methods to build a equals method for any class. Intended to be use to compare Value Objects.

```php
<?php 
declare(strict_types=1);

use Backendbase\Stdlib\EqualsBuilder;

class SomeValueObject()
{
	private $value1;
	private $value2;
	
	public function __construct(string $value1, string $value2)
	{
		$this->value1 = $value1;
		$this->value2 = $value2;
	}
	
	public function getValue1() :string 
	{
		return $value1;
	}
	
	public function getValue2() : string
	{
		return $value2;
	}
	
	public function equals($otherObject) : bool
	{
		return EqualsBuilder::create()
			->append($this->value1, $otherObject->getValue1())
			->append($this->value2, $otherObject->getValue2())
        	->isEquals(); 
	}
}
```


## Resolver


This class provides a method to obtain typehints of a method. Intended to be used to autowire classes.


```php
<?php
declare(strict_types=1);

use Backendbase\Stdlib\Resolver;

class BlogService {}

class Controller
{
	private $argument;
	private $service;
	public function __construct(BlogService $service, array $argument)
	{
		$this->service = $service;
		$this->argument = $argument;
	}
}

$arguments = Resolver::getParameterHints(Controller::class, '__construct');

var_dump($arguments);

/* Prints 
array(2) {
  ["service"]=>
  string(11) "BlogService"
  ["argument"]=>
  string(5) "array"
}
*/
```

## CaseConverter

This class provides methods to convert strings to camelCase, PascalCase or snake_case string.


```php
<?php
declare(strict_types=1);

use Backendbase\Stdlib\CaseConverter;

$source = 'test string';
$result = CaseConverter::toCamelCase($source); // returns: testString
$result = CaseConverter::toPascalCase($source); // returns: TestString
$result = CaseConverter::toSnakeCase($source); // returns: test_string
```

## Git\Version

This class provides a methods to get short version of git. For deployments using git, it can be used to give version numbers to JS, CSS files to ensure to cache updated version of these files.


```php
<?php // common.php

declare(strict_types=1);

use Backendbase\Stdlib\Git\Version;

$gitVersion = Version::short();

$twig->addGlobal('version', $gitVersion);

```
```html
<!-- main.twig -->
<html>
<head>
    <link href="/assets/css/main.css?v={{version}}" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/assets/js/main.min.js?v={{version}}"></script>
</head>
<body>
...
</body>
</html>
```


## BaseUrlExtractor


This class provides a methods to get base url where applications runs. 

Caution: This method may not return real base url if you are behind some services like Cloudflare and when you use Flexible SSL feature.

```php
<?php
declare(strict_types=1);

use Backendbase\Stdlib\BaseUrlExtractor;

$baseUrl = BaseUrlExtractor::getBaseUrl($_SERVER);

echo $baseUrl;

/* Prints base url like:
http://127.0.0.1:8080

http://127.0.0.1:8080/myapp

https://myapp.com
*/
```
