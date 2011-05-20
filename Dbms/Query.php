<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Query model to be consumed by
 * MindFrame2_Dbms_Schema_Adapter_ToSql_Interface::buildQuerySql()
 */

/**
 * Query model to be consumed by
 * MindFrame2_Dbms_Schema_Adapter_ToSql_Interface::buildQuerySql()
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2011-01-21
 */
class MindFrame2_Dbms_Query
{
   const FUNCTION_HOUR = 'HOUR';
   const FUNCTION_AVG = 'AVG';
   const FUNCTION_STDDEV = 'STDDEV';
   const FUNCTION_SUM = 'SUM';

   /**
    * @var string
    */
   private $_from_database;

   /**
    * @var string
    */
   private $_from_table;

   /**
    * @var array
    */
   private $_group_by_parameters = array();

   /**
    * @var array
    */
   private $_joins = array();

   /**
    * @var array
    */
   private $_order_by_parameters = array();

   /**
    * @var array
    */
   private $_select_parameters = array();

   /**
    * @var array
    */
   private $_where_conditions = array();

   /**
    * Construct
    *
    * @param string $from_database Database to query
    * @param string $from_table Table to query
    */
   public function __construct($from_database, $from_table)
   {
      $this->_from_database = $from_database;
      $this->_from_table = $from_table;
   }

   /**
    * Adda a GROUP BY condition to the query
    *
    * @param MindFrame2_Dbms_Query_GroupBy $group_by Group by condition
    *
    * @return void
    */
   public function groupBy(MindFrame2_Dbms_Query_GroupBy $group_by)
   {
      $this->_group_by_parameters[] = $group_by;
   }

   /**
    * Adds a SELECT parameter to the query
    *
    * @param MindFrame2_Dbms_Query_Select $select Select parameter
    *
    * @return void
    */
   public function select(MindFrame2_Dbms_Query_Select $select)
   {
      $this->_select_parameters[] = $select;
   }

   /**
    * Adds a WHERE condition to the query
    *
    * @param MindFrame2_Dbms_Query_Where $where Where condition
    *
    * @return void
    */
   public function where(MindFrame2_Dbms_Query_Where $where)
   {
      $this->_where_conditions[] = $where;
   }

   /**
    * Retreives the name of the database which is to be queried
    *
    * @return string
    */
   public function getFromDatabase()
   {
      return $this->_from_database;
   }

   /**
    * Retreives the name of the table which is to be queried
    *
    * @return string
    */
   public function getFromTable()
   {
      return $this->_from_table;
   }

   /**
    * Retreives the group by condiftions for the query
    *
    * @return array
    */
   public function getGroupByParameters()
   {
      return $this->_group_by_parameters;
   }

   /**
    * Retreives the join conditions fro the query
    *
    * @return array
    */
   public function getJoins()
   {
      return $this->_joins;
   }

   /**
    * Retreives the ORDER BY conditions for the query
    *
    * @return array
    */
   public function getOrderByParameters()
   {
      return $this->_order_by_parameters;
   }

   /**
    * Retreives the select parameters for the query
    *
    * @return array
    */
   public function getSelectParameters()
   {
      return $this->_select_parameters;
   }

   /**
    * Retreives the where conditions for the query
    *
    * @return array
    */
   public function getWhereConditions()
   {
      return $this->_where_conditions;
   }
}
