<?php
namespace Doukaku\SumOfUpAndLeft\Model;

class SheetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider 初期マステストデータ
     */
    public function 初期マス生成($cols, $rows, $blocks)
    {
        $sheet = new Sheet(sprintf('%dx%d:%s', $cols, $rows, implode(',', $blocks)));

        $this->assertThat($sheet instanceof Sheet, $this->equalTo(true));
        $this->assertThat(count($sheet->cells), $this->equalTo($rows));
        $this->assertThat(count($sheet->cells[0]), $this->equalTo($cols));
    }

    public function 初期マステストデータ()
    {
        return [
            '3x3' => [3, 3, []],
            '4x2' => [4, 2, [
                '1022'
            ]],
        ];
    }

    /**
     * @test
     * @dataProvider ブロック内外判定テストデータ
     */
    public function ブロック内外判定($code, $row, $col, $expected)
    {
        $sheet = new Sheet($code);

        $this->assertThat(
            $sheet->blocks[0]->includes($sheet->cells[$row][$col]),
            $this->equalTo($expected));

        if ($expected) {
            $this->assertThat($sheet->cells[$row][$col]->block instanceof Block,
                $this->equalTo(true));
        } else {
            $this->assertThat($sheet->cells[$row][$col]->block,
                $this->equalTo(null));
        }
    }

    public function ブロック内外判定テストデータ()
    {
        return [
            ['3x3:1022',0,0,false],
            ['3x3:1022',0,1,true],
            ['3x3:1022',0,2,true],
            ['3x3:1022',1,0,false],
            ['3x3:1022',1,1,true],
            ['3x3:1022',1,2,true],
            ['3x3:1022',2,0,false],
            ['3x3:1022',2,1,false],
            ['3x3:1022',2,2,false],
            ['4x3:1022',0,3,false],
        ];
    }
}
 