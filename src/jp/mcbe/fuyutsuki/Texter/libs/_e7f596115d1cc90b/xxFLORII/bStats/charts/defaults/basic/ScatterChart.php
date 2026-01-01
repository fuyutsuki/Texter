<?php
declare(strict_types=1);
namespace jp\mcbe\fuyutsuki\Texter\libs\_e7f596115d1cc90b\xxFLORII\bStats\charts\defaults\basic;
use jp\mcbe\fuyutsuki\Texter\libs\_e7f596115d1cc90b\xxFLORII\bStats\charts\CallbackChart;

/**
 * Example:
 * ```php
 * $chart = new ScatterChart("example", function() {
 *     return [
 *         "x" => [1, 2, 3, 4],
 *         "y" => [10, 20, 30, 40]
 *     ];
 * });
 * ```
 */
class ScatterChart extends CallbackChart {
    public static function getType(): string{ return "scatter"; }
    protected function getValue(): mixed{
        $value = $this->call();
        if (empty($value)) return null;
        return $value;
    }
}