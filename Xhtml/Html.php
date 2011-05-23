<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * Xhtml element builder
 */

/**
 * Xhtml element builder
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
class MindFrame2_Xhtml_Html extends MindFrame2_Xhtml_AbstractContainerElement
{
   protected $tag = 'html';

   /**
    * Renders the XHTML
    *
    * @return string
    */
   public function render()
   {
      $xhtml = sprintf('<!%s "%s" "%s">',
         'DOCTYPE html PUBLIC',
         '-//W3C//DTD XHTML 1.1//EN',
         'http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd');

      $xhtml .= "\n" . parent::render();

      return $xhtml;
   }
}
