<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Model for fields in the GROUP BY clause of a query
 */

/**
 * Model for fields in the GROUP BY clause of a query
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2011-01-21
 */
class MindFrame2_Dbms_Query_GroupBy
{
   public $Field;
   public $Function;
   public $Table;

   public function __construct($table, $field, $function)
   {
      $this->Field = $field;
      $this->Function = $function;
      $this->Table = $table;
   }
}
