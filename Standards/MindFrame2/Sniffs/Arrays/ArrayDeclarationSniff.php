<?php
/**
 * A test to ensure that arrays conform to the array coding standard.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006 MindFrame2 Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   CVS: $Id: ArrayDeclarationSniff.php 291908 2009-12-09 03:56:09Z squiz $
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * A test to ensure that arrays conform to the array coding standard.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006 MindFrame2 Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: 1.2.2
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class MindFrame2_Sniffs_Arrays_ArrayDeclarationSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * The number of spaces code should be indented.
     *
     * @var int
     */
    protected $indent = 4;

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_ARRAY);

    }

    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The current file being checked.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Array keyword should be lower case.
        if (strtolower($tokens[$stackPtr]['content']) !== $tokens[$stackPtr]['content'])
        {
            $error = 'Array keyword should be lower case; expected "array" but found "'.$tokens[$stackPtr]['content'].'"';
            $phpcsFile->addError($error, $stackPtr, 'NotLowerCase');
        }

        $arrayStart   = $tokens[$stackPtr]['parenthesis_opener'];
        $arrayEnd     = $tokens[$arrayStart]['parenthesis_closer'];
        $keywordStart = $tokens[$stackPtr]['column'];

        if ($arrayStart != ($stackPtr + 1))
        {
            $error = 'There must be no space between the Array keyword and the opening parenthesis';
            $phpcsFile->addError($error, $stackPtr, 'SpaceAfterKeyword');
        }

        // Check for empty arrays.
        $content = $phpcsFile->findNext(array(T_WHITESPACE), ($arrayStart + 1), ($arrayEnd + 1), TRUE);

        if ($content === $arrayEnd)
        {
            // Empty array, but if the brackets aren't together, there's a problem.
            if (($arrayEnd - $arrayStart) !== 1)
            {
                $error = 'Empty array declaration must have no space between the parentheses';
                $phpcsFile->addError($error, $stackPtr, 'SpaceInEmptyArray');

                // We can return here because there is nothing else to check. All code
                // below can assume that the array is not empty.
                return;
            }
        }

        if ($tokens[$arrayStart]['line'] === $tokens[$arrayEnd]['line'])
        {
            // Single line array.
            // Check if there are multiple values. If so, then it has to be multiple lines
            // unless it is contained inside a function call or condition.
            $nextComma  = $arrayStart;
            $valueCount = 0;
            $commas     = array();
            while (($nextComma = $phpcsFile->findNext(array(T_COMMA), ($nextComma + 1), $arrayEnd)) !== FALSE)
            {
                $valueCount++;
                $commas[] = $nextComma;
            }

            // Now check each of the double arrows (if any).
            $nextArrow = $arrayStart;
            while (($nextArrow = $phpcsFile->findNext(T_DOUBLE_ARROW, ($nextArrow + 1), $arrayEnd)) !== FALSE)
            {
                if ($tokens[($nextArrow - 1)]['code'] !== T_WHITESPACE)
                {
                    $content = $tokens[($nextArrow - 1)]['content'];
                    $error   = "Expected 1 space between \"$content\" and double arrow; 0 found";
                    $phpcsFile->addError($error, $nextArrow, 'NoSpaceBeforeDoubleArrow');
                }
                else
                {
                    $spaceLength = strlen($tokens[($nextArrow - 1)]['content']);
                    if ($spaceLength !== 1)
                    {
                        $content = $tokens[($nextArrow - 2)]['content'];
                        $error   = "Expected 1 space between \"$content\" and double arrow; $spaceLength found";
                        $phpcsFile->addError($error, $nextArrow, 'SpaceBeforeDoubleArrow');
                    }
                }

                if ($tokens[($nextArrow + 1)]['code'] !== T_WHITESPACE)
                {
                    $content = $tokens[($nextArrow + 1)]['content'];
                    $error   = "Expected 1 space between double arrow and \"$content\"; 0 found";
                    $phpcsFile->addError($error, $nextArrow, 'NoSpaceAfterDoubleArrow');
                }
                else
                {
                    $spaceLength = strlen($tokens[($nextArrow + 1)]['content']);
                    if ($spaceLength !== 1)
                    {
                        $content = $tokens[($nextArrow + 2)]['content'];
                        $error   = "Expected 1 space between double arrow and \"$content\"; $spaceLength found";
                        $phpcsFile->addError($error, $nextArrow, 'SpaceAfterDoubleArrow');
                    }
                }
            }//end while

            if ($valueCount > 0)
            {
                $conditionCheck = $phpcsFile->findPrevious(array(T_OPEN_PARENTHESIS, T_SEMICOLON), ($stackPtr - 1), NULL, FALSE);

                if (($conditionCheck === FALSE) || ($tokens[$conditionCheck]['line'] !== $tokens[$stackPtr]['line']))
                {
                    $error = 'Array with multiple values cannot be declared on a single line';
                    $phpcsFile->addError($error, $stackPtr, 'SingleLineNotAllowed');
                    return;
                }

                // We have a multiple value array that is inside a condition or
                // function. Check its spacing is correct.
                foreach ($commas as $comma)
                {
                    if ($tokens[($comma + 1)]['code'] !== T_WHITESPACE)
                    {
                        $content = $tokens[($comma + 1)]['content'];
                        $error   = "Expected 1 space between comma and \"$content\"; 0 found";
                        $phpcsFile->addError($error, $comma, 'NoSpaceAfterComma');
                    }
                    else
                    {
                        $spaceLength = strlen($tokens[($comma + 1)]['content']);
                        if ($spaceLength !== 1)
                        {
                            $content = $tokens[($comma + 2)]['content'];
                            $error   = "Expected 1 space between comma and \"$content\"; $spaceLength found";
                            $phpcsFile->addError($error, $comma, 'SpaceAfterComma');
                        }
                    }

                    if ($tokens[($comma - 1)]['code'] === T_WHITESPACE)
                    {
                        $content     = $tokens[($comma - 2)]['content'];
                        $spaceLength = strlen($tokens[($comma - 1)]['content']);
                        $error       = "Expected 0 spaces between \"$content\" and comma; $spaceLength found";
                        $phpcsFile->addError($error, $comma, 'SpaceBeforeComma');
                    }
                }//end foreach
            }//end if

            return;
        }//end if

        // Check the closing bracket is on a new line.
        $lastContent = $phpcsFile->findPrevious(array(T_WHITESPACE), ($arrayEnd - 1), $arrayStart, TRUE);
        if ($tokens[$lastContent]['line'] !== ($tokens[$arrayEnd]['line'] - 1))
        {
            $error = 'Closing parenthesis of array declaration must be on a new line';
            $phpcsFile->addError($error, $arrayEnd, 'CloseBraceNewLine');
        }
        else if ($tokens[$arrayEnd]['column'] !== $keywordStart)
        {
            // Check the closing bracket is lined up under the a in array.
            $expected  = $keywordStart;
            $expected .= ($keywordStart === 0) ? ' space' : ' spaces';
            $found     = $tokens[$arrayEnd]['column'];
            $found    .= ($found === 0) ? ' space' : ' spaces';
            $error     = "Closing parenthesis not aligned correctly; expected $expected but found $found";
            $phpcsFile->addError($error, $arrayEnd, 'CloseBraceNotAligned');
        }

        $nextToken  = $stackPtr;
        $lastComma  = $stackPtr;
        $keyUsed    = FALSE;
        $singleUsed = FALSE;
        $lastToken  = '';
        $indices    = array();
        $maxLength  = 0;

        // Find all the double arrows that reside in this scope.
        while (($nextToken = $phpcsFile->findNext(array(T_DOUBLE_ARROW, T_COMMA, T_ARRAY), ($nextToken + 1), $arrayEnd)) !== FALSE)
        {
            $currentEntry = array();

            if ($tokens[$nextToken]['code'] === T_ARRAY)
            {
                // Let subsequent calls of this test handle nested arrays.
                $indices[] = array(
                    'value' => $nextToken,
                );
                $nextToken = $tokens[$tokens[$nextToken]['parenthesis_opener']]['parenthesis_closer'];
                continue;
            }

            if ($tokens[$nextToken]['code'] === T_COMMA)
            {
                $stackPtrCount = 0;
                if (isset($tokens[$stackPtr]['nested_parenthesis']) === TRUE)
                {
                    $stackPtrCount = count($tokens[$stackPtr]['nested_parenthesis']);
                }

                if (count($tokens[$nextToken]['nested_parenthesis']) > ($stackPtrCount + 1))
                {
                    // This comma is inside more parenthesis than the ARRAY keyword,
                    // then there it is actually a comma used to seperate arguments
                    // in a function call.
                    continue;
                }

                if ($keyUsed === TRUE && $lastToken === T_COMMA)
                {
                    $error = 'No key specified for array entry; first entry specifies key';
                    $phpcsFile->addError($error, $nextToken, 'NoKeySpecified');
                    return;
                }

                if ($keyUsed === FALSE)
                {
                    if ($tokens[($nextToken - 1)]['code'] === T_WHITESPACE)
                    {
                        $content     = $tokens[($nextToken - 2)]['content'];
                        $spaceLength = strlen($tokens[($nextToken - 1)]['content']);
                        $error       = "Expected 0 spaces between \"$content\" and comma; $spaceLength found";
                        $phpcsFile->addError($error, $nextToken, 'SpaceBeforeComma');
                    }

                    // Find the value, which will be the first token on the line,
                    // excluding the leading whitespace.
                    $valueContent = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($nextToken - 1), NULL, TRUE);

                    while ($tokens[$valueContent]['line'] === $tokens[$nextToken]['line'])
                    {
                        if ($valueContent === $arrayStart)
                        {
                            // Value must have been on the same line as the array
                            // parenthesis, so we have reached the start of the value.
                            break;
                        }

                        $valueContent--;
                    }

                    $valueContent = $phpcsFile->findNext(T_WHITESPACE, ($valueContent + 1), $nextToken, TRUE);
                    $indices[]    = array('value' => $valueContent);
                    $singleUsed   = TRUE;
                }//end if

                $lastToken = T_COMMA;
                continue;
            }//end if

            if ($tokens[$nextToken]['code'] === T_DOUBLE_ARROW)
            {
                if ($singleUsed === TRUE)
                {
                    $error = 'Key specified for array entry; first entry has no key';
                    $phpcsFile->addError($error, $nextToken, 'KeySpecified');
                    return;
                }

                $currentEntry['arrow'] = $nextToken;
                $keyUsed               = TRUE;

                // Find the start of index that uses this double arrow.
                $indexEnd   = $phpcsFile->findPrevious(T_WHITESPACE, ($nextToken - 1), $arrayStart, TRUE);
                $indexStart = $phpcsFile->findPrevious(T_WHITESPACE, $indexEnd, $arrayStart);

                if ($indexStart === FALSE)
                {
                    $index = $indexEnd;
                }
                else
                {
                    $index = ($indexStart + 1);
                }

                $currentEntry['index']         = $index;
                $currentEntry['index_content'] = $phpcsFile->getTokensAsString($index, ($indexEnd - $index + 1));

                $indexLength = strlen($currentEntry['index_content']);
                if ($maxLength < $indexLength)
                {
                    $maxLength = $indexLength;
                }

                // Find the value of this index.
                $nextContent           = $phpcsFile->findNext(array(T_WHITESPACE), ($nextToken + 1), $arrayEnd, TRUE);
                $currentEntry['value'] = $nextContent;
                $indices[]             = $currentEntry;
                $lastToken             = T_DOUBLE_ARROW;
            }//end if
        }//end while

        // Check for mutli-line arrays that should be single-line.
        $singleValue = FALSE;

        if (empty($indices) === TRUE)
        {
            $singleValue = TRUE;
        }
        else if (count($indices) === 1)
        {
            if ($lastToken === T_COMMA)
            {
                // There may be another array value without a comma.
                $exclude     = PHP_CodeSniffer_Tokens::$emptyTokens;
                $exclude[]   = T_COMMA;
                $nextContent = $phpcsFile->findNext($exclude, ($indices[0]['value'] + 1), $arrayEnd, TRUE);
                if ($nextContent === FALSE)
                {
                    $singleValue = TRUE;
                }
            }

            if ($singleValue === FALSE && isset($indices[0]['arrow']) === FALSE)
            {
                // A single nested array as a value is fine.
                if ($tokens[$indices[0]['value']]['code'] !== T_ARRAY)
                {
                    $singleValue === TRUE;
                }
            }
        }

        if ($singleValue === TRUE)
        {
            // Array cannot be empty, so this is a multi-line array with
            // a single value. It should be defined on single line.
            $error = 'Multi-line array contains a single value; use single-line array instead';
            $phpcsFile->addError($error, $stackPtr, 'MulitLineNotAllowed');
            return;
        }

        /*
            This section checks for arrays that don't specify keys.

            Arrays such as:
               array(
                'aaa',
                'bbb',
                'd',
               );
         */

        if ($keyUsed === FALSE && empty($indices) === FALSE)
        {
            $count     = count($indices);
            $lastIndex = $indices[($count - 1)]['value'];

            $trailingContent = $phpcsFile->findPrevious(T_WHITESPACE, ($arrayEnd - 1), $lastIndex, TRUE);
            if ($tokens[$trailingContent]['code'] !== T_COMMA)
            {
                $error = 'Comma required after last value in array declaration';
                $phpcsFile->addError($error, $trailingContent, 'NoCommaAfterLast');
            }

            foreach ($indices as $value)
            {
                if (empty($value['value']) === TRUE)
                {
                    // Array was malformed and we couldn't figure out
                    // the array value correctly, so we have to ignore it.
                    // Other parts of this sniff will correct the error.
                    continue;
                }

                if ($tokens[($value['value'] - 1)]['code'] === T_WHITESPACE)
                {
                    // A whitespace token before this value means that the value
                    // was indented and not flush with the opening parenthesis.
                    if ($tokens[$value['value']]['column'] !== ($keywordStart + 1))
                    {
                        $error = 'Array value not aligned correctly; expected '.($keywordStart + 1).' spaces but found '.$tokens[$value['value']]['column'];
                        $phpcsFile->addError($error, $value['value'], 'ValueNotAligned');
                    }
                }
            }
        }//end if

        /*
            Below the actual indentation of the array is checked.
            Errors will be thrown when a key is not aligned, when
            a double arrow is not aligned, and when a value is not
            aligned correctly.
            If an error is found in one of the above areas, then errors
            are not reported for the rest of the line to avoid reporting
            spaces and columns incorrectly. Often fixing the first
            problem will fix the other 2 anyway.

            For example:

            $a = array(
                'index'  => '2',
            );

            In this array, the double arrow is indented too far, but this
            will also cause an error in the value's alignment. If the arrow were
            to be moved back one space however, then both errors would be fixed.
         */

    $numValues = count($indices);
    // $indicesStart = ($keywordStart + 1);
    $indicesStart = ($tokens[$stackPtr - 2]['column'] + $this->indent);
    $arrowStart   = ($indicesStart + $maxLength + 1);
    $valueStart   = ($arrowStart + 3);

    foreach ($indices as $index)
    {
        if (isset($index['index']) === FALSE)
        {
            // Array value only.
            if (($tokens[$index['value']]['line'] === $tokens[$stackPtr]['line']) && ($numValues > 1))
            {
                $error = 'The first value in a multi-value array must be on a new line';
                $phpcsFile->addError($error, $stackPtr, 'FirstValueNoNewline');
            }

            continue;
        }

        if (($tokens[$index['index']]['line'] === $tokens[$stackPtr]['line']))
        {
            $error = 'The first index in a multi-value array must be on a new line';
            $phpcsFile->addError($error, $stackPtr, 'FirstIndexNoNewline');
            continue;
        }

        if ($tokens[$index['index']]['column'] !== $indicesStart)
        {
            $error = 'Array key not aligned correctly; expected '.$indicesStart.' spaces but found '.$tokens[$index['index']]['column'];
            $phpcsFile->addError($error, $index['index'], 'KeyNotAligned');
            continue;
        }

        if ($tokens[$index['arrow']]['column'] !== $arrowStart)
        {
            $expected  = ($arrowStart - (strlen($index['index_content']) + $tokens[$index['index']]['column']));
            $expected .= ($expected === 1) ? ' space' : ' spaces';
            $found     = ($tokens[$index['arrow']]['column'] - (strlen($index['index_content']) + $tokens[$index['index']]['column']));

            $error = "Array double arrow not aligned correctly; expected $expected but found $found";
            $phpcsFile->addError($error, $index['arrow'], 'DoubleArrowNotAligned');
            continue;
        }

        if ($tokens[$index['value']]['column'] !== $valueStart)
        {
            $expected  = ($valueStart - (strlen($tokens[$index['arrow']]['content']) + $tokens[$index['arrow']]['column']));
            $expected .= ($expected === 1) ? ' space' : ' spaces';
            $found     = ($tokens[$index['value']]['column'] - (strlen($tokens[$index['arrow']]['content']) + $tokens[$index['arrow']]['column']));

            $error = "Array value not aligned correctly; expected $expected but found $found";
            $phpcsFile->addError($error, $index['arrow'], 'ValueNotAligned');
        }

        // Check each line ends in a comma.
        if ($tokens[$index['value']]['code'] !== T_ARRAY)
        {
            $nextComma = $phpcsFile->findNext(array(T_COMMA), ($index['value'] + 1));
            if (($nextComma === FALSE) || ($tokens[$nextComma]['line'] !== $tokens[$index['value']]['line']))
            {
                $error = 'Each line in an array declaration must end in a comma';
                $phpcsFile->addError($error, $index['value'], 'NoComma');
            }

            // Check that there is no space before the comma.
            if ($nextComma !== FALSE && $tokens[($nextComma - 1)]['code'] === T_WHITESPACE)
            {
                $content     = $tokens[($nextComma - 2)]['content'];
                $spaceLength = strlen($tokens[($nextComma - 1)]['content']);
                $error       = "Expected 0 spaces between \"$content\" and comma; $spaceLength found";
                $phpcsFile->addError($error, $nextComma, 'SpaceBeforeComma');
            }
        }
    }//end foreach

}//end process()


}//end class

?>
