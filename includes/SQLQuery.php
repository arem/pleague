<?php

if ( ! class_exists ( "SQLQuery" ) )
{
  // a query class for preparing and executing database queries
  class SQLQuery
  {
    var $parent;
    var $sql;
    var $returntype;

    function SQLQuery ( $sql_string, $sql_class, $sql_return_type = 0 )
    {
      $this->parent = $sql_class;
      $this->sql = $sql_string;
      $this->returntype = $sql_return_type;
    }
  
    function setReturnType ( $type )
    {
      $this->returntype = $type;
    }
    
    function getReturnType ()
    {
      return $this->returntype;
    }
    
    // execute the stored query and return the results
    function execute ()
    {
      $count = func_num_args ();
      $pos = 0;

      // number of binds dealt with
      $bind = 0;

      // temporary SQL query
      $tmpsql = "";

      // these specify whether we're currently "in" either speechmarks or
      // apostrophes
      $insm = 0;
      $inap = 0;
      
      for ( $l = 0; $l < strlen ( $this->sql ); ++$l )
      {
        switch ( $this->sql { $l } )
        {
          case "\"":
            // as long as we're not within apostrophes
            if ( ! ( $inap % 2 ) )
            {
              $insm++;
            }
            
            break;
          
          case "'":
            // as long as we're not within speech marks
            if ( ! ( $insm % 2 ) )
            {
              $inap++;
            }
            
            break;
            
          case "\\":
            $l++;
            break;

          case "?":
            if ( ! ( $inap % 2 ) && ! ( $insm % 2 ) )
            {
              $tmpsql .= substr ( $this->sql, $pos, ( $l - $pos ) );
              
              $t = func_get_arg ( $bind++ );
              if ( preg_match ( '/^[0-9]*(\.[0-9]*)?$/', $t ) )
              {
                $tmpsql .= $t;
              }
              else
              {
                $tmpsql .= $this->parent->quote ( $t );
              }
              
              $pos = $l + 1;
            }

            break;

          default:
            break;
        }

        if ( $bind >= $count )
        {
          break;
        }
      }

      $tmpsql .= substr ( $this->sql, $pos );

      return $this->parent->execute ( $tmpsql, $returntype );
    }
  }
}

?>
