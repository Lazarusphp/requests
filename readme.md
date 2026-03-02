# LazarusPhp Requests

## What is this?

Requests is a psr-7 compatible Library Designed for sending and receiving http requests.

## How to use

the Requests Library automatically detects the request type based on the input provided ie post or get, therfore having no need to set the values.

### Installation

```php
composer require lazarusphp/requests
```

### sending data

By default the class detects and Writes the values into an array upon request, this does not require the need to create individual values.

the following example will use lazarusphp querybuilder.

```php

use LazarusPhp\Requests\Requests;
$requests = new Requests();

$query  = new QueryBuilder("users");
$query->insert(["username"=>$requests->username]);

```

### Retrieveing data

As shown above obtaining the user information can also be retrieved simply by calling `$requests->username` or `$requests->input("username")`

## Validation Options

By default apart from detecting if the value exists `Requests()` do not come with any out of the box validation `OOTB`, but can be accomplished by assigning a field rule.

These can be assigned in any order folloding 'field()'

### Assigning the rule.
```php
    $requests->field("username");
```


These can be assigned in any order folloding 'field()'

### Required value

`required()` checks if the request value is empty, an error will be thrown if this is true.

```php
$requests->field("username")->required();
```

### minimum Value
Setting a minumum value will allow the system to check if the string lenght is valid, An exception will be thrown if not.

```php
$requests->field("username")->min(3);
```

### Max Value
Setting a Maximum value will prevent the request exceeding the allocated string lenght.

An exception will be thrown if this is true.

```php
$requests->field("username")->max(3);
```

### Match Value (confirm)

The `match()` method is put in place to confirm two inputs, failure will result in an exception being thrown.

```php
$requests->field("username")->match("confirm_username");
```

As stated above all these methods can be chained and used in any order but must follow a new field request each time.

Duplicating validation methods will also throw an error.

```php

// this will work

$request->field("username")->required()->min(4)->max(20)->match("confirm_username");

// this will fail Duplicate validation Request

$requests->field("username")->required()->min(4)->match("confirm_username")->match("confirm_email");
```

## validate if Request is post or get.

Although the class automatically determines what the request is, it is recommended that any requests are also validated using the `post` or `get` methods, these values must return true or false.

```php

// Any Get Requests would be rejected by this method.
if($requests->post())
{
    echo "this is a post request";
//    Add Code here
}


// Post requests would be rejected by this method
if($requests->get())
{
    // Add Code here if the request is post"
}

```

## safe output

in certain situation such as pre submission of a form an input field may hold a value which would cause an undefined value error, this is resolved using the `$requests->safeField()` method and can be done like so.

```php
echo '<input name='username' value="'.$requests->safeField("username").'">
```

This command will output the Request if one exists other wise will defaut to an empty string 