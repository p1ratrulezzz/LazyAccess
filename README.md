LazyAccess
==========

[![Latest Stable Version](https://poser.pugx.org/p1ratrulezzz/lazyaccess/v/stable)](https://packagist.org/packages/p1ratrulezzz/lazyaccess)
[![Latest Unstable Version](https://poser.pugx.org/p1ratrulezzz/lazyaccess/v/unstable)](https://packagist.org/packages/p1ratrulezzz/lazyaccess)
[![Total Downloads](https://poser.pugx.org/p1ratrulezzz/lazyaccess/downloads)](https://packagist.org/packages/p1ratrulezzz/lazyaccess)
[![License](https://poser.pugx.org/p1ratrulezzz/lazyaccess/license)](https://packagist.org/packages/p1ratrulezzz/lazyaccess)


LazyAccess is a wrapper around any arrays. Provides easy way of getting it's values or get a default value instead.

Replaces stupid long constructions like 
```php
isset($var) ? $var : NULL.
```    
# Installation

## Using composer

Update your composer.json with following:
```json
    require: {
        "p1ratrulezzz/lazyaccess": "master"
    }
```
and run 
```bash
composer install  
```
or (recommended)
```bash
composer require p1ratrulezzz/lazyaccess master
```
Second method will allow you to install this package without manual changes in composer.lock file.

## Manual installation
```bash
git clone --branch master https://github.com/p1ratrulezzz/LazyAccess-to-PHP-arrays.git lazyaccess
```    
Then in PHP code include the files
```php
require_once 'lazyaccess/src/LazyAccess.php';
require_once 'lazyaccess/src/LazyAccessTyped.php';
```
# Description

For example:
  usual PHP code is 
```php
$somevar = isset($array[$key]['key2'][0]) ? $array[$key]['key2'][0] : 'some_default_value';
```
This code is long and duplicates same things. 
With LazyAccess same code will be
```php  
$wrapper = new LazyAccessTyped($array); //Define it once somewhere in your code
$somevar = $array[$key]->key2[0]->value('some_default_value');
//or
$somevar = $array[$key]['key2'][0]->value('some_default_value'); //the same as the above
//or
$somevar = $array->$key->key2->0->value('some_default_value'); //the same as the above
// Also there are some wrappers with types: asString(), asInteger(), asDouble()
$somevar = $array->{$key}->key2->0->asString('some_default_value');
$somevar = $array->{$key}->key2->0->asInteger(0); // It will perform intval() operation before returning, so you can be sure that there will be an integer value.
// asDouble() also will replace comma "," to a point ".", for example value 1,93 will be converted to 1.93
$floating_point_value = new LazyAccessTyped(['test_float' => ['inner' => '1,93']])->test_float->inner->asDouble(0); // Will return 1.93
```
    
It provides ability to use array operator ("[]") or object operator ("->") to access nesting array elements!

# Note

There are two classes LazyAccess and LazyAccessTyped. LazyAccessTyped provides ability to use converters such as asFloat(), asInteger() and etc.

Please, do not use LazyAccess, cause it can behave unpredictible with it's return value. LazyAccessTyped is much better and safer.
