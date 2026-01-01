<?php
declare(strict_types=1);
namespace jp\mcbe\fuyutsuki\Texter\libs\_f6a0d8769f281660\xxFLORII\bStats\charts\defaults\stock;
use jp\mcbe\fuyutsuki\Texter\libs\_f6a0d8769f281660\xxFLORII\bStats\charts\CallbackChart;

/**
 * Example:
 * ```php
 * $chart = new FlagsChart("example", function() {
 *     return [
 *         ["x" => "2023-01-01", "title" => "Flag 1", "text" => "Event A"],
 *         ["x" => "2023-02-01", "title" => "Flag 2", "text" => "Event B"]
 *     ];
 * });
 * ```
 */
class FlagsChart extends CallbackChart {
    public static function getType(): string{ return "flags"; }
    protected function getValue(): mixed{
        $value = $this->call();
        if (empty($value)) return null;
        return $value;
    }
}