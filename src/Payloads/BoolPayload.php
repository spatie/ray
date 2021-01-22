<?php

namespace Spatie\Ray\Payloads;

class BoolPayload extends Payload
{
    protected bool $bool;

    public function __construct(bool $bool)
    {
        $this->bool = $bool;
    }

    public function getType(): string
    {
        return 'custom';
    }

    public function getContent(): array
    {
        return [
            'content' => $this->bool,
            'label' => 'Boolean',
        ];
    }
}
