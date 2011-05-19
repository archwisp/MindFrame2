<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * DBMS record interface
 */

/**
 * DBMS record interface
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2010-02-05
 */
interface MindFrame2_Dbms_Record_Interface
{
   /**
    * Returns the value of the record's primary key
    *
    * @return mixed
    */
   public function getPrimaryKey();

   /**
    * Sets the record's primary key
    *
    * @param double $value Primary key value
    *
    * @return void
    */
   public function setPrimaryKey($value);
}
