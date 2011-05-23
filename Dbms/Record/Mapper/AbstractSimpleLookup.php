<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * DBMS record mapper responsible for handling object->relational dbms
 * conversion and vice versa
 */

/**
 * DBMS record mapper responsible for handling object->relational dbms
 * conversion and vice versa
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
abstract class MindFrame2_Dbms_Record_Mapper_AbstractSimpleLookup
   extends MindFrame2_Dbms_Record_Mapper_Abstract
{
   /**
    * @var array
    */
   protected $default_order_by_columns = array('Label' => 'ASC');

   /**
    * Name of the model class which will be loaded by the factory
    *
    * @var string
    */
   protected $model_class;

   /**
    * @var string
    */
   protected $table_name;

   /**
    * Creates a model by loading a record from the database
    *
    * @param string $label Record label
    *
    * @return XSIM_Dbms_Schema_Abstract or FALSE
    */
   public function loadByLabel($label)
   {
      $prefix = $this->getTableName() .
         $this->getAdapter()->getFieldDelimiter();

      $records = $this->fetchRecords(
         array($prefix . 'Label' => $label), array(), 1);

      if ($records !== FALSE)
      {
         return $this->load($records[0]);
      }

      return FALSE;
   }

   /**
    * Creates a MindFrame2_Dbms_Schema_Abstract model from the specified record
    *
    * @param array $record Database record to load from
    *
    * @return MindFrame2_Dbms_Schema_Abstract
    *
    * @throws UnexpectedValueException If the model class is not defined
    * @throws RuntimeException If the table does not have a simple primary key
    */
   protected function load(array $record)
   {
      $model_class = $this->model_class;

      if (is_null($model_class))
      {
         throw new UnexpectedValueException(
            'Connot proceed without a model class');
      }

      $pk_field_name = $this->getSimplePrimaryKeyFieldName();

      if ($pk_field_name === FALSE)
      {
         throw new RuntimeException(
            'This method can only be used for tables ' .
            'containing a primary key consisting of a single field');
      }

      $model = new $model_class($record[$pk_field_name], $record['Label']);
      $record_id = $model->getPrimaryKey();
      $this->addOffspring($record_id, $model);

      return $model;
   }

   /**
    * Converts the model data into the array format which is expected by the
    * adapter
    *
    * @param MindFrame2_Dbms_Schema_Abstract $model The Status model
    *
    * @return array
    */
   protected function buildWriteData($model)
   {
      $prefix = $this->buildFieldPrefix();
      $record_id = $model->getPrimaryKey();

      return array(
         $prefix . $this->getSimplePrimaryKeyFieldName() => $record_id,
         $prefix . 'Label' => $model->getLabel());
   }
}
