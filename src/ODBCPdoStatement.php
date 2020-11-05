<?php

namespace Mkrohn\Odbc;

use PDOStatement;

class ODBCPdoStatement extends PDOStatement
{
    protected $query;
    protected $params = [];
    protected $statement;

    public function __construct($conn, $query)
    {
        $this->query = preg_replace('/(?<=\s|^):[^\s:]++/um', '?', $query);

        $this->params = $this->getParamsFromQuery($query);

        $this->statement = odbc_prepare($conn, $this->query);
    }

    protected function getParamsFromQuery($qry)
    {
        $params = [];
        $qryArray = explode(" ", $qry);
        $i = 0;

        while (isset($qryArray[$i])) {
            if (preg_match("/^:/", $qryArray[$i]))
            {
                $namedParam = substr($qryArray[$i], 1); // omite el : del nombre (MK)
                $params[$namedParam] = null;
            }

            $i++;
        }

        return $params;
    }

    public function rowCount()
    {
        return odbc_num_rows($this->statement);
    }

    public function bindValue($param, $val, $ignore = null)
    {
        $this->params[$param] = $val;
    }

    public function execute($ignore = null)
    {
        odbc_execute($this->statement, $this->params);
        $this->params = [];
    }

    public function fetchAll($how = NULL, $class_name = NULL, $ctor_args = NULL)
    {
        $records = [];
        $stored_proc = false;

        if (strlen($this->query) > 17)
        {
            if (strtolower(substr($this->query, 0, 17)) == 'execute procedure')
            {
                // MK: if it's a stored procedure, column names may not be included
                $stored_proc = true;
            }
        }

        if (! $stored_proc)
        {
            while ($record = $this->fetch()) {
                $records[] = $record;
            }
        }
        else
        {
            $records[] = $this->fetch_into($records);
        }

        return $records;
    }

    public function fetch($option = null, $ignore = null, $ignore2 = null)
    {
        $rec = odbc_fetch_array($this->statement);

        if ($rec)
        {
            // odbc_fetch_array has a bounds checking bug with utf8 strings, so we sanitize it:
            $this->sanitize_array($rec);
        }

        return $rec;
    }

    private function sanitize_array(&$rec)
    {
        foreach($rec as $key => $value)
        {

            $pos = mb_strpos($value, chr(0));

            if ($pos)
            {
                $value = substr($value, 0, $pos + 1);
                $rec[$key] = $value;
            }
        }
    }

    public function fetch_into($records)
    {
    // (MK) official doc https://www.redbooks.ibm.com/redbooks/pdfs/sg247218.pdf page 186
        odbc_fetch_into($this->statement, $records);
        return $records;
    }
}
