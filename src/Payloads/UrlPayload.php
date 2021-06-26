<?php

namespace Spatie\Ray\Payloads;

class UrlPayload extends Payload
{
    /** @var string */
    protected $url;

    /** @var string | null */
    protected $label;

    public function __construct(string $url, ?string $label = null)
    {
        $this->url = $url;
        $this->label = $label;
    }

    public function getType(): string
    {
        return 'custom';
    }

    public function getContent(): array
    {
        $label = $this->label ? "<div class=\"pr-3 inline\">{$this->label}</div>" : '';
        $content = "{$label}<a class=\"text-blue-600 underline\" href=\"{$this->url}\">{$this->url}</a>";

        return [
            'content' => $content,
            'label' => 'URL',
        ];
    }
}
