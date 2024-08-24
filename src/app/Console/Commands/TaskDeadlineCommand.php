<?php

namespace App\Console\Commands;

use App\Jobs\SendTaskDeadlineReminder;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TaskDeadlineCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:task-deadline-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $tasks = Task::where('deadline', '>=', Carbon::now())
            ->where('deadline', '<=', Carbon::now()->addDay())
            ->where('status', 1)
            ->with('user')
            ->get();

        foreach ($tasks as $task) {

            SendTaskDeadlineReminder::dispatch($task)->onQueue('deadlinereminders');;
        }

        $this->info('Task deadline reminders sent successfully.');
    }
}
