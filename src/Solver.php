<?php

declare(strict_types=1);

/**
 * 異なるサイズの複数のアイテムをそれぞれ配送料金が異なる箱に入れる際, 
 * 配送料金の合計が最小になるような組み合わせを求める.
 */
class Solver
{
  /** items Example: [1, 2, 3] (各アイテムのサイズ) */
  private $items = [];

  /**
   * package 各箱のサイズとその配送料金.
   * Example: 
   * [
   *   [
   *     "size" => 5, 
   *     "price" => 10
   *   ],
   *   [
   *     "size" => 10,
   *     "price" => 20
   *   ],
   *   [
   *     "size" => 15,
   *     "price" => 30
   *   ]
   * ] 
   * (各箱のサイズとその配送料金) */
  private $packages = [];

  /**
   * @param array $items 各アイテムのサイズの配列
   * @param array $packages 各箱のサイズとその配送料金の配列
   * @return void
   */
  public function __construct(array $items, array $packages)
  {
    $this->items = $items;
    $this->packages = $packages;
  }

  /**
   * 動的計画法により、アイテムを全て梱包した時に配送料金の合計が最小になる組み合わせを返す.
   * （組み合わせの箱の許容サイズの総和がアイテムのサイズの総和を超えないように）
   */
  public function solve(): array
  {
    $dp = [];
    $n = count($this->items);
    $m = count($this->packages);
    for ($i = 0; $i <= $n; $i++) {
      for ($j = 0; $j <= $m; $j++) {
        $dp[$i][$j] = 0;
      }
    }

    for ($i = 0; $i < $n; $i++) {
      for ($j = 0; $j < $m; $j++) {
        if ($this->items[$i] <= $this->packages[$j]["size"]) {
          $dp[$i + 1][$j + 1] = max($dp[$i][$j + 1], $dp[$i][$j], $dp[$i + 1][$j], $dp[$i][$j] + $this->packages[$j]["price"]);
        } else {
          $dp[$i + 1][$j + 1] = $dp[$i][$j + 1];
        }
      }
    }

    $res = [];
    $i = $n;
    $j = $m;
    while ($i > 0 && $j > 0) {
      if ($dp[$i][$j] == $dp[$i - 1][$j]) {
        $i--;
      } else if ($dp[$i][$j] == $dp[$i][$j - 1]) {
        $j--;
      } else {
        $res[] = $this->packages[$j - 1];
        $i--;
        $j--;
      }
    }

    return array_reverse($res);
  }
}
