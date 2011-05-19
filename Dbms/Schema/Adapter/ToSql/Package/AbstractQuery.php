<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 *
 * Abstract implementation of the SQL adapter query module
 */

/**
 * Abstract implementation of the SQL adapter query module
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2011-01-24
 */
abstract class MindFrame2_Dbms_Schema_Adapter_ToSql_Package_AbstractQuery
   extends MindFrame2_Dbms_Schema_Adapter_ToSql_Package_Abstract
   implements MindFrame2_Dbms_Schema_Adapter_ToSql_Package_QueryInterface
{
   /**
    * Wraps the specified input with the specified function. If the function is
    * NULL, the input will be returned.
    *
    * @param string $input Input string
    * @param int $function Function identifier
    *
    * @return string
    *
    * @throws UnexpectedValueException If the specified function is not
    * implemented.
    */
   protected function wrapWithFunction($input, $function)
   {
      if (is_null($function))
      {
         return $input;
      }

      switch ($function)
      {
         case MindFrame2_Dbms_Query::FUNCTION_AVG:

            return sprintf('AVG(%s)', $input);

         case MindFrame2_Dbms_Query::FUNCTION_HOUR:

            return sprintf('HOUR(%s)', $input);

         case MindFrame2_Dbms_Query::FUNCTION_STDDEV:

            return sprintf('STDDEV(%s)', $input);
         
         case MindFrame2_Dbms_Query::FUNCTION_SUM:

            return sprintf('SUM(%s)', $input);

         default:

            throw new UnexpectedValueException(
               sprintf('Unsupported function (%s)', $function));
      }
      // end switch // ($function) //

      return $output;
   }

   protected function runMacro(MindFrame2_Dbms_Query_Macro $macro)
   {
      $value = $macro->getValue();

      switch ($value)
      {
         case MindFrame2_Dbms_Query_Macro::ONE_DAY_AGO:

            return sprintf("DATE_SUB('%s', INTERVAL 1 DAY)",
               date('Y-m-d H:i:s'));

         case MindFrame2_Dbms_Query_Macro::ONE_WEEK_AGO:

            return sprintf("DATE_SUB('%s', INTERVAL 1 WEEK)",
               date('Y-m-d H:i:s'));

         default:

            throw new UnexpectedValueException(
               sprintf('Unsupported macro (%s)', $value));
      }
   }

   /**
    * Builds the SQL SELECT statement described by the specified query model.
    *
    * @param MindFrame2_Dbms_Query $query Query model
    *
    * @return string
    */
   public function buildQuerySql(MindFrame2_Dbms_Query $query)
   {
      $sql = sprintf("SELECT\n  %s\nFROM\n  %s.%s%s%s%s%s;",
         $this->_buildSelectParameters($query),
         $this->getSharedModule()->
            escapeDbElementName($query->getFromDatabase()),
         $this->getSharedModule()->
            escapeDbElementName($query->getFromTable()),
         $this->_buildJoins($query),
         $this->_buildWhereConditions($query),
         $this->_buildGroupByParameters($query),
         $this->_buildOrderByParameters($query));

      return $sql;
   }

   /**
    * Builds the GROUP BY clause of the query
    *
    * @param MindFrame2_Dbms_Query $query Query model
    *
    * @return string
    */
   private function _buildGroupByParameters(MindFrame2_Dbms_Query $query)
   {
      $parameters = $query->getGroupByParameters();
      $sql = array();

      foreach ($parameters as $parameter)
      {
         if ($parameter instanceof MindFrame2_Dbms_Query_GroupBy)
         {
            $table = is_null($parameter->Table) ? NULL : 
               $this->getSharedModule()->
               escapeDbElementName($parameter->Table);

            $field = $this->getSharedModule()->
               escapeDbElementName($parameter->Field);

            if (!is_null($table))
            {
               $field = $table . '.' . $field;
            }
               
            $field = $this->wrapWithFunction($field, $parameter->Function);

            $sql[] = $field;
         }
      }

      return empty($sql) ? NULL : "\nGROUP BY\n  " . join(",\n  ", $sql);
   }

   /**
    * Builds the JOIN clauses of the query
    *
    * @param MindFrame2_Dbms_Query $query Query model
    *
    * @return string
    */
   private function _buildJoins(MindFrame2_Dbms_Query $query)
   {
   }

   /**
    * Builds the ORDER BY clause of the query
    *
    * @param MindFrame2_Dbms_Query $query Query model
    *
    * @return string
    */
   private function _buildOrderByParameters(MindFrame2_Dbms_Query $query)
   {
   }

   /**
    * Builds the SELECT parameters of the query. If no columns are selected,
    * "*" will be returned.
    *
    * @param MindFrame2_Dbms_Query $query Query model
    *
    * @return string
    */
   private function _buildSelectParameters(MindFrame2_Dbms_Query $query)
   {
      $parameters = $query->getSelectParameters();
      $sql = array();

      foreach ($parameters as $parameter)
      {
         if ($parameter instanceof MindFrame2_Dbms_Query_Select)
         {
            $table = $this->getSharedModule()->
               escapeDbElementName($parameter->Table);

            $field = $this->getSharedModule()->
               escapeDbElementName($parameter->Field);

            $qualified_field = $this->wrapWithFunction(
               $table . '.' . $field, $parameter->Function);

            $alias = is_null($parameter->Alias) ? NULL :
               ' AS ' . $this->getSharedModule()->
               escapeDbElementName($parameter->Alias);

            $sql[] = sprintf('%s%s', $qualified_field, $alias);
         }
      }

      return empty($sql) ? '*' : join(",\n  ", $sql);
   }

   /**
    * Builds the WHERE clause of the query
    *
    * @param MindFrame2_Dbms_Query $query Query model
    *
    * @return string
    */
   private function _buildWhereConditions(MindFrame2_Dbms_Query $query)
   {
      $conditions = $query->getWhereConditions();
      $sql = array();

      foreach ($conditions as $condition)
      {
         if ($condition instanceof MindFrame2_Dbms_Query_Where)
         {
            $table = is_null($condition->Table) ? NULL : 
               $this->getSharedModule()->
               escapeDbElementName($condition->Table);
            
            $alias = $this->getSharedModule()->
               escapeDbElementName($condition->Alias);

            if (!is_null($table))
            {
               $alias = $table . '.' . $alias;
            }

            $value = $condition->Value;

            if ($value instanceof MindFrame2_Dbms_Query_Macro)
            {
               $value = $this->runMacro($value);
            }

            $sql[] = sprintf('%s %s %s', $alias, $condition->Operation, $value);
         }
      }

      return empty($sql) ? NULL : "\nWHERE\n  " . join("\n  AND", $sql);
   }
}
