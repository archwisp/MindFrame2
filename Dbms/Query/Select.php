<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Model for fields in the SELECT clause of a query
 */

/**
 * Model for fields in the SELECT clause of a query
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2011-01-21
 */
class MindFrame2_Dbms_Query_Select
{
   public $Alias;
   public $Field;
   public $Function;
   public $Table;

   public function __construct($table, $field, $function, $alias)
   {
      $this->Alias = $alias;
      $this->Field = $field;
      $this->Function = $function;
      $this->Table = $table;
   }
}
