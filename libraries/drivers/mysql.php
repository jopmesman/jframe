<?php
/**
 * MySQL Class
 * This one extends the Database library
 */

/**
 * Class Mysql_driver
 */
class Mysql_Driver extends Database_Library {


  /**
   * constructor
   *
   * @param string $cHost  Hostname
   * @param string $cUser  Username
   * @param string $cPass  Password
   */
  public function __construct( $cHost = '', $cUser = '', $cPass = '', $cDatabase = '' ) {
    $this->SetHost( $cHost );
    $this->SetUserName( $cUser );
    $this->SetPassword( $cPass );
    $this->SetDatabase( $cDatabase );
  }

  /**
   * Destruct function
   */
  public function __destruct() {

  }

  /**
   * Connect to the db
   */
  private function TryToConnect() {
    if ( !$this->bConnected ) {
      $this->rDbLink = mysql_connect( $this->cHost, $this->cUserName, $this->cPassword );
      if ( $this->rDbLink ) {
        $this->bConnected = true;
      }
      else {
        throw new Exception( 'Can not make a connection' );
      }
    }

    if ( $this->bConnected and $this->cDatabase ) {
      mysql_select_db( $this->cDatabase, $this->rDbLink );
    }


    return $this->bConnected;
  }

  /**
   * General query function.
   *
   * @param string $cSql
   * @param array $values
   * @return mysql resultset
   */
  public function DoQuery( $cSql, $values = array() ) {
    $this->TryToConnect();

    if (count($values) > 0) {
      $query = call_user_func_array('sprintf', array_merge(array($cSql), array_map('mysql_real_escape_string', $values)));
    }
    else{
      $query = $cSql;
    }

    $xRetval = mysql_query( $query, $this->rDbLink );

    if ( !$xRetval ) {
      /* Exception handling  */
      $cError = '!!ERROR: ';
      $cError .= mysql_error( $this->rDbLink ).$cSql;
      throw new Exception( $cError );
    }

    return $xRetval;
  }

  /**
   * Run the query and return it as an array
   *
   * @param string $cSql SQL statement
   * @param boolean $bOneDimensional if true only the first element will be returned
   * @return array
   */
  public function DoQueryAsArray( $cSql, $values = array(), $bOneDimensional = false ) {
    $aRetval = array();

    if ( ($xResult = $this->DoQuery($cSql, $values)) ) {
      while ( ($aRow = mysql_fetch_assoc($xResult)) ) {
        $aRetval[] = $aRow;
      }

      //If $bOneDimensional = TRUE. Only the first element
      if ( $bOneDimensional and count($aRetval) > 0 ) {
        $aRetval = $aRetval[0];
      }
    }

    return $aRetval;
  }

  /**
   * Get a single value of an query
   *
   * @param string $cSql
   * @param array $values
   * @return single value
   */
  public function DoQueryAsSingleValue( $cSql, $values = array() ) {
    $aRetval = $this->DoQueryAsArray( $cSql, $values, true );
    $xRetval = array_shift( $aRetval );

    return $xRetval;
  }

  /**
   * Generic function to create an insert query
   * @param string $cTable
   * @param array $aData
   * @return string
   */
  public function BuildInsertQuery( $cTable, $aData ) {
    $this->TryToConnect();
    $cFields = '';
    $cValues = '';
    $cSql = '';

    foreach ( $aData as $cField => $cValue ) {
      $cFields .= ', ' . $cField;

      $cValue = mysql_real_escape_string( strval($cValue) );

      $cValues .= ", '$cValue'";
    }

    // Are there $values?
    if ( $cFields and $cValues ) {
      // Delete first comma
      $cFields = substr( $cFields, 1 );
      $cValues = substr( $cValues, 1 );

      $cSql = " INSERT INTO $cTable ({$cFields}) VALUES ({$cValues})";
    }

    return $cSql;
  }

  /**
   * Generic function to build an update query
   *
   * @param string $cTable
   * @param array $aData
   * @param string $cWhereClause
   * @return string
   */
  public function BuildUpdateQuery( $cTable, $aData, $cWhereClause = '' ) {
    $this->TryToConnect();

    $cFields = '';
    $cSql = '';

    foreach ( $aData as $cField => $cValue ) {
      $cValue = mysql_real_escape_string( strval($cValue) );

      // Put everything between quotes, except if value equels NULL.
      // NULL should be a LITERAL in MySql
      if ( !(trim(strtoupper($cValue)) === 'NULL') ) {
        $cValue = "'" . $cValue . "'";
      }

      $cFields .= ", $cField = $cValue";
    }

    //Are there values?
    if ( $cFields ) {
      //Delete first comma
      $cFields = substr( $cFields, 1 );

      if ( $cWhereClause ) {
        $cWhereClause = 'WHERE ' . $cWhereClause;
      }

      $cSql = " UPDATE $cTable SET {$cFields} $cWhereClause";
    }

    return $cSql;
  }

  /**
   * Get the last inserted id
   * @return integer
   */
  public function GetLastInsertId() {
    return mysql_insert_id( $this->rDbLink );
  }

  /**
   * Checkif an table exists
   *
   * @param string $cTableName
   */
  public function TableExist( $cTableName ) {
    $cSql = "SHOW TABLES LIKE '{$cTableName}'";
    $bResult = ( bool )$this->DoQueryAsSingleValue( $cSql );

    return $bResult;
  }


}