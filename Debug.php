<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Debug class
 */

/**
 * Debug class
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2008-10-08
 */
class MindFrame2_Debug extends Exception
{
   public static $Log_File;

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
         $output = $header . (($message === TRUE) ? '{TRUE}' : '{FALSE}') . "\n";
      }
      else
      {
         $output = $header . print_r($message, TRUE) . "\n";
      }

      if (!empty(self::$Log_File))
      {
         file_put_contents(self::$Log_File, $output . "\n", FILE_APPEND);
      }
      elseif (isset($_SERVER['HTTP_USER_AGENT']))
      {
         echo '<pre class="Debug">' . htmlentities($output) . '</pre>';
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
