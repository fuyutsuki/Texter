<?php
declare(strict_types=1);
namespace jp\mcbe\fuyutsuki\Texter\libs\_e7f596115d1cc90b\xxFLORII\bStats\charts\defaults\threeD;
use jp\mcbe\fuyutsuki\Texter\libs\_e7f596115d1cc90b\xxFLORII\bStats\charts\CallbackChart;

/**
 * Example:
 * ```php
 * $chart = new threeDScatterPlotChart("example", function() {
 *     return [
 *         ["x" => 1, "y" => 2, "z" => 10],
 *         ["x" => 3, "y" => 4, "z" => 20],
 *         ["x" => 5, "y" => 6, "z" => 30]
 *     ];
 * });
 * ```
 */
class threeDScatterPlotChart extends CallbackChart {
    public static function getType(): string{ return "3dscatter"; }
    protected function getValue(): mixed{
        $value = $this->call();
        if (empty($value)) return null;
        return $value;
    }
}