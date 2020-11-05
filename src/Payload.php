<?php

namespace Spatie\Timber;

use Spatie\Timber\Payloads\Message;

class Payload
{
    protected string $chainUuid;

    protected array $messages;

    public function __construct(string $uuid, array $messages)
    {
        $this->chainUuid = $uuid;

        $this->messages = $messages;
    }

    public function toJson(): string
    {
        $messages = array_map(function (Message $message) {
            return $message->toArray();
        }, $this->messages);

        return json_encode([
            'chainUuid' => $this->chainUuid,
            'messages' => $messages,
        ]);
    }
}
