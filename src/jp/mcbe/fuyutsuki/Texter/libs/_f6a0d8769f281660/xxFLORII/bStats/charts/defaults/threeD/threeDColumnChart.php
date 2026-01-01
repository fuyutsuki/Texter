<?php
declare(strict_types=1);
namespace jp\mcbe\fuyutsuki\Texter\libs\_f6a0d8769f281660\xxFLORII\bStats\charts\defaults\threeD;
use jp\mcbe\fuyutsuki\Texter\libs\_f6a0d8769f281660\xxFLORII\bStats\charts\CallbackChart;

/**
 * Example:
 * ```php
 * $chart = new threeDColumnChart("example", function() {
 *     return [
 *         ["x" => "A", "y" => 10],
 *         ["x" => "B", "y" => 20],
 *         ["x" => "C", "y" => 30]
 *     ];
 * });
 * ```
 */
class threeDColumnChart extends CallbackChart {
    public static function getType(): string{ return "3dcolumn"; }
    protected function getValue(): mixed{
        $value = $this->call();
        if (empty($value)) return null;
        return $value;
    }
}