<?php
namespace Doukaku\SumOfUpAndLeft\Model;

class Element
{
    public $left;
    public $top;
    private $value = null;
    /**
     * @var Element[]
     */
    public $addResultFrom = [];
    /**
     * @var Element[]
     */
    public $addEvaluationFrom = [];
    /**
     * @var Element[]
     */
    public $referTo = [];
    /**
     * @var Block
     */
    public $block = null;

    public function __construct($left, $top)
    {
        $this->left = $left;
        $this->top = $top;
    }

    public function getEvaluation()
    {
        if (null === $this->value) $this->calc();

        return $this->value;
    }

    public function calc()
    {
        if (null !== $this->value) return;

        $this->value = array_reduce($this->addEvaluationFrom, function ($current, $v) {
            return $current + $v->getEvaluation();
        }, null);
        $this->value += array_reduce($this->addResultFrom, function ($current, $v) {
            return $current + $v->getResult();
        }, null);
        $this->value %= 100;
    }

    public function getResult()
    {
        if (!empty($this->referTo)) return $this->referTo[0]->getResult();

        return $this->getEvaluation();
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function bindBlock(Block $block)
    {
        $this->block = $block;
        if (!$this->equalsTo($block->bottomRightCell)) {
            $this->referTo[] = $block->bottomRightCell;
        }
    }

    public function equalsTo(Element $element)
    {
        return $this->left == $element->left && $this->top == $element->top;
    }
}