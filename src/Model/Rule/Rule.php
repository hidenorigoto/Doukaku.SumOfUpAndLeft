<?php
namespace Doukaku\SumOfUpAndLeft\Model\Rule;

use Doukaku\SumOfUpAndLeft\Model\Element;

abstract class Rule
{
    abstract public function appliedTo(Element $element);
    abstract public function fromAddress(Element $element);

    public function apply(Element $element, Element $from = null)
    {
        if ($this->inSameBlock($element, $from)) {
            $element->addEvaluationFrom[] = $from;
        } else {
            if (null != $element->block && null != $from->block) {
                if (!array_key_exists($from->block->code, $element->block->fromBlocks)) {
                    $element->block->fromBlocks[$from->block->code] = true;
                    $element->addResultFrom[] = $from;
                }
            } else {
                $element->addResultFrom[] = $from;
            }
        }
    }

    protected function inSameBlock(Element $v1, Element $v2)
    {
        return (null != $v1->block && null != $v2->block &&
            $v1->block->equalTo($v2->block));
    }
}
