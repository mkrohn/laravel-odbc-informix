<?php

namespace Mkrohn\Odbc\Informix\Schema;

use Illuminate\Database\Schema\Builder as BaseSchemaBuilder;

class InformixBuilder extends BaseSchemaBuilder
{
    /**
     * Drop all tables from the database.
     *
     * @return void
     */
    public function dropAllTables()
    {
        @trigger_error("If you really want this comment this line");

        $this->connection->statement($this->grammar->compileDropAllForeignKeys());

        $this->connection->statement($this->grammar->compileDropAllTables());
    }


}
