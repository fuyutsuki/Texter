<?php
declare(strict_types=1);
namespace jp\mcbe\fuyutsuki\Texter\libs\_e7f596115d1cc90b\xxFLORII\bStats\charts\defaults\advanced;
use jp\mcbe\fuyutsuki\Texter\libs\_e7f596115d1cc90b\xxFLORII\bStats\charts\CallbackChart;

/**
 * Example:
 * ```php
 * $chart = new TreeMapChart("example", function() {
 *     return [
 *         ["name" => "A", "value" => 10],
 *         ["name" => "B", "value" => 20],
 *         ["name" => "C", "value" => 30],
 *         ["name" => "D", "value" => 40]
 *     ];
 * });
 * ```
 */
class TreeMapChart extends CallbackChart {
    public static function getType(): string{ return "treemap"; }
    protected function getValue(): mixed{
        $value = $this->call();
        if (empty($value)) return null;
        return $value;
    }
}