<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Abstract base object functionality
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
 * Abstract base object functionality
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
abstract class MindFrame2_Object
{
   /**
    * Returns the PHP resource id for the object
    *
    * @return int
    */
   public function getObjectId()
   {
      $object_id = spl_object_hash($this);

      return $object_id;
   }

   /**
    * Asserts that the specified object property is not empty. This is
    * especially useful when implementing template objects with protected
    * properties to be defined in sub-classes.
    *
    * @param string $property_name Property to be validated
    *
    * @return void
    *
    * @throws UnexpectedValueException If the specified property is empty
    */
   protected function assertPropertyIsNotEmpty($property_name)
   {
      $value = isset($this->$property_name)
         ? trim($this->$property_name) : NULL;

      if (empty($value))
      {
         throw new UnexpectedValueException(sprintf(
            'Object property, "%s" cannot be empty', $property_name));
      }
   }
}
