<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file DBMS DBI Interface
 */

/**
 * DBMS DBI Interface
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2010-12-10
 */
interface MindFrame2_Dbms_Dbi_Interface
{
   /**
    * Retrieves the error code associated with the last operation
    *
    * @return int or FALSE
    */
   public function errorCode();

   /**
    * Retreives all error information associated with the last operation
    *
    * @return array or FALSE
    */
   public function errorInfo();

   /**
    * Executes the specified statement against the database and returns the
    * number of rows affected.
    *
    * @param string $sql Statement to be executed
    *
    * @return int
    */
   public function exec($sql);

   /**
    * Retreives the auto-increment id associated with the last insert operation
    *
    * @return int or FALSE
    */
   public function lastInsertId();

   /**
    * Executes the specified query against the database and returns the
    * results. If fetch mode is NULL, the result object will be returned, 
    * otherwise, the data from the resulting fetch will be returned.
    *
    * @param string $sql Statement to be executed
    * @param string $fetch_mode Fetch mode
    *
    * @return MindFrame2_Dbms_Result
    */
   public function query($sql, $fetch_mode);
}

