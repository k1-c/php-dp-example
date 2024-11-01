<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once(dirname(__FILE__) . '/../src/Solver.php');

class SolverTest extends TestCase
{
  /**
   * 動的計画法のテスト
   */
  public function testSolveMethod()
  {
    $items = [2, 3, 6];
    $packages = [
      [
        "size" => 5,
        "price" => 10
      ],
      [
        "size" => 10,
        "price" => 15
      ],
      [
        "size" => 15,
        "price" => 30
      ]
    ];
    $solver = new Solver($items, $packages);
    $actual = $solver->solve();
    // 結果出力
    var_dump($actual);


    // 配送料金の合計が最小になる組み合わせはサイズが10の箱1つとサイズが5の箱1つ.
    $expected = [
      [
        "size" => 10,
        "price" => 15
      ],
      [
        "size" => 5,
        "price" => 10
      ]
    ];
    $this->assertEquals($expected, $actual);
  }
}
