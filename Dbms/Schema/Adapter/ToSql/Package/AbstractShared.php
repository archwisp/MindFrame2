<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 *
 * Abstract implementation of SQL adapter shared functionality module
 */

/**
 * Abstract implementation of SQL adapter shared functionality module
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2011-01-24
 */
abstract class MindFrame2_Dbms_Schema_Adapter_ToSql_Package_AbstractShared
   extends MindFrame2_Dbms_Schema_Adapter_Abstract
   implements MindFrame2_Dbms_Schema_Adapter_ToSql_Package_SharedInterface
{
   /**
    * Escapes database object names for avoideance of reserved word naming
    * collisions
    *
    * @param string $name Element name to be escaped
    *
    * @return string
    */
   public abstract function escapeDbElementName($name);

   /**
    * Input sanitization
    *
    * @param string $value The value to be sanitized
    *
    * @return string
    */
   public abstract function sanitizeValue($value);
}
