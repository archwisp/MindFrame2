<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 *
 * Macro model for use in queries
 */

/**
 * Macro model for use in queries
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
class MindFrame2_Dbms_Query_Macro
{
   const ONE_WEEK_AGO = 'ONE_WEEK_AGO';
   const ONE_DAY_AGO = 'ONE_DAY_AGO';

   private $_value;

   public function __construct($value)
   {
      $this->_value = $value;
   }

   public function getValue()
   {
      return $this->_value;
   }
}
