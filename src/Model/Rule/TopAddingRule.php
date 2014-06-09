<?php
namespace Doukaku\SumOfUpAndLeft\Model\Rule;

use Doukaku\SumOfUpAndLeft\Model\Element;

/**
 * 上から可算
 */
class TopAddingRule extends Rule
{
    public function appliedTo(Element $element)
    {
        if ($element->top == 0) return false;

        if (null === $element->block) return true;

        if ($element->block->topSide($element)) return true;
        if ($element->block->rightSide($element)) return true;

        return false;
    }

    public function fromAddress(Element $element)
    {
        $top = $element->top - 1;
        $left = $element->left;

        return [$top, $left];
    }
}