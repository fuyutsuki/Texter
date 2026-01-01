<?php
declare(strict_types=1);
namespace jp\mcbe\fuyutsuki\Texter\libs\_f6a0d8769f281660\xxFLORII\bStats\charts\defaults\other;
use jp\mcbe\fuyutsuki\Texter\libs\_f6a0d8769f281660\xxFLORII\bStats\charts\CallbackChart;

/**
 * Example:
 * ```php
 * $chart = new PolarChart("example", function() {
 *     return [
 *         ["name" => "Point A", "value" => 50],
 *         ["name" => "Point B", "value" => 30],
 *         ["name" => "Point C", "value" => 20]
 *     ];
 * });
 * ```
 */
class PolarChart extends CallbackChart {
    public static function getType(): string{ return "polar"; }
    protected function getValue(): mixed{
        $value = $this->call();
        if (empty($value)) return null;
        return $value;
    }
}