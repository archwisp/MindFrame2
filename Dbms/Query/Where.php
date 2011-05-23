<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Model for fields in the WHERE clause of a query
 */

/**
 * Model for fields in the WHERE clause of a query
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
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
