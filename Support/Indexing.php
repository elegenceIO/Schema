<?php
namespace ElegenceIO\Database\Schema\Support;

use Exception;

trait Indexing
{

    /**
     * @method primary();
     * @param string|array|null $c=null
     * @return self
     * used to set a primary key on a column or columns
     */
    public function primary(string|array|null $c=null):self
    {
        $this->register->setPrimary($c);
        return $this;
    }


    /**
     * @method renderPrimary();
     * @param array $pk
     * @param string $key
     * @return string
     * used to render the primary key for a table
     */
    private function  renderPrimary(array $pk,string $key)
    {
        if($pk === null || $pk  === [])
        {
            return [];
        }

        return "PRIMARY KEY (" . implode(",",$pk[$key]). ")";
    }

    
    /**
     * @method index();
     * @param string|null $name=null
     * @param array|null $values=null
     * @return self
     * used to set an index on a column or columns
     */
    public function index(?string $name=null,?array $values=null)
    {
        $this->register->setIndex($name,$values,"index");
        return $this;
    }

    /**
     * @method unique();
     * @param string|null $name=null
     * @param array|null $values=null
     *  @return self
     * used to set a unique index on a column or columns
     */
       public function unique(?string $name=null,?array $values=null)
    {
        $this->register->setIndex($name,$values,"unique");
        return $this;
    }

    /**
     * @method fulltext();
     * @param string|null $name=null
     *  @param array|null $values=null
     * @return self
     * used to set a fulltext index on a column or columns
     */
       public function fulltext(?string $name=null,?array $values=null)
    {
        $this->register->setIndex($name,$values,"fulltext");
        return $this;
    }


    /**
     * @method renderIndexes();
     * @param array $indexes
     *  @return array
     * used to render the indexes for a table
     */
        private function renderIndexes(array $indexes):array
    {

        return array_map(function(array $i)
        {
            $name = $i["name"] ?? $this->indexName($i);
            $cols = implode(",",$i["columns"]);

            return match($i["kind"])
            {
                "unique" =>"UNIQUE KEY $name ($cols)",
                "fulltext"=> "FULLTEXT KEY $name ($cols)",
                default => "KEY $name ($cols)",
            };

        },$indexes);
    }

    /**
     * @method indexName();
     * @param array $i
     *  @return string
     * used to generate an index name based on the columns
     */

    private function indexName(array $i):string 
    {
            return "IDX_".implode(",",$i["columns"]);
    }


}