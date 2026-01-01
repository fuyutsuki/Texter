<?php
declare(strict_types=1);
namespace jp\mcbe\fuyutsuki\Texter\libs\_e7f596115d1cc90b\xxFLORII\bStats\charts\defaults\advanced;
use jp\mcbe\fuyutsuki\Texter\libs\_e7f596115d1cc90b\xxFLORII\bStats\charts\CallbackChart;

/**
 * Example:
 * ```php
 * $chart = new PyramidChart("example", function() {
 *     return [
 *         ["name" => "Level 1", "y" => 100],
 *         ["name" => "Level 2", "y" => 80],
 *         ["name" => "Level 3", "y" => 60],
 *         ["name" => "Level 4", "y" => 40],
 *     ];
 * });
 * ```
 */
class PyramidChart extends CallbackChart {
    public static function getType(): string{ return "pyramid"; }
    protected function getValue(): mixed{
        $value = $this->call();
        if (empty($value)) return null;
        return $value;
    }
}