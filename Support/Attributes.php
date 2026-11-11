<?php
namespace ElegenceIO\Database\Schema\Support;

trait Attributes
{
    /**
     * @method nullable()
     * @param bool $value
     * @return self
     * used to set the nullable property of a column
     */
    public function nullable(bool $value=true)
    {
        $this->register->set("nullable",$value);
        return $this;
    }

    /**
     * @method notNullable()
     * @see nullable();
     * @return self
     * used to set the nullable property of a column to false
     * Prevents empty Values inside a column and will cause an error if empty values are empty
     */
    public function notNullable()
    {
        $this->nullable(false);
        return $this;
    }

    
    /**
     * @method default()
     * @param mixed $value
     * @return self
     * used to set the default property of a column
     */
    public function default(mixed $value)
    {
        $this->register->set("default",$value);
        return $this;
    }

    public function unsigned()
    {
        $this->register->set("unsigned",true);
        return $this;
    }

    public function binary()
    {
         $this->register->set("binary","binary");
        return $this;
    }

    /**
     * @method ai()
     * @return self
     * used to set the auto-increment property of a column
     */
    public function ai()
    {
        $this->register->set("ai",true);
        $this->primary();
        return $this;
    }
}