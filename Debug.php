<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Debug class
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
 * Debug class
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_Debug extends Exception
{
   private static $_log_file;

   public static function install($log_file)
   {
      self::$_log_file = $log_file;
      set_exception_handler(array('MindFrame2_Debug', 'exceptionHandler'));
      set_error_handler(array('MindFrame2_Debug', 'errorHandler'));
   }

   public static function errorHandler($severity, $message, $filepath, $line)
   {
      $output = sprintf('%s:%s %s', $filepath, $line, $message);
      
      if (ini_get('display_errors') === '1')
      {
         new MindFrame2_Debug(new exception($output));
      }

      if (ini_get('log_errors') === '1')
      {
         error_log($output);
      }

      if (!in_array($severity, array(E_STRICT, E_NOTICE, E_USER_NOTICE)))
      {
         exit($severity);
      }
   }

   public static function exceptionHandler(Exception $exception)
   {
      new MindFrame2_Debug($exception);
      exit;
   }

   /**
    * Parses and outputs the debug message
    *
    * @param mixed $message Message to be displayed
    */
   public function __construct($message)
   {
      $file = $this->file;
      $line = $this->line;
      $date = date('Y-m-d H:i:s');

      $header = $date . ' ' . $file . ':' . $line . "\n";
      $header .= str_repeat('=', strlen($date)) . "\n";

      if ($message instanceof Exception)
      {
         $output = $header . $message->getMessage()
            . "\n" . $message->getTraceAsString();
      }
      elseif (is_null($message))
      {
         $output = $header . '{NULL}' . "\n";
      }
      elseif (is_bool($message))
      {
         $output = $header . (($message === TRUE)
            ? '{TRUE}' : '{FALSE}') . "\n";
      }
      else
      {
         $output = $header . print_r($message, TRUE) . "\n";
      }

      if (!empty(self::$_log_file))
      {
         file_put_contents(self::$_log_file, $output . "\n", FILE_APPEND);
      }
      elseif (isset($_SERVER['HTTP_USER_AGENT']))
      {
         printf("<pre class=\"Debug\">\n%s</pre>\n", htmlentities($output));
      }
      else
      {
         if ($message instanceof Exception)
         {
            $pipe = fopen('php://stderr', 'w+');
         }
         else
         {
            $pipe = fopen('php://stdout', 'w+');
         }

         fputs($pipe, $output . "\n");
         fclose($pipe);
      }
   }
}
