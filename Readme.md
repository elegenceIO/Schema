# Schema Builder

## Installation

```php
composer install elegenceio/database

```

## What is it.

Elegence io Database Schema Builder is a chainable Schema Orm system desined to Build and update any database schema Records.


## Building the inital Schema

below is the base structure for creating all blueprint table entries;

this readme guide will build and process a user table with the following detail.

* id
* username
* email
* password
* created_at
* updated_at
* validated

```php
Schema::create(string:$tablename,callable:function($blueprint)
{

});
```

* <u><b>Numeric Entries</b></u>
    * `tinyInt()`
        * Creates a Tiny integer datatype
        * `$blueprint->tinyInt($name)`
        * default length : 1 character
        * Required Parameters : `$name`
        * Output : `$name TINYINT(1)`
    * `int()`
        * Creates a standard integer datatype
        * `$blueprint->int($name)`
        * default length : 8 character
        * Required Parameters : `$name`
        * Output : `$name TINYINT(1)`
    * `bigInt()`
        * Creates a big Integer datatype
        * `$blueprint->bigInt($name)`
        * default length : 50 character
        * Required Parameters : `$name`
        * Output : `$name BIGINT(1)`
<hr>

* <b><u>String Entries</u></b>

    * `string()`
        * Creates a char datatype
        * `$blueprint->string($name,$lenght)`
        * default length : 100 character (optional)
        * Required Parameters : `$name`
        * Output : `$name CHAR(100)`

    * `varchar()`
         * Creates a Varchar datatype
        * `$blueprint->string($name,$lenght)`
        * default length : 255 character (optional)
        * Required Parameters : `$name`
        * Output : `$name VARCHAR(100)`

     * `text()`
        * Creates a Text datatype
        * `$blueprint->text($name)`
        * Required Parameters : `$name`
        * Output : `$name TEXT` 
    * `mediumText()`
        * Creates a Text datatype
        * `$blueprint->mediumText($name)`
        * Required Parameters : `$name`
        * Output : `$name MEDIUMTEXT`
    * `longText()`
        * Creates a Long Text datatype
        * `$blueprint->longText($name)`
        * Required Parameters : `$name`
        * Output : `$name LONGTEXT` 
<hr>

* <b><u>Date Entries</u></b>
    * `date()`
        * Creates a Date datatype
        * `$blueprint->date($name)`
        * Required Parameters : `$name`
        * Output : `$name DATE` 
    * `datetime()`
        * Creates a Date Time datatype
        * `$blueprint->datetime($name)`
        * Required Parameters : `$name`
        * Output :  `$name DATETIME` 
<hr>

<hr>

* <b><u>Attribues</u></b>

    * `nullable()`
        * Allows database to recieve and empty field,
        * `$blueprint->nullable()`
        * Output : `NULL`
        
    * `notNullable()`
        * disallows the database to recieve and empty field,
        * `$blueprint->nullable(false)`
        * '$blueprint->notNullable()',
        * Output : `NOT NULL`
    * `default()`
        * Sets a default value to a field,
        * `$blueprint->default("mike");
        * "Output : `DEFAULT 'mike'`
        * Note : Cannot set a default value  in conjunction with  `ai()` due to auto incrmenting column,


<hr>

* <b><u>Pre-defined Entries</u></b>
    * `id()`
        * Automatially generates an an id column
        * automatically implements auto increment
        * Automatically Applies Primarykey to column
        * Internal Code : `$blueprint->int("id")->ai()->primary()`
        * Output :`id INT(8) AUTO_INCRMENT, PRIMARY KEY(id)`
    * `ai()`
        * sets column to auto increment as well as setting the column to primary.
        * Output : `AUTO_INCRMENT, PRIMARY KEY(id)`

<hr>

* <b><u>Indexing</u></b>
    * `Index()` 
        * Sets an index key to a column or columns
        * can be individually chained off current column : `$blueprint->string("username")->index()`
        * Manually adding : `$blueprint->index($name,$cols)`
        * Params (name) : `string ($name)`
        * Params (Columns) : `array ($cols)`
        * Outputs : `KEY $name ($cols)`
    * `Unique()`
        * Sets an Unique key to a column or columns
        * can be individually chained off current column : `$blueprint->string("username")->unique()`
        * Manually adding : `$blueprint->unique($name,$cols)`
        * Params (name) : `string ($name)`
        * Params (Columns) : `array ($cols)`
        * Outputs : `UNIQUE KEY $name ($cols)`
    * `fulltext`
        * Sets an Full text key to a column or columns
        * can be individually chained off current column : `$blueprint->string("username")->fulltext()`
        * Manually adding : `$blueprint->fulltext($name,$cols)`
        * Params (name) : `string ($name)`
        * Params (Columns) : `array ($cols)`
        * Outputs : `FULLTEXT KEY $name ($cols)`
    * `Primary()`
        * Sets an Primary key to a column or columns
        * can be individually chained off current column : `$blueprint->string("username")->primary()`
        * Manually adding : `$blueprint->primary($cols)`
        * Params (Columns) : `array ($cols)`
        * Outputs : `PRIMARY KEY ($cols)`

<hr>

 * <u><b>Foreign keys and Constraints</b></u>
    
    * `ForeignKey()` && `reference()`
        * Sets up an initial foreign table requires `reference()`
        * `$blueprint->string("username")->foreignKey()->reference("users")`
        * `$blueprint->foreignKey("user_id","users_fk")->reference("users")`,
        * params : `array $cols`,
        * params : ` (string) $name`
        * Output : `CONSTRAINT $name FOREIGN KEY ($cols)`
    
    * reference()
        * Sets a reference table
        * Requires `ForeignKey()`Otherwise will fail
        * params : `(string) $table`
        * params : `(string) $column` defaults to `id`
    
    * onUpdate()
        * Optional Actions when column updates
        * `$blueprint->onUpdate("cascase")`
        * Params : `(string) $action`
        * Ouput : `ON UPDATE $action`
        * Defaults to Restrict if not applied

    * onDelete()
         * Optional Actions when column is deleted
        * `$blueprint->onDelete("cascase")`
        * Params : `(string) $action`
        * Ouput : `ON UPDATE $action`
        * Defaults to restrict if not applied

## Notes

### All Columns Must start with a Datatype entry
`int* string|varchar text* date*`

### Adding a new Datatype Resets the current column name pointer 

```php
    $blueprint->string("username") // creates username pointer
    // continues on username pointer
    $blueprint->nullable();
      //Resets to a new datatype
    $blueprint->int("id")->ai();

    // this would no longer assign to username and would try to assign to id
    $blueprint->default("mike");

```

The following examle applies to applyimng chained indexes and foreign Key Constraints

## Example Build

```php

Schema::create("users",function($blueprint)
{
    // Set the id and auto Incrment and primary Key
    $blueprint->id();
    $blueprint->string("username");
    $blueprint->string("email");
    $bluprint->string("password");
    $blueprint->datetime("created_at");
    $blueprint->datetime("updated_at");
});
```

```php
Schema::create("profile",function($blueprint)
{
    // Set the id and auto Incrment and primary Key
    $blueprint->id();
    $blueprint->int("user_id")->foreignKey()->reference("users")->onUpdate("cascde")->onDelete("cascade");
    // Apply a fulltext index 
    $blueprint->longtext("about")->fulltext();
    $blueprint->datetime("created_at");
    $blueprint->datetime("updated_at");

    
    // Optionally 

    /**
     * $blueprint->foreignKey("user_id","users_profile")
     * ->reference("users")
     * ->onUpdate("cascde")
     * ->onDelete("cascade");
     */
});




```
