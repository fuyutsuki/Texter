<?php
declare(strict_types=1);
namespace jp\mcbe\fuyutsuki\Texter\libs\_f6a0d8769f281660\xxFLORII\bStats\charts\defaults\basic;
use jp\mcbe\fuyutsuki\Texter\libs\_f6a0d8769f281660\xxFLORII\bStats\charts\CallbackChart;

/**
 * Example:
 * ```php
 * $chart = new PieChart("example", function() {
 *     return [
 *         ["name" => "A", "y" => 40],
 *         ["name" => "B", "y" => 30],
 *         ["name" => "C", "y" => 30]
 *     ];
 * });
 * ```
 */
class PieChart extends CallbackChart {
    public static function getType(): string{ return "pie"; }
    protected function getValue(): mixed{
        $value = $this->call();
        if (empty($value)) return null;
        return $value;
    }
}