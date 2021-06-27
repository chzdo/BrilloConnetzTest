<?php

namespace Models;


//movies extend database class
class movies extends \migrations\database
{

    private $table = Tables['movies'];
    private $primaryKey = "id";
    private $id =  NULL;

    function __construct($id = NULL)
    {

        if ($id != NULL) {
            $this->id = $id;
        }
    }

    /**
     * @param filter  containd column to be filtered 
     * @param filter_value  columnn value to be used for filter
     * Function returns a query for filter 
     */

    function filter_movie($filter, $filter_value)
    {

        extract(Tables);
        //check if filter is empty
        if (!empty($filter) && !empty($filter_value)) {
            if ($filter == 'genre') {
                return " where $genre.name = '$filter_value'";
            } else if ($filter == 'name') {
                return " where movie.name like '%$filter_value'";
            }
        }
        return "";
    }


    /**
     * @param sort containd column to be sorted
     * @param sort_type contains ASC , DESC 
     * @param filter containd column to be filtered 
     * @param filter_value  columnn value to be used for filter
     * Function returns @param array of all movies 
     */
    function getAll($sort, $filter, $sort_type, $filter_Value)
    {

        $filteQ = $this->filter_movie($filter, $filter_Value); //get filter

        extract(Tables);

        //query
        $sql = "select movie.name as movie_title , movie.id, movie.size, movie.image_location, movie.date_created, movie.amount,  $genre.name as genre_name  from $this->table as movie
        join  $movie_genre on $movie_genre.movies_id =  movie.id 
        join  $genre on $movie_genre.genres_id =  $genre.id
          $filteQ

          order by movie.id DESC
        ";

        $response =  $this->fetchAll([], $sql);

        if ($response) {
            return $this->result;
        } else {
            return   [];
        }
    }



    /**
     * @param sort containd column to be sorted
     * @param sort_type contains ASC , DESC 
     * @param filter containd column to be filtered 
     * @param customer  conatins customer ID
     * Function returns @param array of all movies 
     */
    function getAllPurchases($sort, $filter, $sort_type, $customer)
    {
        extract(Tables);

        $filterquery = "";

        //check filter exist and filter by date 
        if ($filter != "") {
            $date_array = @explode("-", $filter);
            $filterquery = "where MONTH(payment_date) = '" . $date_array[1] . "' and YEAR(payment_date)=" . $date_array[0];
        }

        //check customer  exist and filter by customer
        if (!empty($customer)) {
            $filterquery .= $filterquery == "" ? "where purchase.customer_id = $customer " : "and purchase.customer_id = $customer ";
        }

        //sql query
        $sql = "select 
        concat(name.first_name,' ', name.last_name) as name,
        payment_ref,payment_date,payment_status, movies.name as movie_title, movies.image_location,
        pay.amount as payment_amount        
        from $purchase as purchase
        left join  $this->table as movies on movies.id =  purchase.movie_id
        left join  $names as name on name.customer_id = purchase.customer_id
        left join  $payment as pay on pay.purchase_id = purchase.id
        $filterquery
        order by purchase.$sort $sort_type;
    
        
        ";

        $response =  $this->fetchAll([], $sql); //fetch all 

        if ($response) {
            return $this->result;
        } else {
            return   [];
        }
    }



    /**
     * @param details contains array of values e.g array("name"=>"doe")
     * Function returns @param boolean  
     */
    function upload($details)
    {
        extract(Tables); //extract names of tables

        //check if movie name exist
        $input = ['name' => $details['name']];

        $sql = "select * from $this->table where name = ? ";

        if ($this->fetchOne($input, $sql)) {
            $_SESSION['msg'] = ["code" => 400, "message" => "Movie already exist"];
            return false;
        }
        //end of check if movie nam exist

        $genres = $details['genre'];  //get genre must be an array
        unset($details['genre']); //unset genre from details


        /**
         * Insert new Information
         */
        if (!$this->insert($details, $this->table)) {
            $_SESSION['msg'] = ["code" => 500, "message" => "Something went wrong"];
            return false;
        }

        /**
         * get Last Inserted ID
         */
        $id = $this->getIDWithName($details['name']);

        if ($id === NULL) {
            $this->remove(['name' => $details['name']], [], $this->table);

            $_SESSION['msg'] = ["code" => 500, "message" => "Something went wrong"];
            return false;
        }

        /**
         * if above is succesfully insert nGenre
         */


        if (!$this->InsertGenre($id['id'], $genres)) {
            $this->remove(['name' => $details['name']], [], $this->table);

            $_SESSION['msg'] = ["code" => 500, "message" => "Something went wrong"];
            return false;
        }



        /**
         * Return response if all is successfully
         */
        $_SESSION['msg'] = ["code" => 201, "message" => "Saved"];
        return true;
    }


    /**
     * @param id contains ID of genre
     * Function returns @param boolean  
     */
    function findGenre($id)
    {
        extract(Tables);
        return  $this->fetchOne(["id" => $id], "select * from $genre where id = ?");
    }

    /**
     * @param id contains ID of movie
     * Function returns @param boolean  if movie is found
     */
    function find($id)
    {
        extract(Tables);

        $resp =   $this->fetchOne(["id" => $id], "select id from $this->table where id = ?");

        if ($resp) {
            $this->id = $this->result['id'];
        }
        return $resp;
    }

    /**
     * 
     * Function returns @param boolean  showing if movie is deleted 
     */
    function delete()
    {
        extract(Tables);
        $resp =  $this->remove(["id" => $this->result['id']], [], $this->table); //delete movie

        if ($resp) {
            @unlink("../" . $this->result['file_location']); //delete video file
            @unlink("../" . $this->result['image_location']); //delete cover file
        }
        return $resp;
    }

    /**
     * @param name contains name of movie
     * Function returns @param array|NULL  of  movied
     */
    function getIDWithName($name)
    {
        $this->fetchOne(['name' => $name], "select id from $this->table where name = ?");
        return $this->result;
    }


    /**
     * @param id contains ID of movie
     * @param genre contains array of genre ID
     * Function returns @param boolean  of if genre was inserted
     */
    function InsertGenre($id, $genres)
    {
        extract(Tables);
        $post = [];

        foreach ($genres as $genres) {
            $post[] = ['movies_id' => $id, 'genres_id' => $genres];
        }

        return $this->TransactionInsert(["movies_id", "genres_id"], $movie_genre, $post);
    }


    /**
     * @param details contains array of values
     * Function returns @param boolean  of  movied
     */
    function editUpload($details)
    {
        extract(Tables); //extract names of tables

        $this->oldfile = $this->result[0];  //get old movie information

        //check if there is change in them. Then check if the new name already exist
        if (isset($details['name']) && !empty($details['name'])) {
            if ($this->oldfile['name'] != $details['name']) {
                $input = ['name' => $details['name']];
                $sql = "select * from $this->table where name = ? ";
                if ($this->fetchOne($input, $sql)) {
                    $_SESSION['msg'] = ["code" => 400, "message" => "Movie already exist"];
                    return false;
                }
            }
        }

        //check if  genre is an array
        if (isset($details['genre']) && is_array($details['genre'])) {
            $genres = $details['genre'];
            unset($details['genre']);
        }

        /**
         * Update infromation
         */
        if (!$this->update($details, ['id' => $this->oldfile['id']], [], $this->table)) {
            $_SESSION['msg'] = ["code" => 500, "message" => "Something went wrong"];
            return false;
        }

        /**
         * if above is succesfully edit genre
         */

        if (!$this->editGenre($genres)) {

            $_SESSION['msg'] = ["code" => 500, "message" => "Finished with some errors"];
        }



        /**
         * Return response if all is successfully
         */

        return true;
    }


    /**
     * 
     * Function returns @param array|NULL  of  genre of a movie
     */
    function getGenre()
    {

        extract(Tables);
        $sql = "select * from $movie_genre where movies_id = ?";
        $this->fetchAll(['movies_id' => $this->id], $sql);

        return $this->result;
    }

    /**
     * 
     * Function returns @param boolean  of  genre name of a movie
     */
    function getGenreName()
    {

        extract(Tables);
        $sql = "select $genre.name from $movie_genre join $genre on $genre.id = $movie_genre.genres_id where movies_id = ?  ";
        $this->fetchAll(['movies_id' => $this->id], $sql);

        return $this->result;
    }


    /**
     * @param genres contains array of genre ID
     * Function returns @param boolean  of  movied
     */
    function editGenre($genres)
    {
        $gen_result  = $this->getGenre(); //get movie existing genres

        if ($gen_result != NULL) { //if found
            foreach ($gen_result as $value) {
                $newGen[] =  $value['genres_id']; //extract the IDs
            }


            $gentoAdd = array_diff($genres, $newGen);   //compare inserted genre and genre in DB to get new Gen

            $gentoRemove = array_diff($newGen, $genres); //compare inserted genre and genre in DB to genre to be removed

            //if genre exist to be removed 
            if (count($gentoRemove) > 0) {
                $this->removeMovieGenre($gentoRemove);
            }

            $genres = $gentoAdd;  //set new genres 
        }


        return $this->InsertGenre($this->id, $genres);  //insert Genre
    }



    /**
     * @param gentoRemove  contains array of genre ID
     * Function returns @param void of  movied
     */
    function removeMovieGenre($gentoRemove)
    {
        extract(Tables);

        foreach ($gentoRemove as $key => $value) {

            $this->remove(['movies_id' => $this->id, "genres_id" => $value], ["and"], $movie_genre);
        }
    }

    /**
     * 
     * Function returns @param array of   genres
     */
    function getAllGenre()
    {
        extract(Tables);
        $resp = $this->fetchAll([], "select * from $genre");
        return $resp ? $this->result : [];
    }


    /**
     * 
     * Function returns @param array of  movie
     */
    function get()
    {


        extract(Tables);
        //query
        $sql = "select movie.*, 
      $genre.id as genre_id
        from $this->table as movie
        join  $movie_genre on $movie_genre.movies_id =  movie.id 
        join  $genre on $movie_genre.genres_id =  $genre.id
        where movie.id = $this->id";

        $response =  $this->fetchAll([], $sql);

        if ($response) {
            return $this->result;
        } else {
            return   [];
        }
    }
}
