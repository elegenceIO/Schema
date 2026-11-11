<?php
namespace ElegenceIO\Database\Schema\Support;

use LogicException;

trait ForeignKeys
{
        protected static array  $fk = [];
        
        /**
         * @method ForeignKey()
         * @param string|array|null $columns=null 
         * @param ?string $name =null
         * Generate a new Foreign Key
         * @return self
         */
        public function foreignKey(string|array|null $columns=null,?string $name=null):self
        {
                $this->register->setForeignKey($columns,$name);
                return $this;
        }


        /**
         * @method onUpdate();
         * @param string $action
         * @return self
         */
        public function onUpdate(string $action):self
        {
                $this->register->setFk("onUpdate",$this->action($action));
                return $this;
        }

        /**
         * @method onDelete();
         * @param string $action
         * @return self
         */
        public function onDelete(string $action):self
        {
                $this->register->setFk("onDelete",$this->action($action));
                return $this;
        }


        /**
         * @method reference()
         * @param string $table
         * @param string|array $columns="id
         * @return self
         */
        public function reference(string $table,string|array $columns="id"):self
        {
                $this->register->setFk("table",$table);
                $this->register->setFk("reference",(array) $columns);
                return $this;
        }

        /**
         * @method action()
         * @param string $key
         * @return string
         * 
         | the action methid is designed to catch and allocate the correct Constraint to the foreign key
         */
        private function action(string $key):string
        {
                // convert to lower case to match the match()
                $key = strtolower($key);

                return match($key)
                {
                        "cascade"  => "CASCADE",
                        "restrict" => "RESTRICT",
                        "null"     => "SET NULL",
                        "none" => "NO ACTION",   
                        default=>"invalid Request",      
                };
        }

        // Render foeign Keys and constraints


        /**
         * Private method 
         * @method RenderForeignKeys()
         * @param array $fks
         * @return array
         */
        private function renderForeignKeys(array $fks):array
        {

                return array_map(function( array $f)
                {
                
                if($f["table"] !== null && $f["columns"] === [])
                {
                        // throw error here
                        throw new LogicException("A refernce is required with this foreign Key");
                }

                if(count($f["columns"]) !== count($f["reference"]))
                {
                        // throw Logic error
                        throw new LogicException("Foreign Key Column Cound does not match");
                }


                $name = $f["name"] ?: "fk_".implode("_",$f["columns"]);
                // Generate Foreign Key Code.
                $sql = "CONSTRAINT $name FOREIGN KEY (".implode(",",$f["columns"]).") ";
                $sql .= "REFERENCES {$f['table']}(" . implode(",",$f["reference"]). ") "; 

                if($f["onDelete"]) $sql .= " ON DELETE {$f["onDelete"]} ";
                if($f["onUpdate"]) $sql .= " ON UPDATE {$f["onUpdate"]} ";

                return $sql;
                },$fks);
        }

}