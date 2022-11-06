<?php
class DatabaseClass {
  
  protected $connection;
  protected $query;
  protected $show_errors = TRUE;
  protected $query_closed = TRUE;
  public $query_count = 0;

  // Create database conncetion 
  public function __construct() {
    $this->dbconnection = new mysqli($this->hostname = 'localhost', $this->username = 'root', $this->password = 'Password@2022', $this->database = 'shoppingcart');
    if($this->dbconnection->connect_error){
      throw new Exception('Failed to connect to MySQL - ('.$db->connect_errno.')');
    }
  }

  // Customs query builder with prepare statments
  public function query($query) {
    if (!$this->query_closed) {
      $this->query->close();
    }
    if ($this->query = $this->dbconnection->prepare($query)) {
      if (func_num_args() > 1) {
        $x = func_get_args();
        $args = array_slice($x, 1);
        $types = '';
        $args_ref = array();
        foreach ($args as $k => &$arg) {
          if (is_array($args[$k])) {
            foreach ($args[$k] as $j => &$a) {
              $types .= $this->_gettype($args[$k][$j]);
              $args_ref[] = &$a;
            }
          } else {
            $types .= $this->_gettype($args[$k]);
            $args_ref[] = &$arg;
          }
        }
        array_unshift($args_ref, $types);
        call_user_func_array(array($this->query, 'bind_param'), $args_ref);
      }
      $this->query->execute();
      if ($this->query->errno) {
        $this->error('Unable to process MySQL query (check your params) - ' . $this->query->error);
      }
      $this->query_closed = FALSE;
      $this->query_count++;
    } else {
      $this->error('Unable to prepare MySQL statement (check your syntax) - ' . $this->dbconnection->error);
    }
    return $this;
  }

  // Customs query builder which return all the result row in array
  public function fetchAll($callback = null) {
    $params = array();
    $row = array();
    $meta = $this->query->result_metadata();
    while ($field = $meta->fetch_field()) {
      $params[] = &$row[$field->name];
    }
    call_user_func_array(array($this->query, 'bind_result'), $params);
    $result = array();
      while ($this->query->fetch()) {
        $r = array();
        foreach ($row as $key => $val) {
          $r[$key] = $val;
        }
        if ($callback != null && is_callable($callback)) {
          $value = call_user_func($callback, $r);
          if ($value == 'break') break;
        } else {
          $result[] = $r;
        }
      }
      $this->query->close();
      $this->query_closed = TRUE;
      return $result;
    }

  // Customs query builder which return result array
  public function fetchArray() {
    $params = array();
    $row = array();
    $meta = $this->query->result_metadata();
    while ($field = $meta->fetch_field()) {
      $params[] = &$row[$field->name];
    }
    call_user_func_array(array($this->query, 'bind_result'), $params);
    $result = array();
    while ($this->query->fetch()) {
      foreach ($row as $key => $val) {
        $result[$key] = $val;
      }
    }
    $this->query->close();
    $this->query_closed = TRUE;
    return $result;
  }

  // Customs function to close the database connections
  public function close() {
    return $this->dbconnection->close();
  }

  // Customs function to get Num of rows in result data
  public function numRows() {
    $this->query->store_result();
    return $this->query->num_rows;
  }

  // Customs function to get affect rows data after applying CURD methods
  public function affectedRows() {
    return $this->query->affected_rows;
  }

  // Customs function to get insert id after applying insert query
  public function lastInsertID() {
    return $this->dbconnection->insert_id;
  }

  // Customs function to set error and stop excution
  public function error($error) {
    if ($this->show_errors) {
      exit($error);
    }
  }
}
?>