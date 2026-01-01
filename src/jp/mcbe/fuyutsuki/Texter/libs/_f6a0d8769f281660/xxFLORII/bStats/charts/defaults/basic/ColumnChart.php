<?php
declare(strict_types=1);
namespace jp\mcbe\fuyutsuki\Texter\libs\_f6a0d8769f281660\xxFLORII\bStats\charts\defaults\basic;
use jp\mcbe\fuyutsuki\Texter\libs\_f6a0d8769f281660\xxFLORII\bStats\charts\CallbackChart;

/**
 * Beispiel:
 * ```php
 * $chart = new ColumnChart("example", function() {
 *     return [5, 10, 15, 20];
 * });
 * ```
 */
class ColumnChart extends CallbackChart {
    public static function getType(): string{ return "column"; }
    protected function getValue(): mixed{
        $value = $this->call();
        if (empty($value)) return null;
        return $value;
    }
}