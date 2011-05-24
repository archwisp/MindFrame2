<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * Xhtml element builder
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
 * Xhtml element builder
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
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
