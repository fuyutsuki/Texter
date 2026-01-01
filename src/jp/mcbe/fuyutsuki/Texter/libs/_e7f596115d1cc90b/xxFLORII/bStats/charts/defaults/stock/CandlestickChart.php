<?php
declare(strict_types=1);
namespace jp\mcbe\fuyutsuki\Texter\libs\_e7f596115d1cc90b\xxFLORII\bStats\charts\defaults\stock;
use jp\mcbe\fuyutsuki\Texter\libs\_e7f596115d1cc90b\xxFLORII\bStats\charts\CallbackChart;

/**
 * Example:
 * ```php
 * $chart = new CandlestickChart("example", function() {
 *     return [
 *         ["x" => "2023-01-01", "open" => 100, "high" => 120, "low" => 90, "close" => 110],
 *         ["x" => "2023-01-02", "open" => 110, "high" => 125, "low" => 105, "close" => 118]
 *     ];
 * });
 * ```
 */
class CandlestickChart extends CallbackChart {
    public static function getType(): string{ return "candlestick"; }
    protected function getValue(): mixed{
        $value = $this->call();
        if (empty($value)) return null;
        return $value;
    }
}