<?php

namespace App\Console\Commands;

use App\Task;
use Illuminate\Console\Command;

class ViewTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'View current task schedule';

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
        $headers = ['Cron', 'Command'];
        $tasks = Task::all(['cron', 'command'])->toArray();
        $this->table($headers, $tasks);
    }
}
