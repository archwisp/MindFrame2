<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * MindFrame2_Sniffs_Whitespace_ScopeIndentSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   CVS: $Id: ScopeIndentSniff.php,v 1.12 2008/03/03 02:51:39 squiz Exp $
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

if (class_exists('Generic_Sniffs_WhiteSpace_ScopeIndentSniff', TRUE) === FALSE)
{
    $error = 'Class Generic_Sniffs_WhiteSpace_ScopeIndentSniff not found';
    throw new PHP_CodeSniffer_Exception($error);
}

/**
 * MindFrame2_Sniffs_Whitespace_ScopeIndentSniff.
 *
 * Checks that control structures are structured correctly, and their content
 * is indented correctly.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: 1.1.0
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class MindFrame2_Sniffs_WhiteSpace_ScopeIndentSniff
    extends Generic_Sniffs_WhiteSpace_ScopeIndentSniff
{
    /**
     * The number of spaces code should be indented.
     *
     * @var int
     */
    public $indent = 3;

    /**
     * Does the indent need to be exactly right.
     *
     * If TRUE, indent needs to be exactly $ident spaces. If FALSE,
     * indent needs to be at least $ident spaces (but can be more).
     *
     * @var bool
     */
    public $exact = FALSE;

    /**
     * Any scope openers that should not cause an indent.
     *
     * @var array(int)
     */
    public $nonIndentingScopes = array();
}
