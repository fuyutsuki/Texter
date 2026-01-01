<?php
declare(strict_types=1);
namespace jp\mcbe\fuyutsuki\Texter\libs\_e7f596115d1cc90b\xxFLORII\bStats\charts\defaults\advanced;
use jp\mcbe\fuyutsuki\Texter\libs\_e7f596115d1cc90b\xxFLORII\bStats\charts\CallbackChart;

/**
 * Example:
 * ```php
 * $chart = new FunnelChart("example", function() {
 *     return [
 *         ["name" => "Stage 1", "y" => 100],
 *         ["name" => "Stage 2", "y" => 80],
 *         ["name" => "Stage 3", "y" => 60],
 *         ["name" => "Stage 4", "y" => 30],
 *     ];
 * });
 * ```
 */
class FunnelChart extends CallbackChart {
    public static function getType(): string{ return "funnel"; }
    protected function getValue(): mixed{
        $value = $this->call();
        if (empty($value)) return null;
        return $value;
    }
}