<?php
namespace ElegenceIO\Database\Schema;

use ElegenceIO\Database\Schema\Support\Register;
use ElegenceIO\Database\Schema\Support\Datatypes;
use ElegenceIO\Database\Schema\Support\ForeignKeys;
use ElegenceIO\Database\Schema\Support\Indexing;
use ElegenceIO\Database\Schema\Support\Template;
use ElegenceIO\Database\Schema\Support\Validation;
use ElegenceIO\Database\Schema\Support\Attributes;
use ElegenceIO\Database\Support\CoreHelper;
use ElegenceIO\Database\Schema\Support\Processing;
use Exception;

class Blueprint
{
    // Supportive Core Traits
    use CoreHelper;
    
    // Manage Traits here
    use Datatypes, Attributes, Indexing,ForeignKeys;


    private ?Register $register = null;

    public function __construct()
    {
        $this->register = new Register(new Template());
    }


    /** Magic method designed to detect if a method exists or not. */
    public function __call(string $method,array $params)
    {
        if(!\method_exists($this,$method))
        {
            throw new Exception("Method {$method} could not be found");
        }

        return $method(...$params);
    }



  
    public function parse():string
    {
        $columns = $this->register->columns();
        $parts = [];
        foreach($columns as $name=>$column)
        {
            $parts[] = $this->renderColumn($name,$column);
        }

        array_push($parts,$this->renderPrimary($this->register->primary(),"columns"));
        array_push($parts,...$this->renderIndexes($this->register->indexes()));
        \array_push($parts,...$this->renderForeignKeys($this->register->foreignKeys()));

        return implode(",",$parts); 
    }


    
}