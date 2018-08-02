<?php

use Mockery as m;

class ExtensionTest extends \PHPUnit\Framework\TestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * @group ext
     */
    public function test_has_errors_string_detection(){
        $gateway = $this->getGateway();
        $gateway->shouldReceive('run')->andReturn(false);
        $gateway->shouldReceive('nextLine')->times(2)->andReturn("Errors", null);
        $connection = m::mock(\Jgile\Remote\Connection::class)->makePartial();
        $connection->shouldReceive("getGateway")->andReturn($gateway);
        $connection->run("ls");

        $this->assertTrue($connection->hasErrors());
    }

    /**
     * @group ext
     */
    public function test_collection_returns_string_and_array(){
        $gateway = $this->getGateway();
        $gateway->shouldReceive('run')->andReturn(false);
        $gateway->shouldReceive('nextLine')->times(2)->andReturn("check", null);

        $connection = m::mock(\Jgile\Remote\Connection::class)->makePartial();
        $connection->shouldReceive("getGateway")->andReturn($gateway);
        $connection->run("ls");

        $this->assertEquals("check", $connection->getOutputString());
        $this->assertEquals(["check"], $connection->getOutputArray());
    }

    /**
     * @group ext
     */
    public function test_callback_process_output_correctly(){
        $gateway = $this->getGateway();
        $gateway->shouldReceive('run')->andReturn(false);
        $gateway->shouldReceive('nextLine')->times(3)->andReturn("check", null);

        $connection = m::mock(\Jgile\Remote\Connection::class)->makePartial();
        $connection->shouldReceive("getGateway")->andReturn($gateway);
        $connection->run("ls");

        $this->assertEquals("check", $connection->getOutputString());

        $connection->run("ls", function(){
            //
        });

        $this->assertEquals("check", $connection->getOutputString());
    }

    public function getGateway()
    {
        $files = m::mock('Illuminate\Filesystem\Filesystem');
        $files->shouldReceive('get')->with('keypath')->andReturn('keystuff');
        $gateway = m::mock('Collective\Remote\SecLibGateway', [
            '127.0.0.1:22',
            ['username' => 'taylor', 'key' => 'keypath', 'keyphrase' => 'keyphrase'],
            $files,
            10,
        ])->makePartial();
        $gateway->shouldReceive('getConnection')->andReturn(m::mock('StdClass'));

        return $gateway;
    }
}
