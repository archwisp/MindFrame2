<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Interface for SQL adapter shared functionality modules
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
 * Interface for SQL adapter shared functionality modules
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
interface MindFrame2_Dbms_Schema_Adapter_ToSql_Package_SharedInterface
{
   /**
    * Escapes database object names for avoideance of reserved word naming
    * collisions
    *
    * @param string $name Element name to be escaped
    *
    * @return string
    */
   public function escapeDbElementName($name);

   public function escapeInput($value);
   
   /**
    * Select statement input sanitization
    *
    * @param string $value The value to be sanitized
    *
    * @return string
    */
   public function sanitizeSelectValue($value);
   
   /**
    * Input sanitization
    *
    * @param string $value The value to be sanitized
    *
    * @return string
    */
   public function sanitizeValue($value);
}
