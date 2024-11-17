<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Validator;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Task::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "title" => "required|string",
            "description" => "required"
        ]);

        if($validator->fails()){
            return response()->json([
                'code' => 'UNPROCESSABLE_ENTITY',
                'description' => 'Invalid input data',
                'message' => $validator->errors()->first()
            ],422);
        }

        try{

            $title = $request->title;
            $description = $request->description;
            $user_id = 1; //make it fillable in Task model

            // Ensure the user is authenticated before proceeding
            // $user = auth()->user();

            // if (!$user) {
            //     return response()->json([
            //         'code' => 'UNAUTHORIZED',
            //         'description' => 'User not authenticated',
            //         'message' => 'You must be logged in to create a task'
            //     ], 401); // Unauthorized
            // }

            // Create a new task and associate the authenticated user
            $task = Task::create([
                'title' => $title,
                'description' => $description,
                'user_id' => $user_id // Associate the task with the authenticated user
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Task created successfully',
                'task' => $task
            ], 201); // Created

        } catch (Exception $e){
            return response()->json([
                'x-request-id' => $request->header('X-Request-Id'),
                'error-message' => $e->getMessage(),
            ], 500); // Internal serve error
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return $task;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(),[
            "title" => "required|string",
            "description" => "required"
        ]);

        if($validator->fails()){
            return response()->json([
                'code' => 'UNPROCESSABLE_ENTITY',
                'description' => 'Invalid input data',
                'message' => $validator->errors()->first()
            ],422);
        }

        try{

            $title = $request->title;
            $description = $request->description;
            $user_id = 1; //make it fillable in Task model

            // Ensure the user is authenticated before proceeding
            // $user = auth()->user();

            // if (!$user) {
            //     return response()->json([
            //         'code' => 'UNAUTHORIZED',
            //         'description' => 'User not authenticated',
            //         'message' => 'You must be logged in to create a task'
            //     ], 401); // Unauthorized
            // }

            // Create a new task and associate the authenticated user
            $task->update([
                'title' => $title,
                'description' => $description,
                'user_id' => $user_id // Associate the task with the authenticated user
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully',
                'task' => $task
            ], 200); // OK

        } catch (Exception $e){
            return response()->json([
                'x-request-id' => $request->header('X-Request-Id'),
                'error-message' => $e->getMessage(),
            ], 500); // Internal serve error
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        try {
            // Attempt to delete the task
            $task->delete();
    
            return response()->json([
                'success' => true,
                'task' => $task,
                'message' => 'Task deleted successfully'
            ], 200); // OK
        } catch (Exception $e) {
            return response()->json([
                'x-request-id' => request()->header('X-Request-Id'),
                'error-message' => $e->getMessage(),
            ], 500); // Internal server error
        }
    }
}
