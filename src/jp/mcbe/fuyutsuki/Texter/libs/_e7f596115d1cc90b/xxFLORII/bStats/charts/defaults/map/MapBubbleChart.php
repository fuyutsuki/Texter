<?php
declare(strict_types=1);
namespace jp\mcbe\fuyutsuki\Texter\libs\_e7f596115d1cc90b\xxFLORII\bStats\charts\defaults\map;
use jp\mcbe\fuyutsuki\Texter\libs\_e7f596115d1cc90b\xxFLORII\bStats\charts\CallbackChart;

/**
 * Example:
 * ```php
 * $chart = new MapBubbleChart("example", function() {
 *     return [
 *         ["hc-key" => "US", "value" => 50, "z" => 10],
 *         ["hc-key" => "DE", "value" => 30, "z" => 8],
 *         ["hc-key" => "FR", "value" => 20, "z" => 6]
 *     ];
 * });
 * ```
 */
class MapBubbleChart extends CallbackChart {
    public static function getType(): string{ return "mapbubble"; }
    protected function getValue(): mixed{
        $value = $this->call();
        if (empty($value)) return null;
        return $value;
    }
}