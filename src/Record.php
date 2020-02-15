<?php

namespace Vuravel\Form;

use Schema;
use DB;

abstract class Record
{
    /**
     * The record's id or key in the table.
     *
     * @var string|integer
     */
    public $recordId;

    /**
     * The record, row or model being processed.
     *
     * @var \Illuminate\Database\Eloquent\Model|Builder
     */
    public $record;

    /**
     * The connection's identifier from "{$connection}.{$table}" that the form links to.
     *
     * @var string
     */
    protected $connection;

    /**
     * The table's identifier from "{$connection}.{$table}" that the form links to.
     *
     * @var string
     */
    protected $table;

    /**
     * The table's columns.
     *
     * @var string
     */
    protected $columns;

    /**
     * Get's the value from the record.
     *
     * @param  string $attribute
     */
    abstract public function getValueFromDB($attribute);

    /**
     * Updates a record in the table from the request data.
     *
     * @param  \Illuminate\Http\Request $request
     */
    //abstract public function updateFromRequest($request);


    /**
     * A static way to construct a Vuravel\Form\Model|Builder object
     *
     * @param  string $recordString
     * @param  string $recordId
     * @return void
     */
    public static function create($recordString, $recordId, $recordKeyName = 'id')
    {
        return new static($recordString, $recordId, $recordKeyName);
    }


    /**
     * Get the model's attribute according to the Schema's table columns.
     *
     * @param  string $column
     * @return boolean
     */
    public function isAttribute($column)
    {
        return in_array($column, $this->columns);
    }

    protected function setTableColumns()
    {
        $this->columns = Schema::connection($this->connection)->getColumnListing($this->table);
    }


    //Overriden in Model, for the rest: false
    public function isBelongsTo($column){ return false; }


    /**
     * Get the record's connection and table for querying.
     *
     * @return boolean
     */
    public function getQueryBuilder(){
        return DB::connection($this->connection)->table($this->table);
    }


    public function getStoragePath($column)
    {
        //return \DB::connection($this->connection)->getDatabaseName().'/'.$this->table.'/'.$column;
        return $this->connection.'/'.$this->table.'/'.$column;
    }

    public function getTable()
    {
        return $this->table;
    }

    /**
     * Convert the class to its string representation in JSON
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this);
    }

}