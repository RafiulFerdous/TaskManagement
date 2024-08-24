<?php

namespace App\Jobs;

use App\Mail\DeadlineMail;
use App\Mail\ServerClosedNotification;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportException;

class SendTaskDeadlineReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->task->user;
        try {
            Log::info('Sending email started...');
            Mail::to($user->email)->send(new DeadlineMail($user));
            Log::info('Email sent successfully.');
        } catch (TransportException $e) {
            Log::error('TransportException: ' . $e->getMessage());

            $this->release(10);
        } catch (\Exception $e) {
            Log::error('Error sending email: ' . $e->getMessage());
            $this->release(10);
        }
    }
}
