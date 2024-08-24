<?php

namespace App\Http\Controllers\Api;

use App\Events\TaskEvent;
use App\Http\Controllers\Controller;
use App\Http\Filters\TaskFilter;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    use HttpResponses;

    public function index(TaskFilter $filter)
    {
        return TaskResource::collection(
            Task::with($this->eagerLoad())
                ->filter($filter)
                ->paginate(request('limit') ?? 10)
        );
    }


    public function store(TaskRequest $form)
    {
        DB::beginTransaction();
        try {
            $task = Task::create($form->persist());
            DB::commit();
            event(new TaskEvent($task->name));
            return new TaskResource($task->load('user'));
        } catch(\Exception $e) {
            DB::rollBack();
            return $this->respondError('Failed to create task. '.$e->getMessage(), 400);
        }
    }

    public function show(string $id)
    {
        try {
            return new TaskResource(
                Task::whereId($id)
                    ->with($this->eagerLoad())
                    ->firstOrFail()
            );
        } catch(\Exception $e) {
            return $this->respondError('No results found.', 404);
        }
    }

    public function update(TaskRequest $form, string $id)
    {
        DB::beginTransaction();
        try {
            $task = Task::findOrFail($id);
            $task->update($form->persistUpdate($task));
            DB::commit();
            return new TaskResource($task);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->respondError('Failed to update task.', 400);
        }
    }


    public function destroy(string $id)
    {
        try {
            $task = Task::findOrFail($id);
            if ($task->delete()) {
                return $this->Success("Task deleted successfully.",true);
            }
            return $this->respondError("Failed to delete task.");
        } catch (\Exception $e) {
            return $this->respondError("Failed to delete task.");
        }
    }

    public function eagerLoad()
    {
        return [
            'user'
        ];
    }

}
