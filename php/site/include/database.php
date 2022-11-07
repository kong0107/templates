<?php

class mysqlii extends mysqli {

    /**
     * Get all result rows as a two-dimensional array.
     */
    public function get_all(
        string $sql
    ) : array|false {
        $result = $this->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : false;
    }

    /**
     * Get all values of the first column of each rows of the query result.
     */
    public function get_col(
        string $sql
    ) : array|false {
        $result = $this->query($sql);
        if(!$result) return false;
        $ret = array();
        while($v = $result->fetch_column()) $ret[] = $v;
        return $ret;
    }

    /**
     * Get the value of the first column of the first row of the query result.
     */
    public function get_one(
        string $sql
    ) : null|int|float|string|false {
        $result = $this->query($sql);
        return $result ? $result->fetch_column() : false;
    }

    /**
     * Get the first row as an associative array.
     */
    public function get_row(
        string $sql
    ) : array|null|false {
        $result = $this->query($sql);
        return $result ? $result->fetch_assoc() : false;
    }

    /**
     * Insert $data as a new row into $table_name.
     */
    public function insert(
        string $table_name,
        array $data,
        bool $test = false
    ) : int|string|false {
        $sql = sprintf(
            'INSERT INTO `%s` SET %s',
            self::escape($table_name),
            self::join_assoc($data, ',')
        );
        if($test) return $sql;
        return $this->query($sql) ? $this->insert_id : false;
    }

    /**
     * Get all rows which meets $conditions from $table_name.
     */
    public function select_all(
        string $table_name,
        array $conditions = [],
        bool $test = false
    ) : array|string|false {
        $sql = sprintf(
            'SELECT * FROM `%s`',
            self::escape($table_name)
        );
        if(count($conditions))
            $sql .= ' WHERE ' . self::join_assoc($conditions, ' AND ');

        if($test) return $sql;
        return $this->get_all($sql);
    }

    /**
     * Get the first row which meets $conditions from $table_name.
     */
    public function select_row(
        string $table_name,
        array $conditions,
        bool $test = false
    ) : array|string|null|false {
        $sql = sprintf(
            'SELECT * FROM `%s` WHERE %s LIMIT 1',
            self::escape($table_name),
            self::join_assoc($conditions, ' AND ')
        );
        if($test) return $sql;
        return $this->get_row($sql);
    }

    /**
     * Delete at most one row which meets $conditions in $table_name.
     */
    public function delete(
        string $table_name,
        array $conditions,
        bool $test = false
    ) : bool {
        $sql = sprintf(
            'DELETE FROM `%s` WHERE %s LIMIT 1',
            self::escape($table_name),
            self::join_assoc($conditions, ' AND ')
        );
        return $test ? $sql : $this->query($sql);
    }

    /**
     * Update at most one row which meets $conditions into $data in $table_name.
     */
    public function update(
        string $table_name,
        array $data,
        array $conditions,
        bool $test = false
    ) : bool {
        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s LIMIT 1',
            self::escape($table_name),
            self::join_assoc($data, ','),
            self::join_assoc($conditions, ' AND ')
        );
        return $test ? $sql : $this->query($sql);
    }

    /**
     * Try to insert $data as a new row into $table_name, or update if having corresponding unique keys.
     */
    public function replace(
        string $table_name,
        array $data,
        bool $test = false
    ) : int|string|false {
        $sql = sprintf(
            'REPLACE `%s` SET %s',
            self::escape($table_name),
            self::join_assoc($data, ',')
        );
        if($test) return $sql;
        return $this->query($sql) ? $this->insert_id : false;
    }


    /**
     * Escape single quote by prepending a backslash.
     */
    static public function escape(
        string $str
    ) : string {
        return str_replace(chr(0x27), chr(0x5c) . chr(0x27), $str);
    }

    /**
     * Convert an associative array into formatted strings and then concatenate them by $glue.
     */
    static public function join_assoc(
        array $assoc,
        string $glue
    ) : string {
        $pieces = [];
        foreach($assoc as $key => $value) $pieces[] = sprintf("`%s` = '%s'", self::escape($key), self::escape($value));
        return implode($glue, $pieces);
    }
}
