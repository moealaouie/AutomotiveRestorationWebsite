<?php

/**
 * Defines the RestService class.
 *
 * This class extends the Rest API to provide a connection to database functionality.
 */
class RestService extends Rest {

  /**
   * @var Database
   *
   * Contains the Database API object.
   */
  private $database;

  /**
   * @var string
   *
   * Contains the authentication method.
   */
  private $auth_method;

  /**
   * Creates a new instance of this class.
   *
   * @param Database
   *   The initialized database object.
   */
  public static function create(Database $database) {
    return new static($database);
  }

  /**
   * Initialize a new RestService object.
   *
   * @param Database
   *   The initialized database object.
   */
  public function __construct(Database $database) {
    parent::__construct();
    $this->database = $database;
  }

  /**
   * Public method for access api.
   * This method dynmically calls the method based on the query string
   */
  public function processApi() {
    $request = $this->_request['request'];
    $values = $this->_request;

    // WARNING: This method needs to add some ways to authenticate the user
    // and should also filter out any dangerous or magic methods before it
    // would be safe. This code is for demonstration purposes only!

    if (method_exists($this->database, $request)) {
      if (!empty($_REQUEST['request'])) {
        unset($values['request']);
        $result = $this->database->processRequest($request, $values);
        if (!empty($result)) {
          $this->response($this->json($result), 200);
        }
        else {
          // If no records "No Content" status
          $this->response('',204);
        }
      }
      else {
        // If the method not exist with in this class, response would be "Page not found".
        $this->response('',404);
      }
    }
  }

  /**
   * Encode to json if passed data is an array.
   */
  private function json($data) {
    if (is_array($data)) {
      return json_encode($data);
    }
  }
}
