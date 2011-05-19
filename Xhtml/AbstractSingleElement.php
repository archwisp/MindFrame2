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
abstract class MindFrame2_Xhtml_AbstractSingleElement extends MindFrame2_Xhtml_Abstract
{
   /**
    * Construct
    *
    * @param array $options The HTML params of the element
    */
   public function __construct(array $options)
   {
      $this->options = $options;
   }

   /**
    * Executed as part of the command pattern chain
    *
    * @return string
    */
   public function render()
   {
      $xhtml = sprintf("<%s%s />", $this->tag, $this->renderOptions());

      return $xhtml;
   }
}
