<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * DBMS query result
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
 * DBMS query result
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Dbms_Result extends PDOStatement
{
   const FETCH_ASSOC = 2; // PDO::FETCH_ASSOC
   const FETCH_COLUMN = 7; //PDO::FETCH_COLUMN
   const FETCH_GROUP = 65536; //PDO::FETCH_GROUP
}
