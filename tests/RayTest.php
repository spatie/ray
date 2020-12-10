<?php

namespace Spatie\Ray\Tests;

use PHPUnit\Framework\TestCase;
use Spatie\Ray\Ray;
use Spatie\Ray\Tests\TestClasses\FakeClient;
use Spatie\Snapshots\MatchesSnapshots;

class RayTest extends TestCase
{
    use MatchesSnapshots;

    private FakeClient $client;

    private Ray $ray;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = new FakeClient();

        $this->ray = new Ray($this->client, 'fakeUuid');
    }

    /** @test */
    public function it_can_send_a_string_to_ray()
    {
        $this->ray->send('a');

        $this->assertMatchesSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_an_array_to_ray()
    {
        $this->ray->send(['a' => 1, 'b' => 2]);

        $this->assertMatchesSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_multiple_things_in_one_go_to_ray()
    {
        $this->ray->send('first', 'second', 'third');

        $this->assertMatchesSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_a_color_and_a_size()
    {
        $this->ray->send('test', 'test2')->color('green')->size('big');

        $this->assertMatchesSnapshot($this->client->sentPayloads());
    }

    public function it_has_a_helper_function()
    {
        $this->assertInstanceOf(Ray::class, ray());
    }

    /** @test */
    public function it_can_send_a_hide_payload_to_ray()
    {
        $this->ray->hide();

        $this->assertMatchesSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_a_remove_payload_to_ray()
    {
        $this->ray->remove();

        $this->assertMatchesSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_measure_time()
    {
        $this->ray->time();
        $this->assertCount(1, $this->client->sentPayloads());

        sleep(1);

        $this->ray->time();
        $this->assertCount(2, $this->client->sentPayloads());

        $this->ray->stopTime();

        $this->ray->time();
        dump($this->client->sentPayloads());
    }
}
