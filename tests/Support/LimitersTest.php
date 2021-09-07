<?php

namespace Spatie\Ray\Tests\Support;

use PHPUnit\Framework\TestCase;
use Spatie\Ray\Origin\Origin;
use Spatie\Ray\Support\Limiters;

class LimitersTest extends TestCase
{
    /** @var Limiters */
    public $limiters;

    protected function setUp(): void
    {
        parent::setUp();

        $this->limiters = new Limiters();
    }

    /** @test */
    public function it_initializes_a_limiter_for_an_origin()
    {
        $origins = [new Origin('test.php', 123), new Origin('test.php', 124)];

        $initResults = [
            $this->limiters->initialize($origins[0], 5),
            $this->limiters->initialize($origins[1], 8),
        ];

        $this->assertEquals([0, 5], $initResults[0]);
        $this->assertEquals([0, 8], $initResults[1]);
    }

    /** @test */
    public function it_increments_a_limiter_counter_for_an_origin()
    {
        $origin = $this->createOrigin('test.php', 123);

        $this->limiters->increment($origin);
        $this->limiters->increment($origin);
        [$counter, $limit] = $this->limiters->increment($origin);

        $this->assertEquals(3, $counter);
    }

    /** @test */
    public function it_does_not_increment_a_limiter_counter_for_an_uninitialized_origin()
    {
        $origin = new Origin('test.php', 456);

        $incrementResult = $this->limiters->increment($origin);

        $this->assertEquals([false, false], $incrementResult);
    }

    /** @test */
    public function it_determines_if_a_payload_can_be_sent_for_a_given_origin()
    {
        $origin = $this->createOrigin('test.php', 123, true, 2);

        $this->limiters->increment($origin);
        $this->assertTrue($this->limiters->canSendPayload($origin));

        $this->limiters->increment($origin);
        $this->assertFalse($this->limiters->canSendPayload($origin));
    }

    protected function createOrigin(string $filename, int $line, bool $initialize = true, int $limit = 5)
    {
        $result = new Origin($filename, $line);

        if ($initialize) {
            $this->limiters->initialize($result, $limit);
        }

        return $result;
    }
}
