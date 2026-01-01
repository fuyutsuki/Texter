<?php
declare(strict_types=1);
namespace jp\mcbe\fuyutsuki\Texter\libs\_e7f596115d1cc90b\xxFLORII\bStats\charts\defaults\other;
use jp\mcbe\fuyutsuki\Texter\libs\_e7f596115d1cc90b\xxFLORII\bStats\charts\CallbackChart;

/**
 * Example:
 * ```php
 * $chart = new SolidGaugeChart("example", function() {
 *     return 75;
 * });
 * ```
 */
class SolidGaugeChart extends CallbackChart {
    public static function getType(): string{ return "solidgauge"; }
    protected function getValue(): mixed{
        $value = $this->call();
        if ($value === null || $value === "") return null;
        return $value;
    }
}