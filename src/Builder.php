<?php

namespace Vuravel\Form;

use Vuravel\Form\Record;
use Schema;

class Builder extends Record
{

    public $recordKeyName = 'id';

    /**
     * Constructs a Vuravel\Form\Builder object
     *
     * @param  string $table
     * @param  string $recordId
     * @return void
     */
    public function __construct($table, $recordId, $recordKeyName = 'id')
    {
    	$this->recordId = $recordId;
		
        $this->connection = explode('.', $table)[0];
		$this->table = explode('.', $table)[1];
        $this->setTableColumns();

        $this->recordKeyName = $recordKeyName;
        $this->record = $this->recordBuilder()->first();
    }

    /**
     * Gets the value from DB for a column name.
     *
     * @param  string $name
     * @return mixed
     */
    public function getValueFromDB($name)
    {
        return $this->record->{$name};
    }
        
    /**
     * Updates a record in the table from the request data.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateFromRequest($request)
    {
        return $this->recordBuilder()->update($request->only(
            Schema::connection($this->connection)->getColumnListing($this->table)
        ));
    }

    /**
     * The table's Query builder.
     *
     * @return QueryBuilder
     */
    protected function recordBuilder()
    {
    	return $this->getQueryBuilder()->where($this->recordKeyName, $this->recordId);
    }
    
}