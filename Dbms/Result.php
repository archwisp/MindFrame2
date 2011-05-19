<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * DBMS query result
 */

/**
 * DBMS query result
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2010-12-16
 */
class MindFrame2_Dbms_Result extends PDOStatement
{
   const FETCH_ASSOC = 2; // PDO::FETCH_ASSOC
   const FETCH_COLUMN = 7; //PDO::FETCH_COLUMN
   const FETCH_GROUP = 65536; //PDO::FETCH_GROUP
}
