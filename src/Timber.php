<?php

namespace Spatie\Timber;

use Ramsey\Uuid\Uuid;
use Spatie\Timber\Messages\ClearScreenMessage;
use Spatie\Timber\Messages\ColorMessage;
use Spatie\Timber\Messages\Message;
use Spatie\Timber\Messages\SizeMessage;

class Timber
{
    protected Client $client;

    public string $chainUuid;

    public function __construct(Client $client, string $uuid = null)
    {
        $this->client = $client;

        $this->chainUuid = $uuid ?? Uuid::uuid4()->toString();
    }

    public function clearScreen(): self
    {
        $message = new ClearScreenMessage();

        $this->sendMessages([$message]);

        return $this;
    }

    public function color(string $color): self
    {
        $message = new ColorMessage($color);

        $this->sendMessages([$message]);

        return $this;
    }

    public function size(string $size): self
    {
        $message = new SizeMessage($size);

        $this->sendMessages([$message]);

        return $this;
    }

    public function send(...$arguments): self
    {
        $messages = array_map(function($argument) {
            return Message::createFromArgument($argument);
        }, $arguments);

        return $this->sendMessages($messages);
    }

    protected function sendMessages(array $messages): self
    {
        $payload = new Payload($this->chainUuid, $messages, );

        $this->client->send($payload);

        return $this;
    }
}
