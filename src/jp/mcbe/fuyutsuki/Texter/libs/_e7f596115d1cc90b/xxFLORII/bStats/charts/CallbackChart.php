<?php
declare(strict_types=1);
namespace jp\mcbe\fuyutsuki\Texter\libs\_e7f596115d1cc90b\xxFLORII\bStats\charts;

abstract class CallbackChart extends CustomChart{
    protected $callback;
    public function __construct(string $custom_id, \Closure $callback) {
        parent::__construct($custom_id);
        $this->callback = $callback;
    }

    protected function call(): mixed{
        try {
            return ($this->callback)();
        } catch (\Throwable $t) {
            \GlobalLogger::get()->error("Error while executing callback in ".(new \ReflectionClass($this))->getShortName()." class:");
            \GlobalLogger::get()->logException($t);
            return null;
        }
    }
}