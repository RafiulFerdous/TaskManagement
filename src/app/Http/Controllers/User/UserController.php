<?php

namespace App\Http\Controllers\User;

use App\Customs\UploadBuilder;
use App\Http\Controllers\Controller;
use App\Http\Filters\UserFilter;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use HttpResponses;
    public function index(UserFilter $filter)
    {
        return UserResource::collection(
            User::filter($filter)
                ->paginate(request('limit') ?? 10)
        )->additional([
            'success' => true,
            'message' => 'User List'
        ]);
    }

    public function store(UserRequest $form)
    {
        DB::beginTransaction();
        try {
            $user = User::create($form->persist());
            if($user){
                if ($form->file('image')) {
                    $path = $this->uploadUserFile($form);
                    $user->image = $path;
                    $user->save();
                }

            }
            DB::commit();

            return $this->success('User created successfull', new UserResource($user));
        } catch(\Exception $e) {
            DB::rollBack();
            return $this->respondError('Failed to create task. '.$e->getMessage(), 400);
        }
    }

    public function show($id)
    {
        try {
            return $this->success('User found', new UserResource(
                User::whereId($id)
                    ->firstOrFail()
            ));
        } catch(\Exception $e) {
            return $this->respondError('No results found.', 404);
        }
    }


    public function update(UserRequest $form, string $id)
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);
            $user->update($form->persist());
            DB::commit();
            return $this->success('User Updated Successfull', new UserResource($user));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->respondError('Failed to update user.', 400);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            if ($user->delete()) {
                DB::commit();
                return $this->success('User deleted successfully.',true);
            }
            DB::rollBack();

            return $this->respondError("Failed to delete user.");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->respondError("Failed to delete user.");
        }
    }


    public function eagerLoad()
    {
        return [
            'user'
        ];
    }

    public function uploadUserFile(Request $request, $oldFile = null)
    {
        $uploadBuilder = new UploadBuilder();
        $destination ='user';
        $path = $uploadBuilder->storeFile($request->file('image'), $destination, 'public');
        if ($path && $oldFile) {
            $uploadBuilder->deleteFile($oldFile);
        }
        return $path;
    }



}
