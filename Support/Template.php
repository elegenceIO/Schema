<?php
namespace ElegenceIO\Database\Schema\Support;

use LogicException;
use Exception;
final class Template
{

        /**
         * @property array $base
         * used to store the base Values for a column,
         */
        private array $base = [
            "nullable" => false,
            "renamedFrom" => false,
            "default" => "__unset__",
            "unsigned"=>false,
        ];

        /**
         * @property array $primary
         * used to pre define primary key values on class registration
         */
        private array $primary = ["columns" => []];

        /**
         * @property array $indexes
         *  used to pre define index values on  class registration.
         */
        private array $indexes = [
            "kind" => "index",
            "name" => null,
            "columns" => [],
        ];

        /**
         * @property array $foreignKeys
         * used to pre define foreign key values on class registration
         */
        private array $foreignKeys = [
            "name" => null,
            "columns"=>[],
            "table"=>null,
            "reference"=>[],
            "onDelete"=>null,
            "onUpdate"=>null,
        ];

        /**
         * @property array $types
         * used to pre define column types and their properties
         */
        private array $types = [
        'tinyint'   => ["length"=>1,'unsigned' => false, 'default' => '__unset__', 'ai' => false],
        'int'       => ["length"=>8,'unsigned' => false, 'default' => '__unset__', 'ai' => false],
        'bigint'    => ["length"=>50,'unsigned' => false, 'default' => '__unset__', 'ai' => false],

        // strings: length required, no ai or unsigned
        'char'      => ['length' => 100, 'default' => '__unset__'],
        'varchar'   => ['length' => 255, 'default' => '__unset__'],

        // text types: no default key (MySQL rejects defaults on them)
        'text'       => [],
        'mediumtext' => [],
        'longtext'   => [],

        // dates
        'date'      => ['default' => '__unset__'],
        'datetime'  => ['default' => '__unset__', 'onUpdateNow' => false],
        ];


        /**
         * @method for();
         * @param string $type
         * @return array
         * used to return the template for a given type
         */
        public function for(string $type)
        {
            $type = strtolower($type);

            if(!isset($this->types[$type]))
            {
                throw new Exception("Type $type cannot be found");
            }

            return ["type"=>$type,...$this->base,...$this->types[$type]];
        }

        /**
         * @method forIndex();
         *  @param string $kind
         * @return array
         * used to return the template for a given index type
         */
        public function forIndex(string $kind):array
        {
            return [...$this->indexes,"kind"=>$kind];
        }

        /**
         * @method forPrimary();
         * @return array
         * used to return the template for a primary key
         */
        public function forPrimary():array
        {
            return  $this->primary;
        }

        /**
         * @method forForeignKeys();
         * @return array
         * used to return the template for foreign keys
         */
        public function forForeignKeys():array
        {
            return $this->foreignKeys;
        }

        /**
         * @method allowed();
         * @param string $type
         * @return bool
         * @see Template::for()
         * used to check if a type is allowed
         */
        public  function allowed(string $type)
        {
            return \array_key_exists($type,$this->for($type));
        }
}