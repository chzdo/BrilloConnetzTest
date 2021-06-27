<?php

namespace Models;





//customer class extend to database class
class Customer extends \migrations\database
{

  private $table = Tables['customers']; //setting the default table of the model
  private $primaryKey = "id";   //setting the primary key of the model
  private $id =  NULL;

  //contructor of the class
  function __construct($id = NULL)
  {
    if ($id != NULL) {
      $this->id = $id;
    }
  }


  /**
   * @param filter containd column to be filtered 
   * @param filter_type contains filter_type 
   * @param filter_value  columnn value to be used for filter
   * Function returns a query for filter 
   */
  function getFilterQuery($filter, $filter_type, $filter_value)
  {

    if ($filter == 'age') {
      if ($filter_type == 1) {
        return "where DATEDIFF(now(), customer.dob)/ 365.25 > $filter_value";
      } else {
        return "where DATEDIFF(now(), customer.dob)/ 365.25 < $filter_value";
      }
    }

    return "";
  }



  /**
   * @param sort contains column to be sorted
   * @param sort_type contains values ASC or DESC
   * @param filter containd column to be filtered 
   * @param filter_type contains filter_type 
   * @param filter_value  columnn value to be used for filter
   * Function returns an array of all customers 
   */
  function getAll($sort, $filter, $sort_type, $filter_type, $filter_value)
  {
    extract(Tables);

    $f_query = $this->getFilterQuery($filter, $filter_type, $filter_value);

    $sql = "select id, concat(names.first_name, ' ', names.last_name) as name, address, dob, phone_number , email  from $this->table as customer
     join  $names as names on names.customer_id =  customer.id  $f_query    order by $sort $sort_type ";

    $response =  $this->fetchAll([], $sql);

    if ($response) {
      return $this->result;
    } else {
      return   [];
    }
  }



  /**
   * @param details contains array of values to be inserted e.g array("name"=>John)
   * 
   * Function returns @param boolean showing if customer registration was successfull
   */
  function register($details)
  {
    extract(Tables); //extract names of tables

    //get names out of the input value
    $name['first_name'] = $details['first_name'];
    $name['last_name'] = $details['last_name'];
    $name['other_name'] = @$detailsT['other_name'];

    //remove them from the input value
    unset($details['first_name']);
    unset($details['last_name']);
    unset($details['other_name']);

    /**
     * Check if email and phone already exist
     */
    $input = ['email' => $details['email'], 'phone_number' => $details['phone_number']];
    $sql = "select * from $this->table where email = ? or phone_number = ?";
    if ($this->fetchOne($input, $sql)) {
      $_SESSION['msg'] = ["code" => 400, "message" => "email or phone number already exist"];
      return false;
    }

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
    $id = $this->getIDWithEmail($details['email']);

    if ($id === NULL) {
      $this->remove(['email' => $details['email']], [], $this->table);

      $_SESSION['msg'] = ["code" => 500, "message" => "Something went wrong"];
      return false;
    }

    /**
     * if above is succesfully insert names
     */

    $name['customer_id'] = $id['id'];  //add id to names Input

    if (!$this->Insert($name, $names)) {

      $this->remove(['email' => $details['email']], [], $this->table);

      $_SESSION['msg'] = ["code" => 500, "message" => "Something went wrong"];
      return false;
    }


    /**
     * Return response if all is successfully
     */
    $_SESSION['msg'] = ["code" => 201, "message" => "Registration Successfully"];
    return true;
  }



  /**
   * @param details contains array of values d e.g array("name"=>John)
   * 
   * Function returns @param boolean showing if customer email verificaion was successfull
   */
  function confirmMail($details)
  {

    $id =  $this->getIDWithEmail(base64_decode($details['email'])); //getting ID from email


    if ($id != null) {

      return  $this->update(["status" => 1], $id, [], $this->table); //update customer status
    }
    return false;
  }

  /**
   * @param details contains array of values d e.g array("name"=>John)
   * 
   * Function returns @param boolean showing if customer details update was successfull
   */
  function addOtherInfo($details)
  {

    return  $this->update($details, [$this->primaryKey => $this->id, "status" => 1], ["and"], $this->table);
  }


/**
   * @param email containsstring email values
   * 
   * Function returns @param array|NULL 
   *    */

  function getIDWithEmail($email)
  {
    $this->fetchOne(['email' => $email], "select id from $this->table where email = ?");
    return $this->result;
  }


/**
   * @param details contains array of values d e.g array("name"=>John)
   * 
   * Function returns @param boolean showing if customer name update was successfull
   */
  function updateNames($details)
  {

    return  $this->update($details, ["customer_id" => $this->id], [], $this->table);
  }

  /**
   * @param details contains array of values d e.g array("name"=>John)
   * 
   * Function returns @param boolean showing if customer card adding was successfull
   */
  function addCard($details)
  {
    extract(Tables);
    $details['customer_id'] = $this->id;
    return  $this->insert($details, $cards);
  }

  /**
   * @param details contains array of values d e.g array("name"=>John)
   * 
   * Function returns @param boolean showing if customer card update was successfull
   */
  function updateCard($details)
  {
    extract(Tables);
    return  $this->update($details, [$this->primaryKey => $this->id, "status" => 1], ['and'], $tokens);
  }


  /**
   * @param details contains array of values d e.g array("name"=>John)
   * 
   * Function returns @param boolean showing if customer name update was successfull
   */
  function updateName($details)
  {
    extract(Tables);
    return  $this->update($details, ["customer_id" => $this->id], [], $names);
  }

/**
   * @param details contains array of values d e.g array("name"=>John)
   * 
   * Function returns @param array|boolean showing if customer login was successfull
   */
  function login($details)
  {

    $sql = "select * from $this->table where email = ? ";
    $response =  $this->fetchOne(['email' => $details['email']], $sql);
    if ($response) {
      return $this->result;
    }

    return false;
  }


  /**
   *
   * Function returns @param boolean showing if customer is logged in
   */

  function isLogin()
  {

    return $this->id !== NULL;
  }



  /**
   * 
   * 
   * Function returns @param array|NULL of customer information
   */
  function getDetails()
  {
    extract(Tables);

   //query
    $sql = "select first_name, other_name, last_name , email, address, phone_number, tokens, dob  from $this->table
    join  $names on $names.customer_id = $this->table.id
    left join  $cards  on $cards.customer_id = $this->table.id
    
    where $this->primaryKey = ? and status = ?";

    $response =  $this->fetchOne([$this->primaryKey => $this->id, "status" => 1], $sql); //fetch customer

    if ($response) {
      return $this->result;
    }
    return NULL;
  }


  /**
   *  
   * Function returns @param string|NULL of customer email
   */
  function getEmail()
  {
    $sql = "select email from $this->table where $this->primaryKey = ? and status = ?";
    $response =  $this->fetchOne([$this->primaryKey => $this->id, "status" => 1], $sql);
    if ($response) {
      return $this->result['email'];
    }
    return NULL;
  }


  /**
   * 
   * 
   * Function returns @param string|NULL of customer password
   */
  function getPassword()
  {
    $sql = "select password from $this->table where $this->primaryKey = ? and status = ?";
    $response =  $this->fetchOne([$this->primaryKey => $this->id, "status" => 1], $sql);
    if ($response) {
      return $this->result['password'];
    }
    return NULL;
  }


  /**
   * @param hash  contains  bycrpt string
   * 
   * Function returns @param boolean showing if customer email change of password was successfull
   */
  function changePassword($hash)
  {
    return  $this->update(['password' => $hash], [$this->primaryKey => $this->id, "status" => 1], ["and"], $this->table);
  }

/**
   * 
   * 
   * Function returns @param array|NULL of customer card information
   */
  function getCardDetails()
  {
    extract(Tables);

    $sql = "select * from $cards where customer_id = ? ";

    $response =  $this->fetchOne(['customer_id' => $this->id], $sql);

    if ($response) {
      return $this->result;
    }
    return NULL;
  }



  /**
   * 
   * 
   * Function returns @param  array|NULL showing if customer purchases
   */
  function getPurchases()
  {
    extract(Tables);
    $sql = "select $movies.*, $payment.payment_ref , $payment.payment_date from $purchase
    left join $movies on $purchase.movie_id = $movies.id
     left join $payment on $payment.purchase_id = $purchase.id
    
    where customer_id = ?    
    ";

    $response =  $this->fetchAll(['customer_id' => $this->id], $sql);
    if ($response) {
      return $this->result;
    }
    return NULL;
  }


  /**
   * @param id   contains string of movie_id
   * 
   * Function returns @param boolean showing if customer has purchased the movie
   */
  function checkPurchase($id)
  {
    extract(Tables);
    $sql = "select * from $purchase
  
    
    where customer_id = ? and movie_id  = ?
    
    ";
    return $this->fetchOne(['customer_id' => $this->id, 'movie_id' => $id], $sql);
  }


  
  /**
   * @param movie_id contains id of the movie to be bought
   * @param payment_ref Payment reference of the purchase
   * @param amount amount paid
   * Function returns @param boolean showing if purchase was succesfully
   */
  function addPurchase($movie_id, $payment_ref, $amount)
  {
    extract(Tables);

    $input = ["customer_id" => $this->id, "movie_id" => $movie_id]; //set up input for purchase table

    $response = $this->Insert($input, $purchase);  //insert purchase

    if (!$response) {
      return false;    //check if insert was successfull
    }

    $sql = "select id from $purchase where customer_id = ? and movie_id = ? order by id DESC limit 1"; //get inserted ID query

    $pay_resp = $this->fetchOne($input, $sql);   

    if (!$pay_resp) {
      return false;
    }

    //set up input for payment table
    $_POST['p_id'] = $id = $this->result['id'];
    $pay['payment_ref'] = $payment_ref;
    $pay['purchase_id'] = $id;
    $pay['amount'] = $amount;
    $pay['payment_status'] = 1;


    if (!$response = $this->Insert($pay, $payment)) { //insert into payment table
      return false;
    }
    return true;
  }
}
