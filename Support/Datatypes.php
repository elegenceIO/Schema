<?php

namespace ElegenceIO\Database\Schema\Support;

use ElegenceIO\Database\Executor;
use Exception;

trait Datatypes
{

    /**
     * @method id()
     * @param string $column
     * @return self
     * @see ai();
     * Quick hand Datatype to generate an id column with auto increment and primary key set.
     */

    public function id(string $column = "id"): self
    {
        $this->int($column, 11)->notNullable()->ai();
        return $this;
    }

    /**
     * @method tinyInt()
     * @param string $column
     * @param int $value
     * @return self
     *  1 digit integer value tiny int
     */

    public function tinyInt(string $column, ?int $value = 1): self
    {
        $this->setType($column, "bigint", ["length" => $value]);
        return $this;
    }

    /**
     * @method int()
     * @param string $column
     * @param int $value
     * @return self
     *  set integer value.
     */

    public function int(string $column, ?int $value=null): self
    {
        $this->setType($column, "int", ["length" => $value]);
        return $this;
    }


    /**
     * @method bigInt()
     * @param string $column
     * @param int $value
     * @return self
     *  used to set larger integer value.
     */


    public function bigInt(string $column, ?int $value=null): self
    {
        $this->setType($column, "bigint", ["length" => $value]);
        return $this;
    }

    /**
     * @method string();
     * @param string $column
     * @param int $value
     * @return self
     *  used to set a string value with a default length of 100
     */

    public function string(string $column, int $value = 100): self
    {
        $this->setType($column, "char", ["length" => $value]);
        return $this;
    }

    /**
     * @method varchar();
     * @param string $column
     * @param int $value
     * @return self
     *  used to set a variable character string value with a default length of 255
     */

    public function varchar(string $column, int $value = 255): self
    {
        $this->setType($column, "varchar", ["length" => $value]);
        return $this;
    }


    /**
     * @method text();
     * @param string $column
     * @return self
     * used to set a text value with no length limit    
     */
    public function text(string $column): self
    {
        $this->setType($column, "text");
        return $this;
    }

    /**
     * @method mediumText();
     * @param string $column
     * @return self
     * used to set a medium text column
     */
    public function mediumText(string $column): self
    {
        $this->setType($column, "mediumText");
        return $this;
    }

    /**
     * @method longText();
     * @param string $column
     * @return self
     * used to set a long text column
     */
    public function longText(string $column): self
    {
        $this->setType($column, "longtext");
        return $this;
    }

    /**
     * @method date();
     * @param string $column
     * @return self
     * used to set a date column
     */
    public function date(string $column): self
    {
        $this->setType($column, "date");
        return $this;
    }

    /**
     * @method datetime();
     * @param string $column
     * @return self
     * used to set a datetime column    
     */
    public function datetime(string $column): self
    {
        $this->setType($column, "datetime");
        return $this;
    }


    // Parse Columns

    /**
     * @method renderColumn();
     * @param string $name
     *  @param array $c
     * @return string|null
     * used to render a column into a string for the database
     */

    private function renderColumn(string $name, array $c): ?string
    {
        $parts = [$name];

        foreach ($this->renderOrder as $key) {
            if (!array_key_exists($key, $c)) continue;
            $peices = $this->peices($key, $c[$key], $c);
            if ($peices !== null) $parts[] = $peices;
        }

        return implode(" ", $parts);
    }



    /**
     *  Private classes and properties.
     |  classes below are private and are required for above Public methods to work
     |  Not to be used directly.
     |  
     */

      /**
     * @property array $renderOrder
     * Set the order in which the properties must be rendered
     */
     private array $renderOrder = ["type","length","unsigned","nullable","default","ai"];
    

    /**
     *  @method setType();
     *  @param string $name
     * @param string $type
     * @param array $attr
     * @return void
     * used to set the type of a column
     */
    private function setType(string $name,string $type,array $attr=[])
    {
        return $this->register->start($name,$type,$attr);
    }

    /**
     * @method peices();
     * @param string $key
     * @param mixed $value
     * @param array $c
     * @return string|null
     * used to render a column piece into a string for the database
     */
    private function peices(string $key, mixed $value, array $c): string|null
    {
        return match ($key) {
            "type" => strtoupper($value) . $this->width($c),
            "length" => null,
            "unsigned" => ($value) ? "UNSIGNED" : null,
            "binary"=>($value) ? "BINARY" : null,
            "nullable" => ($value) ? "NULL" : "NOT NULL",
            "default" => ($value === "__unset__") ? null : "DEFAULT " . $this->defaultValue($value),
            "ai" => ($value) ? "AUTO_INCREMENT" : null,
            default => null,
        };
    }

    /**
     * @method defaultValue();
     * @param mixed $v
     * @return string
     * used to render a default value into a string for the database
     */
    private function defaultValue(mixed $v): string
    {
        return match (true) {
            $v === null                => 'NULL',
            is_bool($v)                => $v ? '1' : '0',
            is_int($v) || is_float($v) => (string) $v,
            default                    => "'" . str_replace("'", "''", $v) . "'",
        };
    }

    /**
     * @method width();
     * @param array $c
     * @return string
     * used to render the width of a column for the database
     */
    private function width(array $c)
    {
        return isset($c['length']) ? "(" . $c['length'] . ")" : '';
    }
}
