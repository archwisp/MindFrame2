<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * DBMS record mapper responsible for handling object->relational dbms
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
 * DBMS record mapper responsible for handling object->relational dbms
 * conversion and vice versa
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
abstract class MindFrame2_Dbms_Record_Mapper_Abstract extends MindFrame2_Object
{
   /**
    * @var MindFrame2_Dbms_Schema_Adapter_ToSql_Interface
    */
   private $_adapter;

   /**
    * @var MindFrame2_Dbms_Dbi_Interface
    */
   private $_dbi;

   /**
    * @var array
    */
   private $_default_order_by_columns = array();

   /**
    * @var string
    */
   private $_model_class;

   /**
    * Contains references to the objects created by this factory
    *
    * @var array
    */
   private $_offspring = array();

   /**
    * @var MindFrame2_Dbms_Record_MapperPool
    */
   private $_pool;

   /**
    * @var string
    */
   private $_table_name;

   /**
    * Construct
    *
    * @param MindFrame2_Dbms_Dbi_Interface $dbi Database interface
    * @param MindFrame2_Dbms_Schema_Adapter_ToSql_Interface $adapter Database model
    * adapter
    * @param MindFrame2_Dbms_Record_MapperPool $pool Instance mapper
    */
   public function __construct(MindFrame2_Dbms_Dbi_Interface $dbi,
      MindFrame2_Dbms_Schema_Adapter_ToSql_Interface $adapter,
      MindFrame2_Dbms_Record_MapperPool $pool = NULL)
   {
      $this->_dbi = $dbi;
      $this->_adapter = $adapter;
      $this->_pool = $pool;

      $this->init();
   }

   /**
    * Extension routine for initializing model class, table name, and order-by
    * columns
    *
    * @return void
    */
   protected abstract function init();

   /**
    * The specified database record is translated into an instance of the
    * object model with which this factory is associated. If the record has
    * lookup tables associated with it, this method is resposible for calling
    * the factories associated with those tables and loading the associated
    * records.
    *
    * @param array $record Database record to load
    *
    * @return MindFrame2_Dbms_Record_Interface
    */
   protected abstract function load(array $record);

   /**
    * Converts the model data into the array format which is expected by the
    * adapter
    *
    * @param object $model The model object
    *
    * @return array
    */
   protected abstract function buildWriteData($model);

   /**
    * Creates a time-based identifier if the record has a simple primary key
    * defined and that key is null, the model is passed through the abstract
    * buildWriteData method and the resulting array is fed to the database
    * adapter insert statement builder, and the statement is executed.
    *
    * Return Values:
    *
    * If the statement execution generates an exception, it is passed though.
    * If there is some other kind of failure, FALSE is returned. If execution
    * was successful and a new identifier was generated, that identifier is
    * returned. If execution is successful, but no identifier was generated,
    * the number or records affected is returned.
    *
    * @param XSIM_Dbms_Schema_Abstract $model The record model
    *
    * @return mixed
    */
   public function create(MindFrame2_Dbms_Record_Interface $model)
   {
      $this->validateModelClass($model);

      // We'll take advantage of the fact that getSimplePrimaryKeyFieldName
      // returns FALSE if the primary key is not in fact a simple key because
      // we only want to automatically generate an ID on records with simple
      // primary keys. Records with complex primary keys either need to require
      // them at the model construct or over-ride this method.

      $simple_pk = $this->getSimplePrimaryKeyFieldName();

      if ($simple_pk !== FALSE)
      {
         if (is_null($model->getPrimaryKey()))
         {
            // Note: $new_id variable is used later

            $new_id = $this->generateTimeBasedId();
            $model->setPrimaryKey($new_id);
         }
         // end if // (is_null($model->getPrimaryKey())) //
      }
      // end if // ($simple_pk !== FALSE) //

      $data = $this->buildWriteData($model);
      $result = $this->insertRecord($data);

      if ($result === FALSE)
      {
         return FALSE;
      }
      elseif (isset($new_id))
      {
         return $new_id;
      }
      else
      {
         return $result;
      }
      // end else //
   }

   /**
    * Writes the specified model back to the database and catches duplicate key
    * violation exceptions.
    *
    * @param MindFrame2_Dbms_Record_InterfaceRecord $model The record model
    *
    * @return mixed
    *
    * @throws Exception If the exception is not a duplicated record exception
    */
   public function createAndIgnoreDuplicates(
      MindFrame2_Dbms_Record_Interface $model)
   {
      if ($this->loadByPrimaryKey($model->getPrimaryKey()) !== FALSE)
      {
         return FALSE;
      }

      return $this->create($model);
   }

   /**
    * Deletes the database record represented by the specified model
    *
    * @param MindFrame2_Dbms_Record_Interface $model The record model
    *
    * @return bool
    */
   public function delete(MindFrame2_Dbms_Record_Interface $model)
   {
      $this->validateModelClass($model);
      $data = $this->buildWriteData($model);
      $result = $this->deleteRecord($data);

      return $result;
   }

   /**
    * Loads the specified number of records from the table. If no records are
    * retreived, FALSE is returned.
    *
    * @param int $limit Number of records to retreive
    *
    * @return array or FALSE
    */
   public function loadAll($limit)
   {
      MindFrame2_Validate::argumentIsInt($limit, 1, 'limit');

      $order_by_columns = $this->buildDefaultOrderByColumns();
      $records = $this->fetchRecords(array(), $order_by_columns, $limit);

      if ($records !== FALSE)
      {
         $models = array();

         foreach ($records as $record)
         {
            $models[] = $this->load($record);
         }
         // end foreach // ($records as $record) //

         return $models;
      }
      // end if // ($records !== FALSE) //

      return FALSE;
   }

   /**
    * Attempts to load a record by primary key
    *
    * @param mixed $value Primary key to search for
    *
    * @return MindFrame2_Dbms_Record_Interface or FALSE
    */
   public function loadByPrimaryKey($value)
   {
      MindFrame2_Validate::argumentIsNotEmpty($value, 1, 'value');

      if (($offspring = $this->getOffspring($value)) !== FALSE)
      {
         return $offspring;
      }

      $search_data = $this->_buildPrimaryKeySearchData($value);

      $records = $this->fetchRecords($search_data, array(), 0);

      if ($records !== FALSE)
      {
         $record = reset($records);
         return $this->load($record);
      }
      // end if // ($records !== FALSE) //

      return FALSE;
   }

   /**
    * Creates an array of models by loading the record from the database
    *
    * @param int $timestamp Timestamp
    * @param int $limit How many records to retreive
    *
    * @return array or FALSE
    */
   public function loadRecent($timestamp, $limit)
   {
      MindFrame2_Validate::argumentIsIntOrNull($timestamp, 1, 'timestamp');
      MindFrame2_Validate::argumentIsInt($limit, 2, 'limit');

      if ($timestamp !== NULL)
      {
         $prefix = $this->buildFieldPrefix();
         $search_data = array(
            $prefix . 'Created_Date' => '>= ' . date('Y-m-d H:i:s', $timestamp)
         );
      }
      else
      {
         $search_data = array();
      }

      $order_by_columns = $this->buildRecentOrderByColumns();
      $records = $this->fetchRecords($search_data, $order_by_columns, $limit);

      if ($records !== FALSE)
      {
         $alerts = array();

         foreach ($records as $record)
         {
            $alerts[] = $this->load($record);
         }
         // end foreach // ($records as $record) //

         return $alerts;
      }
      // end if // ($records !== FALSE) //

      return FALSE;
   }

   /**
    * Writes the specified model back to the database
    *
    * @param MindFrame2_Dbms_Record_Interface $model The record model
    *
    * @return bool
    */
   public function update(MindFrame2_Dbms_Record_Interface $model)
   {
      $this->validateModelClass($model);
      $data = $this->buildWriteData($model);
      $result = $this->updateRecord($data);

      return $result;
   }

   /**
    * Register the reference to a newly created object for re-use later
    *
    * @param mixed $record_id Database identifier
    * @param MindFrame2_Dbms_Record_Interface $model Database record model
    *
    * @return void
    */
   protected function addOffspring($record_id,
      MindFrame2_Dbms_Record_Interface $model)
   {
      if (is_array($record_id))
      {
         $record_id = implode(':', $record_id);
      }

      $this->_offspring[$record_id] = $model;
   }

   /**
    * Builds an array of fully-qualified columns out of the configured default
    * order-by columns for use in building select statements.
    *
    * @return array
    */
   protected function buildDefaultOrderByColumns()
   {
      $field_prefix = $this->buildFieldPrefix();
      $order_by_columns = array();

      foreach ($this->_default_order_by_columns as $field => $direction)
      {
         $order_by_columns[$field_prefix . $field] = $direction;
      }

      return $order_by_columns;
   }

   /**
    * Builds the prefix used to fully-qualify field names
    *
    * @return string
    */
   protected function buildFieldPrefix()
   {
      return $this->getTableName() . $this->_adapter->getFieldDelimiter();
   }

   /**
    * Builds the order by columns that will be used when loading recent records
    *
    * @return array
    */
   protected function buildRecentOrderByColumns()
   {
      $table_name = $this->getTableName();

      $prefix = $table_name . $this->_adapter->getFieldDelimiter();

      $pk_field_names = $this->_adapter->
         getDatabase()->getTablePrimaryKeyFieldNames($table_name);

      $order = array();

      foreach ($pk_field_names as $field_name)
      {
         $order[$prefix . $field_name] = 'DESC';
      }

      return $order;
   }

   /**
    * Deletes the specified record from the table handled by the factory
    *
    * @param array $delete_data Record data
    *
    * @return int or FALSE
    */
   protected function deleteRecord(array $delete_data)
   {
      $sql = $this->_adapter
         ->buildDeleteTableSql($this->getTableName(), $delete_data);

      return $this->_dbi->exec($sql);
   }

   /**
    * Searches the specified table with the specified search data and returns
    * the records found
    *
    * @param array $search_data Data to search with
    * @param array $order_by_columns How the results should be ordered
    * @param int $limit How many records to retreive
    *
    * @return array
    */
   protected function fetchRecords(
      array $search_data, $order_by_columns, $limit)
   {
      $sql = $this->_adapter->buildSelectTableSql(
         $this->getTableName(), $search_data, $order_by_columns, $limit);

      $query = $this->_dbi->query($sql, NULL);
      $data = $query->fetchAll(MindFrame2_Dbms_Result::FETCH_ASSOC);

      if (empty($data))
      {
         return FALSE;
      }

      return $data;
   }

   /**
    * Generates a string composed of a unix timestamp followed by a decimal
    * point and 6 digits representing microseconds.
    *
    * @return string
    */
   protected function generateTimeBasedId()
   {
      return MindFrame2_DateTime::buildMicroSecondTimeString();
   }

   /**
    * Returns the database model adapter
    *
    * @return MindFrame2_Dbms_Schema_Adapter_ToSql_Interface
    */
   protected function getAdapter()
   {
      return $this->_adapter;
   }

   /**
    * Returns the database interface
    *
    * @return MindFrame2_Dbms_Dbi_Interface
    */
   protected function getDbi()
   {
      return $this->_ddbi;
   }

   /**
    * Returns the reference to the object with the specified ID if it exists
    *
    * @param mixed $record_id Database identifier
    *
    * @return MindFrame2_Dbms_Record_Interface or FALSE
    */
   protected function getOffspring($record_id)
   {
      if (is_array($record_id))
      {
         $record_id = implode(':', $record_id);
      }

      if (isset($this->_offspring[$record_id]))
      {
         return $this->_offspring[$record_id];
      }

      return FALSE;
   }

   /**
    * Returns the primary key field names.
    *
    * @return array
    */
   protected function getPrimaryKeyFieldNames()
   {
      $pk_field_names = $this->_adapter->getDatabase()->
         getTablePrimaryKeyFieldNames($this->getTableName());

      return $pk_field_names;
   }

   /**
    * Returns the instance mapper used for communicating with siblings if it
    * has been specified.
    *
    * @return MindFrame2_Dbms_Record_MapperPool or FALSE
    */
   protected function getPool()
   {
      if (!$this->_pool instanceof MindFrame2_Dbms_Record_MapperPool)
      {
         return FALSE;
      }

      return $this->_pool;
   }

   /**
    * Retreives and instance of the mapper class identified by the specified
    * key
    *
    * @param string $key Class identifier
    *
    * @return MindFrame2_Dbms_Record_Mapper_Interface
    */
   protected function getSibling($key)
   {
      if (($pool = $this->getPool()) === FALSE)
      {
         return FALSE;
      }

      return $pool->createInstanceOnce($key);
   }

   /**
    * Returns the primary key field name for tables which have a primary key
    * consisting of only one field.
    *
    * @return string or FALSE
    */
   protected function getSimplePrimaryKeyFieldName()
   {
      $pk_field_names = $this->_adapter->getDatabase()->
         getTablePrimaryKeyFieldNames($this->getTableName());

      if (count($pk_field_names) !== 1)
      {
         return FALSE;
      }

      return reset($pk_field_names);
   }

   /**
    * Returns the name of the table in which the records for the model exist
    *
    * @return string
    */
   protected function getTableName()
   {
      return $this->_table_name;
   }

   /**
    * Inserts the specified record into the table handled by the factory and
    * returns the number of rows affected.
    *
    * @param array $insert_data Record data
    *
    * @return int or FALSE
    */
   protected function insertRecord(array $insert_data)
   {
      $sql = $this->_adapter
         ->buildInsertTableSql($this->getTableName(), $insert_data);

      return $this->_dbi->exec($sql);
   }

   protected function setDefualtOrderByColumns(array $default_order_by_columns)
   {
      $this->_default_order_by_columns = $default_order_by_columns;
   }

   protected function setModelClass($model_class)
   {
      $this->_model_class = $model_class;
   }

   protected function setTableName($table_name)
   {
      $this->_table_name = $table_name;
   }

   /**
    * Validates that the class of the specified model matches what the factory
    * is configured to handle.
    *
    * @param MindFrame2_Dbms_Record_Interface $model Abstract model object
    *
    * @return void
    *
    * @throws InvalidArgumentException if the class of the model does not match
    * the model class of the factory
    */
   protected function validateModelClass(MindFrame2_Dbms_Record_Interface $model)
   {
      $expected_model_class = $this->_model_class;

      if (!$model instanceof $expected_model_class)
      {
         throw new InvalidArgumentException(
            sprintf('Expected instance of (%s), instance of (%s) given',
            $expected_model_class, get_class($model)));
      }
   }

   /**
    * Updates the table handled by the factory with the specified record
    *
    * @param array $update_data Record data
    *
    * @return int or FALSE
    */
   protected function updateRecord(array $update_data)
   {
      $sql = $this->_adapter
         ->buildUpdateTableSql($this->getTableName(), $update_data);

      return $this->_dbi->exec($sql);
   }

   /**
    * Builds the search data array for loading data by the primary key. It
    * handles simple and composite primary keys.
    *
    * @param mixed $value Primary key value
    *
    * @return array
    *
    * @throws RuntimeException If the value type has not been implemented
    */
   private function _buildPrimaryKeySearchData($value)
   {
      $search_data = array();
      $prefix = $this->buildFieldPrefix();
      $simple_pk_field_name = $this->getSimplePrimaryKeyFieldName();

      if ($simple_pk_field_name !== FALSE)
      {
         $search_data[$prefix . $simple_pk_field_name] = $value;
      }
      elseif (is_array($value))
      {
         $pk_field_names = $this->getPrimaryKeyFieldNames();

         foreach ($pk_field_names as $key => $pk_field_name)
         {
            $search_data[$prefix . $pk_field_name] = $value[$key];
         }
         // end foreach // ($pK_field_names as $ket => $pk_field_name) //
      }
      else
      {
         throw new RuntimeException('Unsupported value type.');
      }
      // end else // elseif (is_array($value)) //

      return $search_data;
   }
}
