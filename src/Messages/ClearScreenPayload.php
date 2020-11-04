<?php

namespace Spatie\Timber\Messages;

class ClearScreenPayload extends Payload
{
    public function getType(): string
    {
        return 'clear_screen';
    }

    public function getContent(): array
    {
        return [];
    }

}
