<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * @file
 * MVC View helper for building a link list
 */

/**
 * MVC View helper for building a link list
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
class MindFrame2_ViewHelper_LinkList
{
   /**
    * @var array
    */
   private $_list_items = array();

   /**
    * Adds a list item to the collection
    *
    * @param string $label Link label
    * @param string $href Hyper-link
    *
    * @return void
    */
   public function addListItem($label, $href)
   {
      $item = new MindFrame2_Xhtml_Li(NULL, array());
      $item->addContent(new MindFrame2_Xhtml_A($label, array('href' => $href)));
      $this->_list_items[] = $item;
   }

   /**
    * Builds a DIV with the css class of LinkList in which link items are
    * encapsulated
    *
    * @param string $caption List caption
    *
    * @return MindFrame2_Xhtml_Div
    */
   public function buildList($caption)
   {
      $div = new MindFrame2_Xhtml_Div(NULL, array('class' => 'LinkList'));

      $div->addContent(new  MindFrame2_Xhtml_H3(
         $caption, array('class' => 'Caption')));

      $div->addContent($ul = new MindFrame2_Xhtml_Ul(
         NULL, array('class' => 'Links')));

      foreach ($this->_list_items as $item)
      {
         $ul->addContent($item);
      }

      return $div;
   }
}
