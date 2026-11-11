<?php
namespace ElegenceIO\Database\Schema\Statements;

class Drop extends \ElegenceIO\Database\Statements
{
    public function __construct(private string $table,private bool $ifExists=true)
    {

    }

    public function steps():array{

    return ["compileDrop"];
    }

    public function compileDrop():string
    {
        $sql = "DROP TABLE ";
        if($this->ifExists) $sql .= "IF EXISTS ";
        $sql .= $this->table;
        return $sql;
    }
}