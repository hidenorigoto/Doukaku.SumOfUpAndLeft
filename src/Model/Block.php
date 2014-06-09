<?php
namespace Doukaku\SumOfUpAndLeft\Model;

class Block
{
    public $code;
    public $left;
    public $top;
    public $width;
    public $height;
    public $bottomRightCell;
    public $fromBlocks = [];

    public function __construct($code)
    {
        $this->code = $code;
        $this->left = $code[0];
        $this->top = $code[1];
        $this->width = $code[2];
        $this->height = $code[3];
    }

    public function includes(Element $element)
    {
        return (($element->left >= $this->left && $element->left <= ($this->left + $this->width - 1)) &&
           ($element->top >= $this->top && $element->top <= ($this->top + $this->height - 1)));
    }

    public function topSide(Element $element)
    {
        return $element->top == $this->top;
    }

    public function leftSide(Element $element)
    {
        return $element->left == $this->left;
    }

    public function rightSide(Element $element)
    {
        return $element->left == ($this->left + $this->width - 1);
    }

    public function bottomSide(Element $element)
    {
        return $element->top == ($this->top + $this->height - 1);
    }

    public function equalTo(Block $target)
    {
        return $this->code == $target->code;
    }

    public function bottomRightAddress()
    {
        return [$this->top + $this->height - 1, $this->left + $this->width - 1];
    }
}
