<?php
declare(strict_types=1);
namespace jp\mcbe\fuyutsuki\Texter\libs\_e7f596115d1cc90b\xxFLORII\bStats\charts;

abstract class CustomChart implements \JsonSerializable{
    private string $custom_id;

    public abstract static function getType(): string;

    public function __construct(string $custom_id) {
        if ($custom_id == null) {
            throw new \InvalidArgumentException("Chart: $custom_id cannot be null");
        }

        $this->custom_id = $custom_id;
    }
    public function getCustomId(): string{ return $this->custom_id; }

    public function jsonSerialize(): array{
        $json = [
            "chartId" => $this->custom_id,
        ];
        try {
            $data = $this->getValue();
            if ($data === null) throw new \ErrorException("\$data cannot be null");
            $json["data"] = $data;
        } catch (\Throwable $ignored) {
        }
        return $json;
    }

    protected abstract function getValue(): mixed;
}