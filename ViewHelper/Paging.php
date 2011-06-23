<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * View helper to provided paging functionality
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
 * View helper to provided paging functionality
 *
 * @category PHP
 * @package  MindFrame2
 * @author   Bryan C. Geraghty <bryan@ravensight.org>
 * @license  http://www.gnu.org/licenses/lgpl-3.0.txt GNU LGPL
 * @link     https://github.com/archwisp/MindFrame2
 */
class MindFrame2_ViewHelper_Paging
{
    public static function buildNumeric($current_page, $per_page, $item_total, $base_href)
    {
        MindFrame2_Core::assertArgumentIsInt($current_page, 1, 'current_page');
        MindFrame2_Core::assertArgumentIsInt($per_page, 2, 'per_page');
        MindFrame2_Core::assertArgumentIsInt($item_total, 3, 'item_total');

        if ($item_total <= $per_page)
        {
            return NULL;
        }

        // Calculate the initial page count by rounding and then, if there 
        // is a remainder, add an aditional page.

        $page_count = round($item_total / $per_page, 0);
        $page_count += ($item_total % $per_page === 0) ? 0 : 1;

        $pages = range(1, $page_count);

        $div = new MindFrame2_Xhtml_Div(
            'Pages: ', array('class' => 'paging'));

        $prefix = NULL;

        foreach ($pages as $page)
        {
            $href = $base_href . '/page/' . $page;

            $div->addContent($prefix); 

            if ($page == $current_page)
            {
                $element = new MindFrame2_Xhtml_Strong($page, array());
            }
            else
            {
                $element = new MindFrame2_Xhtml_A(
                    $page, array('href' => $href));
            }

            $div->addContent($element);

            $prefix = ' | ';
        }

        return $div->render();
    }
}

