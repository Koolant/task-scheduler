<?php

namespace App\Console;

use App\Task;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $tasks = Task::all();
        foreach ($tasks as $task) {
            /**
             * Importantly, we enforce that the invoked command must begin with 'task'. All commands
             * we intend to use as scheduled tasks must begin this way in their signature. This is for
             * safety to prevent accidental or malicious invocation of other artisan commands.
             */
            $schedule->command("task:$task->command")->cron($task->cron);
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
