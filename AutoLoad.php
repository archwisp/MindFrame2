<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Source file auto-loader for intantiated objects
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
 * Source file auto-loader for intantiated objects
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_AutoLoad
{
   /**
    * Construct
    *
    * @param string $class_name The name of the class to be loaded
    *
    * @return void
    */
   public static function load($class_name)
   {
      if (class_exists($class_name))
      {
         return TRUE;
      }

      $file_name = self::convertClassToPath($class_name);

      $include_paths = explode(PATH_SEPARATOR, get_include_path());

      foreach ($include_paths as $include_path)
      {
         $full_path = $include_path . DIRECTORY_SEPARATOR . $file_name;

         if (file_exists($full_path))
         {
            include_once $file_name;
            return TRUE;
         }
         // end if // (file_exists($full_path)) //
      }
      // end foreach // ($include_paths as $include_path) //
      
      return FALSE;
   }

   public static function install()
   {
      spl_autoload_register('self::load');
   }

   /**
    * Maps the class name to the appropriate file
    *
    * @param string $class_name The name of the class to be loaded
    *
    * @return string
    */
   public static function convertClassToPath($class_name)
   {
      return str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';
   }
}
