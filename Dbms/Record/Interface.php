<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * DBMS record interface
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   MindFrame2
 * @author    Bryan C. Geraghty <bryan@ravensight.org>
 * @copyright 2005-2011 Bryan C. Geraghty
 * @license   http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link      https://github.com/archwisp/MindFrame2
 */

/**
 * DBMS record interface
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
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
