<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Abstract implementation of SQL adapter shared functionality module
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
 * Abstract implementation of SQL adapter shared functionality module
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
abstract class MindFrame2_Dbms_Schema_Adapter_ToSql_Package_AbstractShared
   extends MindFrame2_Dbms_Schema_Adapter_Abstract
   implements MindFrame2_Dbms_Schema_Adapter_ToSql_Package_SharedInterface
{
   /**
    * Select statement input sanitization
    *
    * @param string $value The value to be sanitized
    *
    * @return string
    */
   public function sanitizeSelectValue($value)
   {
      $sanitized = $value;

      if ($sanitized === 'NULL')
      {
         $sanitized = 'IS NULL';
      }
      elseif ($sanitized === 'NOT NULL')
      {
         $sanitized = 'IS NOT NULL';
      }
      elseif (in_array(substr($value, 0, 2), array('> ', '< ')))
      {
         $sanitized = substr($value, 0, 2)
            . $this->sanitizeValue(substr($value, 2));
      }
      elseif (in_array(substr($value, 0, 3), array('>= ', '<= ')))
      {
         $sanitized = substr($value, 0, 3)
            . $this->sanitizeValue(substr($value, 3));
      }
      elseif (substr($value, 0, 7) == 'BETWEEN')
      {
         list($begin, $end) = explode(' AND ', substr($value, 8));

         $sanitized = sprintf('BETWEEN %s AND %s',
            $this->sanitizeValue($begin),
            $this->sanitizeValue($end));
      }
      elseif (!is_numeric($sanitized))
      {
         if (strpos($sanitized, ',') !== FALSE)
         {
            $values = explode(',', $sanitized);
            $values = array_map('trim', $values);
            $values = array_map(
               array($this, 'sanitizeValue'), $values);

            $sanitized = sprintf('IN (%s)', join(', ', $values));
         }
         elseif (strpos($sanitized, '*') !== FALSE)
         {
            $sanitized = 'LIKE ' . $this->sanitizeValue(
               str_replace('*', '%', $sanitized));
         }
         else
         {
            $sanitized = "= '" . $this->escapeInput($sanitized) . "'";
         }
      }
      else
      {
         $sanitized = "= '" . $this->escapeInput($sanitized) . "'";
      }

      return $sanitized;
   }
}
