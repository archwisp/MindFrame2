<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Model for fields in the WHERE clause of a query
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
 * Model for fields in the WHERE clause of a query
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Dbms_Query_Where
{
   public $Alias;
   public $Operation;
   public $Table;
   public $Value;

   public function __construct($table, $alias, $operation, $value)
   {
      $this->Alias = $alias;
      $this->Operation = $operation;
      $this->Table = $table;
      $this->Value = $value;
   }
}
