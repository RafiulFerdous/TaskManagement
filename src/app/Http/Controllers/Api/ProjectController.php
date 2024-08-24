<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    use HttpResponses;
    public function store(ProjectRequest $form)
    {
        DB::beginTransaction();
        try {
            $task = Project::create($form->persist());
            DB::commit();
            return new ProjectResource($task->load('user'));
        } catch(\Exception $e) {
            DB::rollBack();
            return $this->respondError('Failed to create task. '.$e->getMessage(), 400);
        }
    }
}
