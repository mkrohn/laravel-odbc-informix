<?php

namespace App\Classes\Database\Informix\Schema;

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
        @trigger_error("Si realmente desea esto debe comentar esta linea");

        $this->connection->statement($this->grammar->compileDropAllForeignKeys());

        $this->connection->statement($this->grammar->compileDropAllTables());
    }


}
