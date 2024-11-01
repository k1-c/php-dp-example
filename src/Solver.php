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
    $totalWeight = array_sum($this->items);

    // dp配列とcombinations配列の初期化
    $dp = array_fill(0, $totalWeight + 1, PHP_INT_MAX);
    $dp[0] = 0;  // 0kgの荷物を詰めるコストは0円

    // 各重さごとに最適な箱の組み合わせを格納する配列
    $combinations = array_fill(0, $totalWeight + 1, []);
    $combinations[0] = []; // 0kgの場合は空の組み合わせ

    // 動的計画法で最小配送料金を計算
    for ($weight = 1; $weight <= $totalWeight; $weight++) {
      foreach ($this->packages as $package) {
        $boxSize = $package["size"];
        $cost = $package["price"];

        // 現在の重量に対してこの箱を使えるかどうかを確認
        if ($weight >= $boxSize && $dp[$weight - $boxSize] != PHP_INT_MAX) {
          $newCost = $dp[$weight - $boxSize] + $cost;

          // 新しいコストが現在のdpより小さい場合、更新
          if ($newCost < $dp[$weight]) {
            $dp[$weight] = $newCost;
            $combinations[$weight] = array_merge($combinations[$weight - $boxSize], [$package]);
          }
        }
      }
    }

    return $combinations[$totalWeight] ?? [];
  }
}
