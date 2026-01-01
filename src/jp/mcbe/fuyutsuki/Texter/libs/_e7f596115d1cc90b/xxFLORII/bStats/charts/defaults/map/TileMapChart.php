<?php
declare(strict_types=1);
namespace jp\mcbe\fuyutsuki\Texter\libs\_e7f596115d1cc90b\xxFLORII\bStats\charts\defaults\map;
use jp\mcbe\fuyutsuki\Texter\libs\_e7f596115d1cc90b\xxFLORII\bStats\charts\CallbackChart;

/**
 * Example:
 * ```php
 * $chart = new TileMapChart("example", function() {
 *     return [
 *         ["hc-key" => "US", "value" => 5],
 *         ["hc-key" => "DE", "value" => 3],
 *         ["hc-key" => "FR", "value" => 2]
 *     ];
 * });
 * ```
 */
class TileMapChart extends CallbackChart {
    public static function getType(): string{ return "tilemap"; }
    protected function getValue(): mixed{
        $value = $this->call();
        if (empty($value)) return null;
        return $value;
    }
}