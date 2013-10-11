<?php
/**
 * @file Abstract class of the database.
 * Add another clsss and extend this class.
 * For example an Oracle driver
 */

abstract class Database_Library
{
   // Connectiondata
   protected  $cHost     = '';
   protected  $cUserName = '';
   protected  $cPassword = '';
   protected  $cDatabase = '';

   //Link to the db
   public $rDbLink = null;

   //Is there a  connection
   protected $bConnected = false;


   /**
    * Generic constructor
    *
    * @param string $cHost  Hostname
    * @param string $cUser  Username
    * @param string $cPass  Password
    */
   abstract public function __construct( $cHost = '' , $cUser = '' , $cPass = '' , $cDatabase = '' );

   abstract public function __destruct();


   /**
    * public interface
    */

   /**
    * Wrapper function for qeries.
    *
    */
   abstract public function DoQuery( $cSql, $values = array() );

   /**
    * Run a query and return it as an array
    */
   abstract public function DoQueryAsArray( $cSql, $values = array(),  $bOneDimensional = false );

   /**
    * Return a single value
    */
   abstract public function DoQueryAsSingleValue( $cSql );

   /**
    * Create an insertquery
    */
   abstract public function BuildInsertQuery( $cTable , $aData );

   /**
    * Create an updatequery
    */
   abstract public function BuildUpdateQuery( $cTable , $aData , $cWhereClause = '' );

   /**
    * Check if the table exists
    *
    * @param string $cTableName
    */
   abstract public function TableExist( $cTableName );


   /**
    * Getters en Setters
    */
   public function GetHost()
   {
      return $this->cHost;
   }
   public function SetHost( $xValue )
   {
      $this->cHost = $xValue;
   }

   public function GetUserName()
   {
      return $this->cUserName;
   }
   public function SetUserName( $xValue )
   {
      $this->cUserName = $xValue;
   }

   public function GetPassword()
   {
      return $this->cPassword;
   }
   public function SetPassword( $xValue )
   {
      $this->cPassword = $xValue;
   }

   public function GetDatabase()
   {
      return $this->cDatabase;
   }
   public function SetDatabase( $xValue )
   {
      $this->cDatabase = $xValue;
   }

   public function GetConnected()
   {
      return $this->bConnected;
   }

}