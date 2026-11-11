<?php
namespace ElegenceIO\Database\Schema;

use Dba\Connection;
use ElegenceIO\Database\Gateway;
use ElegenceIO\Database\Interceptor;
use ElegenceIO\Database\Queries\Database;
use ElegenceIO\Database\Queries\SchemaQueries;
use ElegenceIO\Database\Schema\Statements\Drop;
use ElegenceIO\Database\Schema\Blueprint;
use Exception;
use PDO;
use PDOException;

class Schema
{
    private ?Database $database = null;
    private ?object $instance = null;
    private ?SchemaQueries $schema = null;


    // Initial Create Class
    public  static function create(string $table,callable $callback):static
    {
        return self::generate($table,$callback);
    }

    /**
     * @method generate()
     * @param string $table
     * @param callable $callback
     * @return object
     */
    private static function generate(string $table, callable $callback):object
    {
        $bp = new Blueprint();
        $callback($bp);
        // Load INterceptor
        $interceptor = new Interceptor([],$table);
        $parts = $interceptor->compile("schema","create",["blueprint"=>$bp]);
        $gateway = new Gateway(app("connection"));

        try
        {
            return $gateway->parse($parts["sql"]);
        }
        catch (PDOException $e)
        {
            throw new Exception("Error creating table: " . $e->getMessage());
        }
    }


    /**
     * @method drop
     * @param string $table;
     * return void
     */
    public static function drop(string $table):object
    {
        $interceptor = new Interceptor([],$table);
        $parts = $interceptor->compile("schema","drop");
        echo $parts["sql"];
        $gateway = new Gateway(app("connection"));
        return $gateway->parse($parts["sql"]);
        
    }



}