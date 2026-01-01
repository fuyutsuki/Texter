<?php
declare(strict_types=1);
namespace jp\mcbe\fuyutsuki\Texter\libs\_e7f596115d1cc90b\xxFLORII\bStats\charts\defaults\other;
use jp\mcbe\fuyutsuki\Texter\libs\_e7f596115d1cc90b\xxFLORII\bStats\charts\CallbackChart;

/**
 * Example:
 * ```php
 * $chart = new SankeyDiagramChart("example", function() {
 *     return [
 *         ["from" => "A", "to" => "B", "value" => 10],
 *         ["from" => "B", "to" => "C", "value" => 20],
 *         ["from" => "C", "to" => "D", "value" => 30]
 *     ];
 * });
 * ```
 */
class SankeyDiagramChart extends CallbackChart {
    public static function getType(): string{ return "sankey"; }
    protected function getValue(): mixed{
        $value = $this->call();
        if (empty($value)) return null;
        return $value;
    }
}