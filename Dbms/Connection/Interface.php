<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * DBMS connection interface
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
 * DBMS connection interface
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
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

