<?php

namespace Spatie\Ray\Payloads;

use Spatie\Ray\Origin\Origin;
use Spatie\Ray\Ray;

class RateLimitingActivePayload extends Payload
{
    /** @var Ray|null  */
    protected $rayInstance;

    public function __construct(?Ray $rayInstance = null)
    {
        $this->rayInstance = $rayInstance;
    }

    public function getType(): string
    {
        return 'custom';
    }

    public function getContent(): array
    {
        return [
            'content' => $this->getMessage(),
            'label' => 'RateLimit',
        ];
    }

    protected function getMessage(): string
    {
        return '<div class="text-red-600">' . $this->getMessageText() . '</div>';
    }

    protected function getMessageText(): string
    {
        if (! $this->rayInstance) {
            return 'Rate limiting is active!';
        }

        /** @var Origin $origin */
        $origin = $this->rayInstance->limitOrigin;

        if (! $origin) {
            return 'Limit reached!';
        }

        $result = 'Limit reached at ' . $this->replaceRemotePathWithLocalPath($origin->file);
        $result .= " line {$origin->lineNumber}";

        return $result;
    }
}
