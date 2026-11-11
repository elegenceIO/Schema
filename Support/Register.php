<?php
namespace ElegenceIO\Database\Schema\Support;

use Exception;
use LogicException;

class Register
{
    /**
     * Set Properties
     */
    private array $columns = [];
    private ?string $current = null;

    // Add Indexes Primarys and fk

    private ?array $primary = null;
    private array $indexes = [];
    private array $foreignKeys = [];
    private ?int $currentFk = null;


    /** Set constructor */

    public function __construct(private Template $template)
    {

    }

    /**
     * Initial Reogstration
     */
    public function start(string $name,string $type, array $attr= [])
    {
        $this->columns[$name] = [...$this->template->for($type),...$attr];
        $this->current = $name;
    }
    

    /**
     * Set Additional Keys
     */
    public function set(string $key,mixed $value)
    {
        if($this->current === null) throw new Exception("Failed to find current Column");

        $column = &$this->columns[$this->current];

        if(!array_key_exists($key,$column))
        {
            throw  new LogicException("Cannot find Key $key");
        }

        $column[$key] = $value;
        
    }

    /** Set and Apply Primary Keys */
    public function setPrimary(string|array|null $columns=null):void
    {
        $cols = ($columns === null) 
        ? [$this->current ?? throw new LogicException("Declare a column first")] : 
        (array) $columns;

        if($this->primary !== null && $this->primary["columns"] !== $cols)
        {
            throw new LogicException("Only One Primary key can be Applied per Column");
        }

        $this->primary  = [...$this->template->forPrimary(),"columns"=>$cols];
    }

    /** Set indexes */
    public function setIndex(?string $name=null,?array $columns=null, string $kind="index"):void
    {
        $cols = ($columns === null) ? [$this->current ?? throw new LogicException("Declare a column first")] : (array) $columns;
        $this->indexes[] = [...$this->template->forIndex($kind), "columns"=>$cols,"name"=>$name];

    }

    public function setForeignKey(string|array|null $columns=null,?string $name=null):void
    {
        $cols = $columns === null ? [$this->current ?? throw new LogicException("Declare a column first")]
        : (array) $columns;

       //Register Foreign Keys
        $this->foreignKeys[] = [...$this->template->forForeignKeys(), "columns" => $cols, "name" => $name];
        $this->currentFk = array_key_last($this->foreignKeys);   
    }
    

    public function setFk(string $key, mixed $value):void
    {
        $this->currentFk ?? throw new LogicException("A foreign Key Must be set first");

        $fk = &$this->foreignKeys[$this->currentFk];
        if(!array_key_exists($key,$fk))
        {
            throw new LogicException("key cannot $key be found");
        }

        $fk[$key] = $value;
    }


    // Return Values for colunns
    public function columns():array
    {
        return $this->columns;
    }

    //return Primary keys
    public function primary():array
    {
         return $this->primary;
    }

    // return indexes 

    public function indexes()
    {
        return $this->indexes;
    }

    // Return Foreign Key Values
    public function foreignKeys():array
    {
        return $this->foreignKeys;
    }



}