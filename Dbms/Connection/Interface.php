<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * DBMS connection interface
 */

/**
 * DBMS connection interface
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
interface MindFrame2_Dbms_Connection_Interface
{
   /**
    * Builds the DSN for the database connection.
    *
    * @return string
    */
   public function buildDsn();

   /**
    * Retreives the DBMS identifier portion of the DSN
    *
    * @return string
    */
   public function getDbms();
}

