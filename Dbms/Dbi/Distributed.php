<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Distributed database interface
 */

/**
 * Distributed database interface
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2009-12-01
 *
 * @todo Improve join hashing
 */
class MindFrame2_Dbms_Dbi_Distributed implements MindFrame2_Dbms_Dbi_Interface
{
   /**
    * @var MindFrame2_Dbms_Schema_Adapter_ToSql_Interface
    */
   private $_adapter;

   /**
    * @var MindFrame2_Dbms_Cluster
    */
   private $_cluster;

   /**
    * @var array
    */
   private $_dbis = array();

   /**
    * @var MindFrame2_Dbms_Dbi_Interface
    */
   private $_last_dbi;

   /**
    * Initializes the object
    *
    * @param MindFrame2_Dbms_Schema_Adapter_ToSql_Interface $adapter DBM Adapter
    */
   public function __construct(
      MindFrame2_Dbms_Schema_Adapter_ToSql_Interface $adapter,
      MindFrame2_Dbms_Cluster $cluster)
   {
      $this->_adapter = $adapter;
      $this->_cluster = $cluster;
   }

   /**
    * Adds a DBMS interface to the pool
    *
    * @param MindFrame2_Dbms_Dbi_Interface $dbi DBMS interface
    *
    * @return void
    */
   public function addDbi(MindFrame2_NetworkNode $node,
      MindFrame2_Dbms_Dbi_Interface $dbi)
   {
      $this->_dbis[$node->getNodeId()] = $dbi;
   }

   /**
    * Retrieves the error code associated with the last operation
    *
    * @return int or FALSE
    */
   public function errorCode()
   {
      if (!$this->_last_dbi instanceof MindFrame2_Dbms_Dbi_Interface)
      {
         return FALSE;
      }

      return $this->_last_dbi->errorCode();
   }

   /**
    * Retreives all error information associated with the last operation
    *
    * @return array or FALSE
    */
   public function errorInfo()
   {
      if (!$this->_last_dbi instanceof MindFrame2_Dbms_Dbi_Interface)
      {
         return FALSE;
      }

      return $this->_last_dbi->errorInfo();
   }

   /**
    * Executes the specified query against all of the dbis in the collection
    * and returns the number of rows affected.
    *
    * @param string $sql SQL command
    *
    * @return int
    */
   public function exec($sql)
   {
      $transaction_id = MindFrame2_DateTime::buildMicroSecondTimeString();

      $node_ids = array_keys($this->_dbis);
      $primary_node_index = ($transaction_id % count($node_ids));
      $primary_node_id = $node_ids[$primary_node_index];
      $primary_dbi = $this->_dbis[$primary_node_id];

      $total_affected = $primary_dbi->exec($sql);

      $partners = $this->_cluster->getRelationshipsForNode($primary_node_id);
      
      if ($partners !== FALSE)
      {
         foreach ($partners as $partner)
         {
            // $db_suffix = $this->_buildReplicaDatabaseSuffix($partner);
            // $dbi->exec($sql);
         }
      }

      return $total_affected;
   }

   /**
    * Retreives the auto-increment id associated with the last insert operation
    *
    * @return int or FALSE
    */
   public function lastInsertId()
   {
      if (!$this->_last_dbi instanceof MindFrame2_Dbms_Dbi_Interface)
      {
         return FALSE;
      }

      return $this->_last_dbi->lastInsertId();
   }

   /**
    * Executes the specified query against the database and returns the
    * results. If fetch mode is NULL, the result object will be returned,
    * otherwise, the data from the resulting fetch will be returned.
    *
    * @param string $sql Statement to be executed
    * @param string $fetch_mode Fetch mode
    *
    * @return MindFrame2_Dbms_Result
    *
    * @throws RuntimeException If no DBIs have been defined
    */
   public function query($sql, $fetch_mode)
   {
      // If no connections have been defined, we need to throw an exception. If
      // there is only one connection, simply pass the query along and return
      // the results.

      $dbi_count = count($this->_dbis);

      if ($dbi_count === 0)
      {
         throw new RuntimeException('No connections have been defined');
      }
      elseif ($dbi_count === 1)
      {
         $dbi = reset($this->_dbis);

         return $dbi->query($sql, $fetch_mode);
      }

      $merge_table_name = $this->_buildMergeTableName();
      $data = array();
      
      // Create a temporary merge table from the first query into which
      // the results from all of the remaining queries will be inserted
      // and the final query will pull from.

      $dbis = $this->_dbis; 
      $merge_dbi = array_shift($dbis);

      $merge_table_sql = $this->_adapter->
         buildSelectIntoTemporaryTableSql($merge_table_name, $sql);

      $merge_dbi->exec($merge_table_sql);

      foreach ($dbis as $index => $dbi)
      {
         $merge_data = $dbi->query($sql, MindFrame2_Dbms_Result::FETCH_ASSOC);

         foreach ($merge_data as $row)
         {
            $insert_sql = $this->_adapter->
               buildInsertAdHocTableSql($merge_table_name, $row);
            
            $merge_dbi->exec($insert_sql);
         }
         // end foreach // ($merge_data as $row) //
      }
      // end foreach // ($connections as $connection) //

      // Now pull everything from the merge table and return the result object

      $final_select = $this->_adapter->
         buildSelectDdbiTableSql($merge_table_name, $sql);

      return $merge_dbi->query($final_select, $fetch_mode);
   }

   /**
    * Builds the merge table name
    *
    * @return string
    */
   private function _buildMergeTableName()
   {
      $table_id = MindFrame2_DateTime::buildMicroSecondTimeString();
      return '#merge_' . $table_id;
   }

   /**
    * Builds the replica dabase name for the given node
    *
    * @param MindFrame2_Dbms_Schema_Node $node Target node
    *
    * @return string
    */
   private function _buildReplicaDatabaseSuffix(MindFrame2_NetworkNode $node)
   {
      $replica_name = $node->getNodeId();
      $replica_name = substr($replica_name, -5);
      $replica_name = str_replace(':', '', $replica_name);
      $replica_name = sprintf('-%s', strtoupper($replica_name));

      return $replica_name;
   }
}
