<?php

use Carbon\Carbon;
use Illuminate\Support\Arr;

use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertGreaterThan;
use function PHPUnit\Framework\assertGreaterThanOrEqual;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertLessThan;
use function PHPUnit\Framework\assertNotEquals;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertStringContainsString;
use function PHPUnit\Framework\assertTrue;

use Spatie\Backtrace\Frame;

use function Spatie\PestPluginTestTime\testTime;

use Spatie\Ray\Origin\Hostname;
use Spatie\Ray\PayloadFactory;
use Spatie\Ray\Payloads\CallerPayload;
use Spatie\Ray\Payloads\LogPayload;
use Spatie\Ray\Ray;
use Spatie\Ray\Settings\SettingsFactory;
use Spatie\Ray\Tests\TestClasses\FakeClient;

function getNewRay(): Ray
{
    return Ray::create(test()->client, 'fakeUuid');
}

function getValueOfLastSentContent(string $contentKey)
{
    $payload = test()->client->sentPayloads();

    if (! count($payload)) {
        return null;
    }

    $lastPayload = end($payload);

    return Arr::get($lastPayload, "payloads.0.content.{$contentKey}");
}

beforeEach(function () {
    Hostname::set('fake-hostname');

    $this->client = new FakeClient();

    $this->settings = SettingsFactory::createFromConfigFile();

    $this->ray = new Ray($this->settings, $this->client, 'fakeUuid');

    $this->ray->enable();

    Ray::rateLimiter()->clear();
});

it('can send a string to ray', function () {
    $this->ray->send('a');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send a strings that might be interpreted as callables to ray', function () {
    $this->ray->send('value');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('the ray function also works', function () {
    Ray::$fakeUuid = 'fakeUuid';

    ray('a');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send an array to ray', function () {
    $this->ray->send(['a' => 1, 'b' => 2]);

    $dumpedValue = getValueOfLastSentContent('values')[0];

    assertStringContainsString('<span class=sf-dump-key>a</span>', $dumpedValue);
    assertStringContainsString('<span class=sf-dump-key>b</span>', $dumpedValue);
});

it('can send multiple things in one go to ray', function () {
    $this->ray->send('first', 'second', 'third');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send a color and a size', function () {
    $this->ray->send('test', 'test2')->color('green')->size('big');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send a screen color', function () {
    $this->ray->screenGreen();

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send a label', function () {
    $this->ray->send('my value')->label('my label');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('has a helper function', function () {
    assertInstanceOf(Ray::class, ray());
});

it('can send a hide payload to ray', function () {
    $this->ray->hide();

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send a remove payload to ray', function () {
    $this->ray->remove();

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can conditionally show something using a boolean', function () {
    $this->ray->send('hey')->showIf(true);
    assertCount(1, $this->client->sentPayloads());

    $this->client->reset();
    $this->ray->send('hey')->showIf(false);
    assertCount(2, $this->client->sentPayloads());
});

it('can conditionally show something using a callable', function () {
    $this->ray->send('hey')->showIf(function () {
        return true;
    });
    assertCount(1, $this->client->sentPayloads());

    $this->client->reset();
    $this->ray->send('hey')->showIf(function () {
        return false;
    });
    assertCount(2, $this->client->sentPayloads());
});

it('can conditionally remove something using a boolean', function () {
    $this->ray->send('hey')->removeWhen(true);
    assertCount(2, $this->client->sentPayloads());

    $this->client->reset();
    $this->ray->send('hey')->removeWhen(false);
    assertCount(1, $this->client->sentPayloads());

    $this->client->reset();
    $this->ray->send('hey')->removeIf(true);
    assertCount(2, $this->client->sentPayloads());
});

it('can conditionally remove something using a callable', function () {
    $this->ray->send('hey')->removeWhen(function () {
        return true;
    });
    assertCount(2, $this->client->sentPayloads());

    $this->client->reset();
    $this->ray->send('hey')->removeWhen(function () {
        return false;
    });
    assertCount(1, $this->client->sentPayloads());

    $this->client->reset();
    $this->ray->send('hey')->removeIf(function () {
        return true;
    });
    assertCount(2, $this->client->sentPayloads());
});

it('can measure time and memory', function () {
    $this->ray->measure();
    assertCount(1, $this->client->sentPayloads());
    assertTrue(getValueOfLastSentContent('is_new_timer'));
    assertEquals(0, getValueOfLastSentContent('total_time'));
    assertEquals(0, getValueOfLastSentContent('max_memory_usage_during_total_time'));
    assertEquals(0, getValueOfLastSentContent('time_since_last_call'));
    assertEquals(0, getValueOfLastSentContent('max_memory_usage_since_last_call'));

    usleep(1000);

    $this->ray->measure();
    assertCount(2, $this->client->sentPayloads());
    assertFalse(getValueOfLastSentContent('is_new_timer'));
    assertNotEquals(0, getValueOfLastSentContent('total_time'));
    assertNotEquals(0, getValueOfLastSentContent('max_memory_usage_during_total_time'));
    assertNotEquals(0, getValueOfLastSentContent('time_since_last_call'));
    assertNotEquals(0, getValueOfLastSentContent('max_memory_usage_since_last_call'));

    usleep(1000);
    $this->ray->measure();
    assertCount(3, $this->client->sentPayloads());
    assertGreaterThan(
        getValueOfLastSentContent('time_since_last_call'),
        getValueOfLastSentContent('total_time'),
    );

    $this->ray->stopTime();

    $this->ray->measure();
    assertTrue(getValueOfLastSentContent('is_new_timer'));
    assertEquals(0, getValueOfLastSentContent('total_time'));
    assertEquals(0, getValueOfLastSentContent('max_memory_usage_during_total_time'));
    assertEquals(0, getValueOfLastSentContent('time_since_last_call'));
    assertEquals(0, getValueOfLastSentContent('max_memory_usage_since_last_call'));
});

it('can measure using multiple timers', function () {
    $this->ray->measure('my-timer');
    assertEquals('my-timer', getValueOfLastSentContent('name'));
});

it('can measure a closure', function () {
    $closure = function () {
        sleep(1);
    };

    $this->ray->measure($closure);

    assertCount(1, $this->client->sentPayloads());
    assertNotEquals(0, getValueOfLastSentContent('total_time'));
    assertNotEquals(0, getValueOfLastSentContent('max_memory_usage_during_total_time'));
    assertNotEquals(0, getValueOfLastSentContent('time_since_last_call'));
    assertNotEquals(0, getValueOfLastSentContent('max_memory_usage_since_last_call'));
});

it('removes a named stopwatch when stopping time', function () {
    $this->ray->measure('test-timer');
    /** @phpstan-ignore-next-line */
    assertTrue(isset($this->ray::$stopWatches['test-timer']));

    $this->ray->stopTime('test-timer');
    /** @phpstan-ignore-next-line */
    assertFalse(isset($this->ray::$stopWatches['test-timer']));
});

it('can send backtrace to ray', function () {
    $this->ray->trace();
    $frames = getValueOfLastSentContent('frames');

    assertGreaterThanOrEqual(10, count($frames));

    $firstFrame = $frames[0];

    assertEquals('P\Tests\RayTest', $firstFrame['class']);
    assertEquals('{closure}', $firstFrame['method']);
});

it('can send backtrace frames starting from a specific frame', function () {
    $this->ray->trace(function (Frame $frame) {
        return $frame->class === 'PHPUnit\TextUI\TestRunner';
    });

    $frames = getValueOfLastSentContent('frames');

    $firstFrame = $frames[0];

    assertEquals('PHPUnit\TextUI\TestRunner', $firstFrame['class']);
    assertEquals('run', $firstFrame['method']);
});

it('has a backtrace alias for trace', function () {
    $this->ray->backtrace();
    $frames = getValueOfLastSentContent('frames');

    assertGreaterThanOrEqual(10, count($frames));

    $firstFrame = $frames[0];

    assertEquals('P\Tests\RayTest', $firstFrame['class']);
    assertEquals('{closure}', $firstFrame['method']);
});

it('can send the caller to ray', function () {
    $this->ray->caller();

    $frame = getValueOfLastSentContent('frame');

    assertEquals('call_user_func', $frame['method']);
    assertEquals(null, $frame['class']);
});

it('can send the ban payload', function () {
    $this->ray->ban();

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send the charles payload', function () {
    $this->ray->charles();

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send the notify payload', function () {
    $this->ray->notify('notification text');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send the exception payload', function () {
    $this->ray->exception(new Exception('This is an exception'));

    $payloads = $this->client->sentPayloads();

    assertCount(2, $payloads);
    assertEquals('exception', $payloads[0]['payloads'][0]['type']);
    assertEquals(Exception::class, $payloads[0]['payloads'][0]['content']['class']);
    assertEquals('This is an exception', $payloads[0]['payloads'][0]['content']['message']);
});

it('can send the json payload', function () {
    $this->ray->json('{"message": "message text 2"}');

    $dumpedValue = $this->client->sentPayloads()[0]['payloads'][0]['content']['content'];

    assertStringContainsString('<span class=sf-dump-key>message</span>', $dumpedValue);
    assertStringContainsString('<span class=sf-dump-str title="14 characters">message text 2</span>', $dumpedValue);
});

it('can send multiple json payloads', function () {
    $this->ray->json(
        '{"message": "message text 1"}',
        '{"message": "message text 2"}'
    );

    $dumpedValue1 = $this->client->sentPayloads()[0]['payloads'][0]['content']['content'];
    $dumpedValue2 = $this->client->sentPayloads()[0]['payloads'][1]['content']['content'];

    assertStringContainsString('<span class=sf-dump-key>message</span>', $dumpedValue1);
    assertStringContainsString('<span class=sf-dump-key>message</span>', $dumpedValue2);
    assertStringContainsString('<span class=sf-dump-str title="14 characters">message text 1</span>', $dumpedValue1);
    assertStringContainsString('<span class=sf-dump-str title="14 characters">message text 2</span>', $dumpedValue2);
});

it('can send the toJson payload', function () {
    $this->ray->toJson(['message' => 'message text 1']);

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send multiple toJson payloads', function () {
    $this->ray->toJson(
        ['message' => 'message text 1'],
        ['message' => 'message text 2']
    );

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send the file content payload', function () {
    $this->ray->file(__DIR__ .'/testSettings/ray.php');
    $this->ray->file('missing.php');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send the new screen payload', function () {
    $this->ray->newScreen('my-screen');
    $this->ray->newScreen();

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send the clear screen payload', function () {
    $this->ray->clearScreen();

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send the clear all payload', function () {
    $this->ray->clearAll();

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send the class name', function () {
    $this->ray->className($this);

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send a falsy value', function () {
    $this->ray->send(false);

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('is macroable', function () {
    Ray::macro('myCustomFunction', function (string $value) {
        $payload = new LogPayload($value . '-suffix');

        $this->sendRequest([$payload]);

        return $this;
    });

    $this->ray->myCustomFunction('my value');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('when ray is not running the pause call will not blow up', function () {
    $this->ray->pause();

    assertEquals('create_lock', $this->client->sentPayloads()[0]['payloads'][0]['type']);
});

it('can send custom stuff to ray', function () {
    $this->ray->sendCustom('my custom content');
    assertMatchesOsSafeSnapshot($this->client->sentPayloads());

    $this->ray->sendCustom('my custom content', 'custom label');
    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send data to ray and return the data', function () {
    $data = ['a' => 1, 'b' => 2];

    $result = $this->ray->pass($data);

    assertEquals($data, $result);

    $dumpedValue = getValueOfLastSentContent('values')[0];

    assertStringContainsString('<span class=sf-dump-key>a</span>', $dumpedValue);
    assertStringContainsString('<span class=sf-dump-key>b</span>', $dumpedValue);
});

it('can rewrite the file paths using the config values', function () {
    $payload = new CallerPayload([
        new Frame('/app/app/MyFile.php', 1, []),
        new Frame('/app/app/MyFile.php', 2, []),
    ]);

    $payload->remotePath = '/app';
    $payload->localPath = '/some/local/path';

    assertEquals('/some/local/path/app/MyFile.php', $payload->getContent()['frame']['file_name']);
});

it('only rewrites paths for matching remote paths', function () {
    $payload = new CallerPayload([
        new Frame('/app/files/MyFile.php', 1, []),
        new Frame('/app/files/MyFile.php', 2, []),
    ]);

    $payload->remotePath = '/files';
    $payload->localPath = '/some/local/path';

    assertEquals('/app/files/MyFile.php', $payload->getContent()['frame']['file_name']);

    $payload->remotePath = '/app';
    $payload->localPath = '/some/local/path';

    assertEquals('/some/local/path/files/MyFile.php', $payload->getContent()['frame']['file_name']);
});

it('returns itself and does not send anything when calling send without arguments', function () {
    $settings = SettingsFactory::createFromConfigFile();

    $this->ray = new Ray($settings, $this->client, 'fakeUuid');

    $result = $this->ray->send();

    assertCount(0, $this->client->sentPayloads());
    assertEquals($this->ray, $result);
    assertNull(getValueOfLastSentContent('values'));
});

it('can determine how many times a particular piece of code was called for a given name', function () {
    foreach (range(1, 2) as $i) {
        ray()->count('first');

        foreach (range(1, 2) as $j) {
            ray()->count('second');
            ray()->count('another');
        }

        ray()->count('another');
    }

    assertEquals(2, Ray::$counters->get('first'));
    assertEquals(4, Ray::$counters->get('second'));
    assertEquals(6, Ray::$counters->get('another'));
});

it('can determine how many times a particular piece of code was called without a name', function () {
    foreach (range(1, 2) as $i) {
        ray()->count();

        foreach (range(1, 2) as $j) {
            ray()->count();
        }
    }

    assertEquals("Called 4 times.", $this->client->sentPayloads()[5]['payloads'][0]['content']['content']);
});

it('creates a Ray instance with default settings when create is called without arguments', function () {
    $ray = Ray::create(null, '1-2-3-4');

    assertNotNull($ray);
    assertEquals('1-2-3-4', $ray->uuid);
    assertEquals($ray->settings, SettingsFactory::createFromConfigFile());
});

it('merges default settings into existing settings', function () {
    $settings = SettingsFactory::createFromConfigFile();

    assertNull($settings->test);
    assertEquals(23517, $settings->port);

    $settings->setDefaultSettings(['test' => 'testvalue']);

    assertEquals('testvalue', $settings->test);
    assertEquals(23517, $settings->port);
});

it('can send the php info payload', function () {
    $this->ray->phpinfo();

    $payloads = $this->client->sentPayloads();

    assertCount(1, $payloads);

    assertEquals('table', $payloads[0]['payloads'][0]['type']);
});

it('the php info can report specific options', function () {
    $this->ray->phpinfo('default_mimetype');

    $payloads = $this->client->sentPayloads();

    assertCount(1, $payloads);

    assertArrayHasKey('default_mimetype', $payloads[0]['payloads'][0]['content']['values']);
});

it('sends an image payload', function () {
    $this->ray->image('http://localhost/test.jpg');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('sends a base64 encoded image payload', function () {
    $this->ray->image('dGVzdCBzdHJpbmc=');
    $this->ray->image('data:image/png;base64,dGVzdCBzdHJpbmc=');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('sends a table payload', function () {
    $this->ray->table([
        'First' => 'First value',
        'Second' => 'Second value',
        'Third' => 'Third value',
    ]);

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send a carbon payload', function () {
    $frozenTime = testTime()->freeze('2020-01-01 00:00:00');

    $carbon = new Carbon();

    ray()->carbon($carbon);

    assertCount(1, $this->client->sentPayloads());

    $payload = $this->client->sentPayloads()[0];
    assertEquals($frozenTime, $payload['payloads'][0]['content']['formatted']);
    assertEquals($frozenTime->getTimestamp(), $payload['payloads'][0]['content']['timestamp']);
    assertEquals(date_default_timezone_get(), $payload['payloads'][0]['content']['timezone']);
});

it('sends an xml payload', function () {
    $this->ray->xml('<one><two>2</two></one>');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send the raw values', function () {
    $this->ray->raw(new Carbon(), 'string', ['a' => 1]);

    $payloads = $this->client->sentPayloads();

    assertEquals('log', $payloads[0]['payloads'][0]['type']);
    assertEquals('log', $payloads[0]['payloads'][1]['type']);
    assertEquals('log', $payloads[0]['payloads'][2]['type']);
});

it('returns a ray instance when calling raw without arguments', function () {
    $instance = $this->ray->raw();

    assertInstanceOf(Ray::class, $instance);
    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('will send a specialized payloads by default', function () {
    $this->ray->send(new Carbon(), 'string', ['a => 1']);

    $payloads = $this->client->sentPayloads();

    assertEquals('carbon', $payloads[0]['payloads'][0]['type']);
    assertEquals('log', $payloads[0]['payloads'][1]['type']);
    assertEquals('log', $payloads[0]['payloads'][2]['type']);
});

it('sends the hide application payload', function () {
    $this->ray->hideApp();

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('sends the show application payload', function () {
    $this->ray->showApp();

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('sends an html payload', function () {
    $this->ray->html('<strong>test</strong>');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('sends an url payload', function () {
    $this->ray->url('https://spatie.be');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('sends a correct url payload even without a protocol', function () {
    $this->ray->url('spatie.be');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('sends an url with label payload', function () {
    $this->ray->url('https://spatie.be', 'Spatie');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('sends a link payload', function () {
    $this->ray->link('https://spatie.be', 'Spatie');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('sends a text payload', function () {
    $this->ray->text('text string');
    $this->ray->text('another   <strong>text</strong>' . PHP_EOL . '  string');

    $lastPayload = $this->client->sentPayloads()[1]['payloads'][0];

    assertStringContainsString('&nbsp;&nbsp;&nbsp;&lt;strong&gt;', $lastPayload['content']['content']);
    assertStringContainsString('<br>', $lastPayload['content']['content']);
    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('sends a null payload', function () {
    $this->ray->send(null);

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('returns zero when accessing a missing counter', function () {
    assertEquals(0, Ray::$counters->get('missing'));
    ray()->count('missing');
    assertEquals(1, Ray::$counters->get('missing'));
});

it('sets the ray instance for a counter', function () {
    $ray1 = ray();
    $ray2 = ray();

    $ray1->count('first');

    $ray1::$counters->setRay('first', $ray1);

    assertEquals($ray1, $ray1::$counters->increment('first')[0]);

    $ray1::$counters->setRay('first', $ray2);

    assertEquals($ray2, $ray1::$counters->increment('first')[0]);
});

it('clears all counters', function () {
    Ray::$counters->clear();

    assertEquals(0, Ray::$counters->get('first'));

    ray()->count('first');

    assertEquals(1, Ray::$counters->get('first'));

    ray()->clearCounters();

    assertEquals(0, Ray::$counters->get('first'));
});

it('returns the value of a named counter', function () {
    assertEquals(0, ray()->counterValue('first'));

    ray()->count('first');

    assertEquals(1, ray()->counterValue('first'));

    ray()->count('first');

    assertEquals(2, ray()->counterValue('first'));
});

it('will respect the raw values config setting', function () {
    $this->settings->always_send_raw_values = true;
    $this->ray->send(new Carbon());
    assertEquals('log',  $this->client->sentPayloads()[0]['payloads'][0]['type']);
});

it('can be disabled', function () {
    $this->ray->send('test payload 1');
    $this->ray->disable();
    $this->ray->send('test payload 2');

    assertCount(1, $this->client->sentPayloads());
});

it('can be reenabled after being disabled', function () {
    $this->ray->enable();
    $this->ray->send('test payload 1');
    $this->ray->disable();
    $this->ray->send('test payload 2');
    $this->ray->enable();
    $this->ray->send('test payload 3');

    assertCount(2, $this->client->sentPayloads());
});

it('returns the correct enabled state', function () {
    Ray::$enabled = true;
    assertTrue($this->ray->enabled());
    assertFalse($this->ray->disabled());

    Ray::$enabled = false;
    assertFalse($this->ray->enabled());
    assertTrue($this->ray->disabled());
});

it('defaults to enabled state', function () {
    assertTrue($this->ray->enabled());
});

it('checks the availablity of the Ray server', function () {
    $this->client->changePortAndReturnOriginal(34993);

    assertFalse($this->client->performAvailabilityCheck());
});

it('respects the enabled property', function () {
    $ray = getNewRay()->disable();

    assertFalse($ray->enabled());
    assertFalse(getNewRay()->enabled());

    getNewRay()->enable();

    assertTrue($ray->enabled());
    assertTrue(getNewRay()->enabled());
});

it('respects the enabled property when sending payloads', function () {
    $ray = getNewRay()->disable();
    $ray->send('test message 1');
    assertCount(0, $this->client->sentPayloads());

    $ray->enable();
    $ray->send('test message 2');
    assertCount(1, $this->client->sentPayloads());

    $ray->disable();
    $ray->send('test message 3');
    assertCount(1, $this->client->sentPayloads());
});

it('can quickly send a request', function () {
    $before = microtime(true);

    $payloads = PayloadFactory::createForValues([
        'value 1' => 'nested',
        'value 2',
    ]);

    $this->ray->sendRequest($payloads);

    $after = microtime(true);

    assertLessThan(0.005, $after - $before);
});

it('can quickly call the ray helper', function () {
    $before = microtime(true);

    ray('a');

    $after = microtime(true);

    assertLessThan(0.05, $after - $before);
});

it('can quickly call send function', function () {
    $before = microtime(true);

    $this->ray->send('a');

    $after = microtime(true);

    assertLessThan(0.005, $after - $before);
});

it('can limit the number of payloads sent from a loop', function () {
    $limit = 5;

    for ($i = 0; $i < 10; $i++) {
        getNewRay()->limit($limit)->send("limited loop iteration $i");
    }

    assertCount($limit, $this->client->sentPayloads());
});

it('only limits the number of payloads sent from the line that calls limit', function () {
    $limit = 5;
    $iterations = 10;

    for ($i = 0; $i < $iterations; $i++) {
        getNewRay()->limit($limit)->send("limited loop iteration $i");
        getNewRay()->send("unlimited loop iteration $i");
    }

    assertCount($limit + $iterations, $this->client->sentPayloads());
});

it('can handle multiple consecutive calls to limit', function () {
    $limit = 2;

    for ($i = 0; $i < 10; $i++) {
        getNewRay()->limit($limit)
        ->send("limited loop A iteration $i");

        getNewRay()->limit($limit)
        ->send("limited loop B iteration $i");
    }

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can conditionally send payloads using if with a truthy conditional and without a callback', function () {
    for ($i = 0; $i < 10; $i++) {
        $this->ray->if($i < 5)->text("value: {$i}");
    }

    assertCount(5, $this->client->sentPayloads());
});

it('can conditionally send payloads using if with a callable conditional param', function () {
    for ($i = 0; $i < 10; $i++) {
        $this->ray->if(function () use ($i) {
            return $i < 5;
        })->text("value: {$i}");
    }

    assertCount(5, $this->client->sentPayloads());
});

it('can conditionally send payloads using if with a callback', function () {
    $this->ray->if(true, function ($ray) {
        $ray->text('one');
    });

    $this->ray->if(false, function ($ray) {
        $ray->text('two');
    });

    assertCount(1, $this->client->sentPayloads());
});

it('can chain method calls when using if with a callback and a false condition', function () {
    $this->ray->if(false, function ($ray) {
        $ray->text('one')->green();
    })
    ->text('two')
    ->blue();

    $this->ray
        ->text('three')
        ->if(false, function ($ray) {
            $ray->green();
        });

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can chain multiple when calls with callbacks together', function () {
    $this->ray
        ->text('test')
        ->if(true, function ($ray) {
            $ray->green();
        })
        ->if(false, function ($ray) {
            $ray->text('text modified');
        })
        ->if(true, function ($ray) {
            $ray->large();
        })
        ->hide();

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('cannot call when rate limit max has reached', function () {
    Ray::rateLimiter()
        ->clear()
        ->max(1);

    ray('this can pass');
    ray('this cannot pass, but triggers a warning call');
    ray('this cannot pass');

    assertCount(2, $this->client->sentPayloads());

    assertSame('Rate limit has been reached...', $this->client->sentPayloads()[1]['payloads'][0]['content']['content']);
});

it('sends a payload once when called with arguments', function () {
    for ($i = 0; $i < 5; $i++) {
        getNewRay()->once($i);
    }

    assertCount(1, $this->client->sentPayloads());
    assertEquals([0], $this->client->sentPayloads()[0]['payloads'][0]['content']['values']);
});

it('sends a payload once when called without arguments', function () {
    for ($i = 0; $i < 5; $i++) {
        getNewRay()->once()->text($i);
    }

    assertCount(1, $this->client->sentPayloads());
    assertEquals(0, $this->client->sentPayloads()[0]['payloads'][0]['content']['content']);
});

it('sends a payload once while allowing calls to limit', function () {
    for ($i = 0; $i < 5; $i++) {
        $this->ray->once($i);
        getNewRay()->limit(5)->text($i);
    }

    assertCount(6, $this->client->sentPayloads());
});

it('does nothing if no exceptions are thrown from a callable while using catch with a callback', function () {
    $ray = getNewRay();

    $ray->send(function () use ($ray) {
        return $ray->text('hello world');
    })->catch(function ($exception, $ray) {
        $ray->text($exception->getMessage());
    });

    assertCount(1, $this->client->sentPayloads());
    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('handles exceptions using catch with a callback and classname parameter', function () {
    getNewRay()->send(function () {
        throw new Exception('test');
    })->catch(Exception::class);

    // 2 payloads for exceptions
    assertCount(2, $this->client->sentPayloads());
});

it('handles exceptions using and catch without a callback', function () {
    getNewRay()->send(function () {
        throw new Exception('test');
    })->catch();

    // 2 payloads are sent when ray->exception() is called
    assertCount(2, $this->client->sentPayloads());
});

it('handles exceptions using catch with a callback', function () {
    getNewRay()->send(function () {
        throw new Exception('test');
    })->catch(function ($e, $ray) {
        return $ray->text($e->getMessage());
    });

    assertCount(1, $this->client->sentPayloads());
    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('handles exceptions using catch with a callback and a typed parameter', function () {
    getNewRay()->send(function () {
        throw new Exception('test');
    })->catch(function (Exception $e, $ray) {
        return $ray->text($e->getMessage());
    });

    assertCount(1, $this->client->sentPayloads());
    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('handles exceptions using catch with a callback and a union type parameter on php8 and higher', function () {
    $newRay = getNewRay();

    $newRay->send(function () {
        throw new \Exception('test');
    })->catch(function (\InvalidArgumentException | \Exception $e, $ray) {
        return $ray->text($e->getMessage());
    });

    assertCount(1, $this->client->sentPayloads());
    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
})->skip(PHP_MAJOR_VERSION < 8, 'test requires PHP 8+');

it('handles exceptions using catch with an array of callbacks with typed parameters', function () {
    getNewRay()->send(function () {
        throw new InvalidArgumentException('test');
    })->catch([
        function (BadMethodCallException $e, $ray) {
            return $ray->text(get_class($e));
        },
        function (InvalidArgumentException $e, $ray) {
            $ray->text(get_class($e));
        },
    ]);

    assertCount(1, $this->client->sentPayloads());
    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('handles exceptions using catch with an array of exception classnames', function () {
    getNewRay()->send(function () {
        throw new InvalidArgumentException('test');
    })->catch([
        BadMethodCallException::class,
        InvalidArgumentException::class,
    ]);

    assertCount(2, $this->client->sentPayloads());
});

it('does not handle exceptions using catch with an array of exception classnames that do not match the exception', function () {
    $this->expectException(\InvalidArgumentException::class);

    getNewRay()->send(function () {
        throw new InvalidArgumentException('test');
    })->catch([
        BadMethodCallException::class,
        BadFunctionCallException::class,
    ]);

    assertCount(0, $this->client->sentPayloads());
});

it('does not handle exceptions using catch with a callback and a typed parameter different than the exception class', function () {
    $this->expectException(\Exception::class);

    getNewRay()->send(function () {
        throw new Exception('test');
    })->catch(function (InvalidArgumentException $e, $ray) {
        return $ray->text($e->getMessage());
    });

    assertCount(0, $this->client->sentPayloads());
});

it('allows chaining additional methods after handling an exception', function () {
    getNewRay()->send(function ($ray) {
        $ray->text('hello world');

        throw new Exception('test');
    })->catch()->blue()->small();

    assertCount(5, $this->client->sentPayloads());
});

it('throws exceptions when calling throwExceptions', function () {
    $this->expectException(Exception::class);

    getNewRay()->send(function ($ray) {
        $ray->text('hello world');

        throw new Exception('test');
    })->throwExceptions();
});

it('can dump a string with a global function name', function () {
    $this->ray->send('array_map');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
})->skip(PHP_MAJOR_VERSION < 8, 'test requires PHP 8+');

it('can send a separator', function () {
    $this->ray->send('separator');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can set the project name', function () {
    ray()->project('my project');

    assertEquals('my project', Ray::$projectName);

    ray('send request');

    assertEquals('my project', $this->client->sentRequests()[0]['meta']['project_name']);
});

it('can dump long integers as string', function () {
    $this->ray->send(11111111111111110);
    $this->ray->send(11111111111111111);

    assertSame([11111111111111110], $this->client->sentRequests()[0]['payloads'][0]['content']['values']);
    assertSame(["11111111111111111"], $this->client->sentRequests()[1]['payloads'][0]['content']['values']);
});
