<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Xhtml element builder
 */

/**
 * Xhtml element builder
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 * @since 2009-09-16
 */
abstract class MindFrame2_Xhtml_Abstract implements MindFrame2_Xhtml_Interface
{
   /**
    * @var string
    */
   protected $tag;

   /**
    * @var array
    */
   protected $options = array();

   /**
    * @var array
    */
   protected $required_options = array();

   /**
    * Renders the options array into an XHTML string
    *
    * @return string
    */
   protected function renderOptions()
   {
      $xhtml = array();

      foreach ($this->options as $key => $value)
      {
         $xhtml[] = sprintf(' %s="%s"', $key, $value);
      }
      // end foreach // ($this->options as $key => $value) //

      $xhtml = join($xhtml);

      return $xhtml;
   }

   /**
    * Returns the options in the collection
    *
    * @return array
    */
   public function getOptions()
   {
      return $this->options;
   }

   /**
    * Returns the value of the specified option
    *
    * @param string $name The name of the option to find
    *
    * @return string or FALSE
    */
   public function getOptionByName($name)
   {
      if (array_key_exists($name, $this->options))
      {
         return $this->options[$name];
      }

      return FALSE;
   }

   /**
    * Returns the $tag property of the object
    *
    * @return string
    */
   public function getTag()
   {
      return $this->tag;
   }

   /**
    * Sets an option
    *
    * @param string $name The key portion of the option
    * @param string $value The value portion of the option
    *
    * @return bool
    */
   public function setOption($name, $value)
   {
      $this->options[$name] = $value;

      return TRUE;
   }
}
