<?php

use Spatie\Ray\Client;

it('can load the client without deprecations', function () {
    $command = sprintf(
        '%s -n -d error_reporting=-1 -d display_errors=1 -r %s 2>&1',
        escapeshellarg(PHP_BINARY),
        escapeshellarg('require ' . var_export(dirname(__DIR__) . '/src/Client.php', true) . ';')
    );

    exec($command, $output, $exitCode);

    expect($exitCode)->toBe(0);
    expect(implode("\n", $output))->toBe('');
});

it('returns false when an availability check throws', function (Throwable $failure) {
    $client = new class ($failure) extends Client {
        protected $failure;

        public function __construct(Throwable $failure)
        {
            parent::__construct();

            $this->failure = $failure;
        }

        protected function getCurlHandleForUrl(string $method, string $url)
        {
            throw $this->failure;
        }
    };

    expect($client->performAvailabilityCheck())->toBeFalse();
})->with([
    [new Exception('connection failed')],
    [new Error('invalid handle')],
    [new TypeError('invalid handle type')],
]);
