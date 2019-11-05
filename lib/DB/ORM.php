<?php


namespace Frasma\DB;

use Generator;

/**
 * Abstraction class facilitator for using a DB Technology.
 *
 * @author André Gaspar <and_lrg@hotmail.com>
 */
class ORM{

    const AND = " AND ";
    const OR = " OR ";
    const DESC = "DESC";
    const ASC = "ASC";

    private $table;
    private $fields;
    private $where; //-> Key e Value
    private $orderBy;
    private $limit;
    private $conn;

    public function __construct($connector, $table){
        $this->fields=NULL;
        $this->where=NULL;
        $this->orderBy=NULL;
        $this->limit=NULL;
        $this->conn = $connector;
        $this->table = $table;
    }
    /**
     * Function responsible for performing the assembly of the select in the
     * respective table, applying the filters passed.
     *
     * @return Generator
     */
    public function select(){

        if ($this->fields){
            $this->fields = implode(", ", $this->fields);
        }else{
            $this->fields = "*";
        }

        $query = 'SELECT ' . $this->fields . ' FROM ' . $this->table;

        $stringWhere = '';

        if (is_array($this->where)){
            $stringWhere .= $this->prepareWhereString($this->where);
            $query .= $stringWhere;
        }else if (is_string($this->where)){
            $stringWhere = ' WHERE ' . $this->where;
            $query .= $stringWhere;
        }

        if ($this->orderBy){
            $query .= $this->orderBy;
        }
        if ($this->limit){
            $query .= ' LIMIT '. $this->limit;
        }
        $this->fields=NULL;
        $this->where=NULL;
        $this->orderBy=NULL;
        $this->limit=NULL;
        return $this->executeRawSql($query);
    }

    /**
     * Function responsible for holding the Inserts in the database
     *
     * @param string $table
     * @param array $values -> Key and Value
     * @return bool
     */
    public function insert($table, $values){

        $names = implode(', ', array_keys($values));

        $values = implode(', ', array_map(
            function($item){
                if (gettype($item) == 'string'){
                    return "'". $item ."'";
                }else{
                    return $item;
                }
            }, array_values($values)));

        $into_string = ' (' . $names . ') ';
        $values_string = 'VALUES (' . $values . ');';

        $query = 'INSERT INTO ' . $table . $into_string . $values_string;

        return $this->conn->query($query) === TRUE;
    }

    /**
     * Function responsible for performing the delete in the database
     *
     * @param string $table
     * @param array $where
     * @return bool|null
     */
    public function delete(string $table, array $where){

        if (!is_array($where)){
            return Null;
        }
        $where_string = $this->prepareWhereString($where);

        $query = 'DELETE FROM ' . $table . $where_string;

        return $this->conn->query($query) === TRUE;
    }

    /**
     * Function responsible for setting the table;
     *
     * @param string $table
     */
    public function from($table){
        $this->table = $table;
    }

    /**
     * Function will contain the fields needs to be selected in the query
     * EX: -> fields('field1', 'field2', 'field3')
     * @param mixed ...$fields ...Unpacker
     * @return ORM return
     */
    public function fields(...$fields){
        $this->fields = $fields;
        return $this;
    }

    /**
     * Function will build the where clause, can be called many times to be
     * the composition of conditionals, with the connective
     * MySql::AND and MySql::OR and the static functions `gte` and `equals`.
     * In addition it can be called in raw state.
     * EX:
     * ->where(MySql::equals('field', 2))
     * ->where(MySql::OR)
     * ->where('campo2 in (1,2,3)')
     *
     * @param $where string
     * @return ORM return
     */
    public function where($where){
        $this->where .= $where;
        return $this;
    }

    /**
     * Function will define the order by, receives the field and sort type
     * (not required, default: ASC), you can use the MySql::ASC and
     * MySql::DESC.
     *
     * @param $field string
     * @param $type ASC ou DESC
     * @return ORM return
     */
    public function orderBy($field, $type=MySQL::ASC){
        $this->orderBy = " ORDER BY ". $field . " " . $type . " ";
        return $this;
    }

    /**
     * Function will set the limit.
     *
     * @param $limit int
     * @return ORM return
     */
    public function limit($limit){
        $this->limit = $limit;
        return $this;
    }

    /**
     * Executes the select and throws at a variable received by reference in
     * the parameters.
     * @param $result array
     */
    public function selectTo(&$result){
        $result = $this->select();
    }

    /**
     * Executes the select and throws the first result at a variable
     * received by reference in the parameters.
     * @param $result array
     */
    public function getTo(&$result){
        $result = $this->select()->current();
    }


    /**
     * Function responsible for executing SQL query
     *
     * @param string query
     * @return Generator
     */
    public function executeRawSql($query){

        $result = $this->conn->query($query) or die($this->conn->error);

        while($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            yield $row;
        }
    }

    private function prepareWhereString($where){
        return ' WHERE ' . implode(', ', array_map(
                function($key, $value) {
                    return $key . '=' . $value . '';
                }, array_keys($where), array_values($where)));
    }

    public static function gte($key, $val){
        return $key . " <= " . $val . " ";
    }

    public static function equals($key, $val){
        return $key . " = " . $val . " ";
    }

}