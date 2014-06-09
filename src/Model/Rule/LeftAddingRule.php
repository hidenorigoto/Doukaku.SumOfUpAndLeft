<?php
namespace Doukaku\SumOfUpAndLeft\Model\Rule;

use Doukaku\SumOfUpAndLeft\Model\Element;

/**
 * 左から可算
 */
class LeftAddingRule extends Rule
{
    public function appliedTo(Element $element)
    {
        if ($element->left == 0) return false;

        return true;
    }

    public function fromAddress(Element $element)
    {
        $top = $element->top;
        $left = $element->left - 1;

        return [$top, $left];
    }
}