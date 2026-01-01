<?php
declare(strict_types=1);
namespace jp\mcbe\fuyutsuki\Texter\libs\_f6a0d8769f281660\xxFLORII\bStats\charts\defaults\other;
use jp\mcbe\fuyutsuki\Texter\libs\_f6a0d8769f281660\xxFLORII\bStats\charts\CallbackChart;

/**
 * Example:
 * ```php
 * $chart = new SunburstChart("example", function() {
 *     return [
 *         ["name" => "Category 1", "children" => [
 *             ["name" => "Subcategory 1-1", "value" => 50],
 *             ["name" => "Subcategory 1-2", "value" => 30]
 *         ]],
 *         ["name" => "Category 2", "children" => [
 *             ["name" => "Subcategory 2-1", "value" => 70]
 *         ]]
 *     ];
 * });
 * ```
 */
class SunburstChart extends CallbackChart {
    public static function getType(): string{ return "sunburst"; }

    protected function getValue(): mixed{
        $value = $this->call();
        if (empty($value)) return null;
        return $value;
    }
}