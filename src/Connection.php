<?php

namespace Jgile\Remote;

use \Closure;

/**
 * Class Connection
 * @package Jgile\Remote
 */
class Connection extends \Collective\Remote\Connection
{
    /**
     * @var
     */
    protected $stored_output;


    /**
     * Run a set of commands against the connection.
     *
     * @param string|array $commands
     * @param \Closure $callback
     *
     * @return $this
     */
    public function run($commands, Closure $callback = null)
    {
        $gateway = $this->getGateway();

        $callback = $this->getCallback($callback);

        $gateway->run($this->formatCommands($commands));

        while (true) {
            if (is_null($line = $gateway->nextLine())) {
                break;
            }

            call_user_func($callback, $line, $this);
        }

        return $this;
    }


    /**
     * Get the display callback for the connection.
     *
     * @param \Closure|null $callback
     *
     * @return \Closure
     */
    protected function getCallback($callback)
    {
        if (!is_null($callback)) {
            return $callback;
        }

        return function ($line) {
            $this->storeOutput($line);
        };
    }

    /**
     * @return array
     */
    public function getOutputArray()
    {
        return explode(PHP_EOL, $this->stored_output);
    }

    /**
     * @return mixed
     */
    public function getOutputString()
    {
        return (string)$this->stored_output;
    }

    /**
     * Save output to $this->stored_output
     * @param $output
     */
    public function storeOutput($output)
    {
        $this->stored_output = $this->stored_output.$output;
    }

    /**
     * @param $command
     * @return null
     */
    public function commandExists($command)
    {
        return $this->test("hash $command 2>/dev/null");
    }

    /**
     * @param $command
     * @return null
     */
    public function test($command)
    {
        $bool = null;

        $command = $this->formatCommands($command);

        $this->run("if $command; then echo 'true'; fi", function ($line) use (&$bool) {
            $bool = trim($line) === 'true';
        });

        return $bool;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return str_contains(strtolower($this->getOutputString()), "error");
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->getOutputString();
    }
}
