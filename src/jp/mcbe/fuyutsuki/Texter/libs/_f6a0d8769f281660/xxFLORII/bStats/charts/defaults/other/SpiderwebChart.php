<?php
declare(strict_types=1);
namespace jp\mcbe\fuyutsuki\Texter\libs\_f6a0d8769f281660\xxFLORII\bStats\charts\defaults\other;
use jp\mcbe\fuyutsuki\Texter\libs\_f6a0d8769f281660\xxFLORII\bStats\charts\CallbackChart;

/**
 * Example:
 * ```php
 * $chart = new SpiderwebChart("example", function() {
 *     return [
 *         ["name" => "Metric A", "value" => 80],
 *         ["name" => "Metric B", "value" => 60],
 *         ["name" => "Metric C", "value" => 40],
 *         ["name" => "Metric D", "value" => 70]
 *     ];
 * });
 * ```
 */
class SpiderwebChart extends CallbackChart {
    public static function getType(): string{ return "spiderweb"; }
    protected function getValue(): mixed{
        $value = $this->call();
        if (empty($value)) return null;
        return $value;
    }
}