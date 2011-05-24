<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Record mapper pool
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
 * Record mapper pool
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Dbms_Record_MapperPool
{
   /**
    * @var MindFrame2_Dbms_Schema_Adapter_ToSql_Interface
    */
   private $_adapter;

   /**
    * @var array
    */
   private $_instances = array();

   /**
    * @var array
    */
   private $_mapper_classes = array();

   /**
    * Construct
    *
    * @param MindFrame2_Dbms_Schema_Adapter_ToSql_Interface $adapter Database model
    * adapter
    */
   public function __construct(
      MindFrame2_Dbms_Schema_Adapter_ToSql_Interface $adapter)
   {
      $this->_adapter = $adapter;
   }

   /**
    * Creates an instance of the class registered with the specified key.
    *
    * @param string $key Class identifier
    *
    * @return MindFrame2_Dbms_Record_Mapper_Abstract
    *
    * @throws UnexpectedValueException If a class has not been registered with
    * the specified key
    */
   public function createInstanceOnce($key)
   {
      if (!array_key_exists($key, $this->_mapper_classes))
      {
         throw new UnexpectedValueException(sprintf(
            'No class has been registered with the specified key, "%s"', $key));
      }

      if (!array_key_exists($key, $this->_instances))
      {
         $class = $this->_mapper_classes[$key];

         $this->_instances[$key] = new $class['class'](
            $class['dbi'], $this->_adapter, $this);
      }

      return $this->_instances[$key];
   }

   /**
    * Add a class to the registry so that instances of it can be managed by
    * this pool.
    *
    * @param string $key Class identifier
    * @param string $class Class to be registered
    * @param MindFrame2_Dbms_Dbi_Interface $dbi Database interface
    *
    * @return bool
    */
   public function registerMapperClass($key, $class, MindFrame2_Dbms_Dbi_Interface $dbi)
   {
      $this->_mapper_classes[$key] = array('class' => $class, 'dbi' => $dbi);
   }
}
