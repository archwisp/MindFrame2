<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Macro model for use in queries
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
 * Macro model for use in queries
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
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
