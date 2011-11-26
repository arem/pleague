<?php

if ( ! class_exists ( "SQL" ) )
{
  define ( 'SQL_RETURN_RESULT', 0 );
  define ( 'SQL_RETURN_ASSOC', 1 );
  define ( 'SQL_RETURN_ROWS', 2 );
  define ( 'SQL_RETURN_INSERTID', 3 );

  require "SQLQuery.php";

  // a database connection management class
  class SQL
  {
    // the database connection that we're going to manage
    var $dbh;

    var $queries = Array ();
  
    function SQL ( $user = '', $pass = '', $host = '', $dbname = '' )
    {
      $this->connect ( $user, $pass, $host, $dbname );
    }

    function connect ( $user = '', $pass = '', $host = '', $dbname = '' )
    {
      if ( !isset ( $user ) )
      {
        $user = '';
  
        if ( !isset ( $pass ) )
        {
          $pass = '';
  
          if ( !isset ( $host ) )
          {
            $host = 'localhost';
          }
        }
      }
      
      $this->dbh = @mysql_connect ( $host, $user, $pass );
  
      if ( $this->dbh == FALSE )
      {
        return FALSE;
      }
  
      if ( isset ( $dbname ) )
      {
        if ( @mysql_select_db ( $dbname, $this->dbh ) == FALSE )
        {
          @mysql_close ( $this->dbh );
  
          return FALSE;
        }
      }
  
      return TRUE;
    }
  
    function isConnected ()
    {
      return ( $this->dbh == FALSE ? FALSE : TRUE );
    }
    
    function disconnect ()
    {
      return ( $this->isConnected () == FALSE ? TRUE : @mysql_close ( $this->dbh ) );
    }
  
    function setDatabase ( $dbname )
    {
      return ( $this->isConnected () == FALSE ? FALSE : @mysql_select_db ( $dbname, $this->dbh ) );
    }
  
    function getConnection ()
    {
      return $this->dbh;
    }
  
    function getDatabase ()
    {
      return ( $this->isConnected () == FALSE ? '' : @mysql_db_name ( $this->dbh ) );
    }
    
    // executes the SQL query $sql and for SELECT queries returns the results
    // either;
    //
    // 0 = as a mysql_* result handle
    // 1 = all results in an assoc. array
    // 2 = ignore the result, return number of rows
    //
    // For non-SELECT queries the number of affected rows is returned
    function execute ( $sql, $type = 0 )
    {
      if ( $this->isConnected () == FALSE )
      {
        return FALSE;
      }
      
      $tsql = trim ( $sql );
      $this->queries [] = $sql;
  
      $q = @mysql_query ( $sql );
  
      if ( $q == FALSE )
      {
        return FALSE;
      }
     
      if ( preg_match ( "/^select/i", $tsql ) )
      {
        $return = $q;
      
        if ( $type == SQL_RETURN_ASSOC )
        {
          $return = array ();
  
          while ( $r = @mysql_fetch_assoc ( $q ) )
          {
            $return [] = $r;
          }
        }
        else if ( $type == SQL_RETURN_ROWS )
        {
          $return = @mysql_num_rows ( $q );
        }
      }
      else if ( preg_match ( '/^insert/i', $tsql ) )
      {
        $return = @mysql_affected_rows (); 
        
        if ( $type == SQL_RETURN_INSERTID &&
             $return > 0 )
        {
          $return = @mysql_insert_id ();
        }
      }
      else if ( preg_match ( '/^(update|delete)/i', $tsql ) )
      {
        $return = @mysql_affected_rows (); 
      }
      else
      {
        $return = TRUE;
      }
  
      return $return;
    }
  
    // returns an SQLQuery object with the SQL string $sql set and the database
    // handler $this->dbh given
    function prepareQuery ( $sql )
    {
      if ( $this->isConnected () === FALSE )
      {
        return FALSE;
      }

      $q = new SQLQuery ( $sql, $this );

      return $q;
    }

    function quote ( $str, $quote = 1 )
    {
      $ret = str_replace ( '\'', '\\\'', $str );
      
      if ( $quote )
      {
        $ret = '\'' . $ret . '\'';
      }
      
      return $ret;
    }

    function getQueries ()
    {
      return $this->queries;
    }
  }
}

?>
