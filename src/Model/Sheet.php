<?php
namespace Doukaku\SumOfUpAndLeft\Model;

use Doukaku\SumOfUpAndLeft\Model\Rule\LeftAddingRule;
use Doukaku\SumOfUpAndLeft\Model\Rule\Rule;
use Doukaku\SumOfUpAndLeft\Model\Rule\TopAddingRule;

class Sheet
{
    /**
     * @var Element[][]
     */
    public $cells;
    private $cols;
    private $rows;

    /**
     * @var Block[]
     */
    public $blocks = [];
    /**
     * @var Rule[]
     */
    public $rules = [];

    public function __construct($code, $rules = [])
    {
        if (empty($rules)) {
            $rules = [
                new LeftAddingRule(),
                new TopAddingRule(),
            ];
        }
        $this->rules = $rules;

        preg_match('/(?<width>.)x(?<height>.):(?<blocks>.*)$/', $code, $matches);

        $this->cols = $matches['width'];
        $this->rows = $matches['height'];
        $this->initializeCells($this->cols, $this->rows);
        $this->initializeBlocks(explode(',', $matches['blocks']));
        $this->bindCellToBlock();

        $this->applyRules();
    }

    /**
     * @param $width
     * @param $height
     */
    private function initializeCells($width, $height)
    {
        $this->cells = [];

        for ($i = 0; $i < $height; $i++) {
            for ($j = 0; $j < $width; $j++) {
                $this->cells[$i][$j] = new Element($j, $i);
            }
        }
    }

    /**
     * @param $blockCodes
     */
    private function initializeBlocks($blockCodes)
    {
        $this->blocks = [];

        foreach ($blockCodes as $blockCode)
        {
            if ($blockCode == '') continue;

            $block = new Block($blockCode);
            $address = $block->bottomRightAddress();
            $block->bottomRightCell = $this->cells[$address[0]][$address[1]];
            $this->blocks[] = $block;
        }
    }

    private function bindCellToBlock()
    {
        array_walk_recursive($this->cells, function($element) {
            if (!($element instanceof Element)) return;

            if ($block = $this->findBlock($element)) {
                $element->bindBlock($block);
            }
        });
    }

    private function applyRules()
    {
        array_walk_recursive($this->cells, function($element) {
            if (!($element instanceof Element)) return;

            foreach ($this->rules as $rule) {
                if ($rule->appliedTo($element)) {
                    $fromAddress = $rule->fromAddress($element);
                    if (is_array($fromAddress)) {
                        $rule->apply($element, $this->cells[$fromAddress[0]][$fromAddress[1]]);
                    } else {
                        $rule->apply($element);
                    }
                }
            }
        });
    }

    private function findBlock(Element $element)
    {
        foreach ($this->blocks as $block)
        {
            if ($block->includes($element)) return $block;
        }

        return null;
    }

    public function evaluate()
    {
        array_walk_recursive($this->cells, function($element) {
            if (!($element instanceof Element)) return;

            $element->calc();
        });
    }

    public function getResult()
    {
        return $this->cells[$this->rows - 1][$this->cols - 1]->getResult();
    }
}
