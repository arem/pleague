<?php

if ( ! class_exists ( "CGI" ) )
{
  class CGI
  {
    // this is going to store all of our POST/GET variables
    var $args;
  
    function htmlEncode ( $string )
    {
      $string = str_replace ( "&", "&amp;", $string );
      $string = str_replace ( "<", "&lt;", $string );
      $string = str_replace ( ">", "&gt;", $string );
      $string = str_replace ( "\"", "&quot;", $string );
      $string = str_replace ( "'", "", $string );  
      return $string;
    }
  
    function unquote ( $string )
    {
      if ( get_magic_quotes_gpc () )
      {
 //       $string = stripslashes ( $string );
      }
  
      return $string;
    }
    
    function getValue ( $key, $type = '' )
    {
      if ( isset ( $this->args [ $key ] ) )
      {
        if ( strtolower ( $type ) == 'post' )
        {
          return $_POST [ $key ];
        }
        else if ( strtolower ( $type ) == 'get' )
        {
          return $_GET [ $key ];
        }
        
        return $this->args [ $key ];
      }
      
      return FALSE;
    }
    
    function CGI ()
    {
      if ( !isset ( $_POST ) && isset ( $HTTP_POST_VARS ) )
      {
        $_POST = $HTTP_POST_VARS;
      }
      
      if ( !isset ( $_GET ) && isset ( $HTTP_GET_VARS ) )
      {
        $_GET = $HTTP_GET_VARS;
      }
      
      if ( !isset ( $_COOKIE ) && isset ( $HTTP_COOKIE_VARS ) )
      {
        $_COOKIE = $HTTP_COOKIE_VARS;
      }
      
      while ( list ( $key, $val ) = each ( $_POST ) )
      {
        $_POST [ $key ] = $this->unquote ( $val );
      }
      
      while ( list ( $key, $val ) = each ( $_GET ) )
      {
        $_GET [ $key ] = $this->unquote ( $val );
      }
      
      while ( list ( $key, $val ) = each ( $_COOKIE ) )
      {
        $_COOKIE [ $key ] = $this->unquote ( $val );
      }
      
      $this->args = array_merge ( $_GET, $_POST );
    }
  }
}

?>
