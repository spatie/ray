<?php

namespace Spatie\Ray\Payloads;

class NullPayload extends Payload
{
    protected bool $bool;

    public function getType(): string
    {
        return 'custom';
    }

    public function getContent(): array
    {
        return [
            'content' => null,
            'label' => 'Null',
        ];
    }
}
