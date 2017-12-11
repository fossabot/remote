<?php

namespace Jgile\Remote\Commands;

use Illuminate\Console\Command;

class RemoteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Remote:info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows the Remote package information';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('Package created using Bootpack.');
    }
}
