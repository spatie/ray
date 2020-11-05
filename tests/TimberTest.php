<?php

namespace Spatie\Timber\Tests;

use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;
use Spatie\Timber\Tests\TestClasses\FakeClient;
use Spatie\Timber\Timber;
use StdClass;

class TimberTest extends TestCase
{
    use MatchesSnapshots;

    private FakeClient $client;

    private Timber $timber;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = new FakeClient();

        $this->timber = new Timber($this->client, 'fakeUuid');
    }

    /** @test */
    public function it_can_send_a_string_to_timber()
    {
        $this->timber->send('a');

        $this->assertMatchesSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_an_array_to_timber()
    {
        $this->timber->send(['a' => 1, 'b' => 2]);

        $this->assertMatchesSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_multiple_things_in_one_go_to_timber()
    {
        $this->timber->send('first', 'second', 'third');

        $this->assertMatchesSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_a_color_and_a_size()
    {
        $this->timber->send('test', 'test2')->color('green')->size('big');

        $this->assertMatchesSnapshot($this->client->sentPayloads());
    }

    public function it_has_a_helper_function()
    {
        $this->assertInstanceOf(Timber::class, timber());
    }
}
