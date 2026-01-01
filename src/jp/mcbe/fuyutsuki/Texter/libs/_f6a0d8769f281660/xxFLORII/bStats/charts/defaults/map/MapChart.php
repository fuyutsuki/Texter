<?php
declare(strict_types=1);
namespace jp\mcbe\fuyutsuki\Texter\libs\_f6a0d8769f281660\xxFLORII\bStats\charts\defaults\map;
use jp\mcbe\fuyutsuki\Texter\libs\_f6a0d8769f281660\xxFLORII\bStats\charts\CallbackChart;

/**
 * Example:
 * ```php
 * $chart = new MapChart("example", function() {
 *     return [
 *         ["hc-key" => "US", "value" => 100],
 *         ["hc-key" => "DE", "value" => 80],
 *         ["hc-key" => "FR", "value" => 70]
 *     ];
 * });
 * ```
 */
class MapChart extends CallbackChart {
    public static function getType(): string{ return "map"; }
    protected function getValue(): mixed{
        $value = $this->call();
        if (empty($value)) return null;
        return $value;
    }
}