<?php

namespace ElegenceIO\Database\Schema\Statements;

use ElegenceIO\Database\Statements;

class Create extends Statements
{

   protected array $parts = [];
   protected array $bindings = [];
   public function __construct(private string $table, private array $data) {}

   public function steps(): array
   {
      return  ["compileCreate"];
   }

   private function open():string
   {
      return "CREATE TABLE IF NOT EXISTS {$this->table} (";
   }

   private function close():string
   {
      return ")";
   }
   
   public function compileCreate()
   {
      $blueprint = $this->data["blueprint"];
      $sql = $this->open();
      $sql .= $blueprint->parse();
      $sql .= $this->close();
      echo $sql;
      return $sql;
   }
}
