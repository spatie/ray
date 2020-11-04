<?php

namespace Spatie\Timber\Messages;

class ClearScreenMessage extends Message
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
