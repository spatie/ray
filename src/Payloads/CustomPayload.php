<?php


namespace Spatie\Ray\Payloads;

class CustomPayload extends Payload
{
    protected string $content;

    protected string $label;

    public function __construct(string $content, string $label = '')
    {
        $this->content = $content;

        $this->label = $label;
    }

    public function getType(): string
    {
        return 'custom';
    }

    public function getContent(): array
    {
        return [
            'content' => $this->content,
            'label' => $this->label,
        ];
    }
}
