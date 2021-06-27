<?php

namespace migrations;


class Conn
{

    /**
     * @param conn 
     * The function returns a static PDO connection
     */
    static  function Conn()
    {

        $db = (object)[
            "host" => "localhost",
            "db_name" => "myDb",
            "username" => "root",
            "password" => ""
        ];

        try {
            $conn = new \PDO("mysql:host=" . $db->host . ";dbname=" . $db->db_name, $db->username, $db->password);
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        } catch (\PDOException $exception) {

            echo "Connection error: " . $exception->getMessage();

        }
        return $conn;
    }
}


//class extend to the PDO connection class to have access to the static function
class database extends Conn
{

   
    /**
     * @param input for input values to be inserted
     * @param table for table to be inserted into
     * Function to insert into the database
     */

    function Insert($input, $table)
    {


        $query = "insert into $table set ";  //SQL Query composition

        $setQuery = '';     // Used for Concatenating the set part of the insert statment

        //setting up the query  e.g key = ?
        foreach ($input as $key => $value) {
            if (array_key_last($input) == $key) {
                $setQuery .= $key . '= ? ';
            } else {
                $setQuery .= $key . '= ? ,';
            }
        }

        $query .= $setQuery;

        $filteredInput = $this->filter($input); //Filtering input before insert

        $filteredInput = array_values($filteredInput); //getting index based array

        $stmt = $this::Conn()->prepare($query);

        if ($stmt->execute($filteredInput)) {  //executing the query
            return true;
        }
        return false;
    }


     /**
     * @param input fconatining the keys or columns to be inserted
     * @param table for table to be inserted into
     * @param arrayofValues contains multi-dimension indexed array of values to be added
     * Function to insert multiple values into the database
     */
    function TransactionInsert($input, $table, $arrayofValues)
    {


        $query = "insert into $table set ";  //SQL Query composition

        $setQuery = '';     // Used for Concatenating the set part of the insert statment

        //setting up the query 
        foreach ($input as $key => $value) {
            if (array_key_last($input) == $key) {
                $setQuery .= $value . '= ? ';
            } else {
                $setQuery .= $value . '= ? ,';
            }
        }

        $query .= $setQuery;  
                

        $stmt = $this::Conn()->prepare($query);

        $this::Conn()->beginTransaction(); //Start of traction

        foreach ($arrayofValues as $values) {
        
            $filteredInput = $this->filter($values); //Filtering input before insert

            $filteredInput = array_values($filteredInput);
       
          
            if (!$stmt->execute($filteredInput)) {
                $this::Conn()->rollback();
                return false;
            }
          
        }


      
        return true;
    }



    /**
     * @param input contains array of values to be processed
     * Function to filter inputs
     */
    function filter($input)
    {
        $filter = [];
        foreach ($input as $key => $value) {
            $filter[] = strip_tags(htmlentities($value));
        }
        return $filter;
    }


 /**
     * @param input for input values to be used for query
     * @param query contains mysql query
     * Function to fetch values from the database
     */
    function fetchOne($inputs, $query)
    {
        $this->result = NULL;   //set result to be empty

        $stmt = $this::Conn()->prepare($query);
     
        //filter input
        $filteredInput = $this->filter($inputs);
        $filteredInput = array_values($filteredInput);

        $stmt->execute($filteredInput);
        if ($stmt->rowCount() <= 0) { //check if result exist
            return false;
        }

        $this->result = $stmt->fetch(\PDO::FETCH_ASSOC); //fetch return

        return true;
    }

 /**
     * @param input for input values to be used for query
     * @param query contains mysql query
     * Function to fetch many  values from the database
     */

    function fetchAll($inputs, $query)
    {
        $this->result = NULL;   //set result to be empty

        $stmt = $this::Conn()->prepare($query);


        //filter inputs
        $filteredInput = $this->filter($inputs);
        $filteredInput = array_values($filteredInput);

        //execute query
        $stmt->execute($filteredInput);
        if ($stmt->rowCount() <= 0) {
            return false;
        }

        $this->result = $stmt->fetchAll(\PDO::FETCH_ASSOC); //fetch all query

        return true;
    }

     /**
     * @param inputs for input values to be used for query
     * @param condition the values to be used for condition in the where statement
     * @param conj  conatins  values such as and , or 
     * @param table contains table to be updated
     * Function to update a table
     */
    function update($inputs, $condition, $conj, $table)
    {
        $query = "update  $table set ";   //update statements
        $setQuery = '';

        //get query to look like e.g name = ?, id = ?
        foreach ($inputs as $key => $value) {
            if (array_key_last($inputs) == $key) {
                $setQuery .= $key . '= ? ';
            } else {
                $setQuery .= $key . '= ? ,';
            }
        }

        $query .= $setQuery;   //append setQuery

        //check if there is a condition
        if (count($condition) > 0) {
            $con = ' where ';
            $i = 0;
            foreach ($condition as $key => $value) {
                //get condition to look like e.g id = "123
                if (array_key_last($condition) == $key) {
                    $con .= $key . '= ? ';
                } else {
                    $con .= $key . '= ? ' . $conj[$i] . ' ';
                    $i++;
                }
            }
            $query .= $con;  // append condition
        }
        /**
         * Filter and merge Array
         */
        $filteredInput = $this->filter($inputs);
        $filteredCond = $this->filter($condition);
        $filteredInput = array_values($filteredInput);
        $filteredCond  = array_values($filteredCond);
        $mergedInputs = array_merge($filteredInput, $filteredCond);
        /**
         * filter and merge array end
         */


        $stmt = $this::Conn()->prepare($query);

        if ($stmt->execute($mergedInputs)) {
            return true;
        }
        return false;
    }


      /**
     * 
     * @param condition the values to be used for condition in the where statement
     * @param conj  conatins  values such as and , or 
     * @param table contains table to be deleted from
     * Function to delete an item from  a table
     */

    function remove($condition, $conj, $table)
    {
        $query = "delete from  $table  "; //delete Query

        //check if condition exists
        if (count($condition) > 0) {
            $con = ' where ';
            $i = 0;
            //get Query to look like e.g id = 1
            foreach ($condition as $key => $value) {
                if (array_key_last($condition) == $key) {
                    $con .= $key . '= ? ';
                } else {
                    $con .= $key . '= ? ' . $conj[$i] . " ";
                    $i++;
                }
            }
            $query .= $con;
        }

        $filteredCond = $this->filter($condition);

        $filteredCond = array_values($filteredCond);

        $stmt = $this::Conn()->prepare($query);

        if ($stmt->execute($filteredCond)) {
            return true;
        }
        return false;
    }
}
