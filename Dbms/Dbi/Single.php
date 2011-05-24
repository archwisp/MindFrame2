<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Database interface
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
 * Database interface
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Dbms_Dbi_Single implements MindFrame2_Dbms_Dbi_Interface
{
   /**
    * @var array
    */
   private $_attributes = array(
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_STATEMENT_CLASS => array('MindFrame2_Dbms_Result'),
      PDO::ATTR_TIMEOUT => 10
   );

   /**
    * @var resource
    */
   public $_connection;

   /**
    * @var bool
    */
   private $_connection_failed = FALSE;

   /**
    * @var MindFrame2_Dbms_Connection_Interface
    */
   private $_connection_model;

   /**
    * Construct
    *
    * @param MindFrame2_Dbms_Connection_Interface $connection_model Connection
    * definition
    */
   public function __construct(
      MindFrame2_Dbms_Connection_Interface $connection_model)
   {
      $this->_connection_model = $connection_model;
   }

   /**
    * Retrieves the error code associated with the last operation
    *
    * @return int or FALSE
    */
   public function errorCode()
   {
      return $this->_connectOnce()->errorCode();
   }

   /**
    * Retreives all error information associated with the last operation
    *
    * @return array or FALSE
    */
   public function errorInfo()
   {
      return $this->_connectOnce()->errorInfo();
   }

   /**
    * Excutes the specified command and returns the number of rows affected.
    *
    * @param string $sql SQL command
    *
    * @return int
    */
   public function exec($sql)
   {
      return $this->_connectOnce()->exec($sql);
   }

   /**
    * Retreives the auto-increment id associated with the last insert operation
    *
    * @return int or FALSE
    */
   public function lastInsertId()
   {
      return $this->_connectOnce()->lastInsertId();
   }

   /**
    * Returns the connection attributes
    *
    * @return array
    */
   public function getAttributes()
   {
      return $this->_attributes;
   }

   /**
    * Returns whether or not the connection has been marked as failed.
    *
    * @return bool
    */
   public function isDown()
   {
      return $this->_connection_failed;
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
    */
   public function query($sql, $fetch_mode)
   {
      $connection = $this->_connectOnce();

      if ($connection instanceof PDO)
      {
         $result = $connection->query($sql);
      }
      else
      {
         $result = $connection->query($sql, $fetch_mode);
      }

      if ((is_null($fetch_mode))
         || (!$result instanceof MindFrame2_Dbms_Result))
      {
         return $result;
      }

      return $result->fetchAll($fetch_mode);
   }

   /**
    * Sets the connection attribute to be used when getConnection() is called.
    * If a connection has already been created, this function attempts to set
    * the attribute on the already establised connection.
    *
    * @param string $attribute Connection attribute to be set
    * @param value $value Connection attribute value
    *
    * @return bool
    */
   public function setAttribute($attribute, $value)
   {
      $this->_attributes[$attribute] = $value;

      if ($this->_connection instanceof PDO)
      {
         return $this->_connection->setAttribute($attribute, $value);
      }
      else
      {
         return FALSE;
      }

      return TRUE;
   }

   /**
    * Marks the connection as failed
    *
    * @return void
    */
   public function setConnectionFailed()
   {
      $this->_connection_failed = TRUE;
   }

   /**
    * Checks the PDO drivers for the specified identifier
    *
    * @param string $dbms DBMS identifier
    *
    * @return void
    *
    * @throws RuntimeException if the specified dbms is not supported
    */
   private function _assertDbmsIsSupported($dbms)
   {
      if (!in_array($dbms, PDO::getAvailableDrivers()))
      {
         throw new RuntimeException(sprintf('Support for the specified ' .
            'DBMS (%s) is not configured in this PHP installation', $dbms));
      }
      // end if // (!in_array($dbms, PDO::getAvailableDrivers())) //
   }

   /**
    * Creates a connection if one does not already exist.
    *
    * @return resource
    *
    * @throws RuntimeException If the connection has been marked as failed
    * @throws UnexpectedValueException If the connection model has not been
    * implemented
    * @throws RuntimeException If a PDO connection cannot be created
    */
   private function _connectOnce()
   {
      if ($this->_connection_failed)
      {
         throw new RuntimeException('Connection has been marked as failed');
      }

      if ($this->_connection === NULL)
      {
         $connection_model = $this->_connection_model;

         // Catch and re-throw PDOExceptions because they contain database
         // login credentials in the back-trace.

         try
         {
            if ($connection_model instanceof MindFrame2_Dbms_Connection_Ip)
            {
               $this->_assertDbmsIsSupported($connection_model->getDbms());

               $this->_connection = new PDO(
                  $connection_model->buildDsn(),
                  $connection_model->getUsername(),
                  $connection_model->getPassword(),
                  $this->getAttributes()
               );
            }
            elseif ($connection_model instanceof MindFrame2_Dbms_Connection_File)
            {
               $this->_assertDbmsIsSupported($connection_model->getDbms());

               $this->_connection = new PDO(
                  $connection_model->buildDsn(),
                  NULL, NULL,
                  $this->getAttributes()
               );
            }
            elseif ($connection_model instanceof MindFrame2_Dbms_Connection_Rpc)
            {
               $this->_connection = new MindFrame2_XmlRpc_Client(
                  $connection_model->buildDsn(),
                  $connection_model->getUsername(),
                  $connection_model->getPassword());
            }
            else
            {
               throw new UnexpectedValueException(sprintf(
                  'Connection type not implemented',
                  get_class($connection_model)));
            }
            // end else // elseif ($connection_model instanceof ... //
         }
         catch (PDOException $exception)
         {
            throw new RuntimeException($exception->getMessage());
         }
      }
      // end if // ($this->_connection === NULL) //

      return $this->_connection;
   }
}
