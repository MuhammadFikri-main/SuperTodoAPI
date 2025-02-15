<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Validator;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Template;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller implements HasMiddleware // implement HasMiddleware
{
    public static function middleware() //create middleware function
    {
        return [
            new Middleware('auth:sanctum') //authorize all routes except index and show , except: ['index', 'show']
        ];
    }

    /*
    * Get Task
    */
    public function getListOfTasks(Request $request)
    {
        try{
            // Retrieve the authenticated user
            $user = auth()->user();

            // Log the user object
            // Log::info('Authenticated user:', ['user' => $user]);

            if (!$user) {
                return response()->json([
                    'message' => 'User not authenticated',
                ], 401); // Unauthorized
            }

            // Fetch tasks belonging to the authenticated user
            $tasks = Task::where('user_id', $user->id)
                ->where('status', '<>', 'completed')
                ->get();

            if ($tasks->isEmpty()) {
                return response()->json([
                    'message' => "You do not have any task listed",
                ]);
            }

            return response()->json([
                'success' => true,
                'task' => $tasks
            ], 200); // OK

        }catch (Exception $e){
            return response()->json([
                'x-request-id' => $request->header('X-Request-Id'),
                'error-message' => $e->getMessage(),
            ], 500); // Internal serve error
        }
    }

    /* 
    * Add Task
    */
    public function addTask(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(),[
            "title" => "required|string",
            "description" => "nullable|string",
            "template_id" => "nullable|exists:templates,id", // Validate template_id if provided
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
            $templateId = $request->template_id; // Optional template ID

            // Check if template_id is provided and belongs to the authenticated user
            if ($templateId) {
                $template = Template::find($templateId);

                // Ensure the authenticated user is the owner of the template
                if (!$template || $template->user_id !== $request->user()->id) {
                    return response()->json([
                        'code' => 'FORBIDDEN',
                        'description' => 'You can only add tasks to your own templates',
                        'message' => 'Template does not belong to you'
                    ], 403);
                }
            }

            // Create a new task and associate the authenticated user
            $task = $request->user()->tasks()->create([
                'title' => $title,
                'description' => $description,
                'template_id' => $templateId // can be null
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

    /*
    * Update Task
    */
    public function updateTask(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "id" => "required|exists:tasks,id",
            "title" => "nullable|string",
            "description" => "nullable"
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
            $status = $request->status;
            $priority = $request->priority;
            $dateline = $request->dateline;

            // Ensure the user is authenticated before proceeding
            $user = auth()->user();

            $task = Task::findOrFail($request->id); // Get the task by ID

            $task->update([
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
                'priority' => $request->priority,
                'dateline' => $request->dateline,
                'user_id' => auth()->id() // Get authenticated user ID
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

    /*
    * Delete Task
    */
    public function deleteTask($id)
    {
        $validator = Validator::make(['id' => $id], [
            "id" => "required|exists:tasks,id"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 'UNPROCESSABLE_ENTITY',
                'description' => 'Invalid input data',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $task = Task::findOrFail($id);
            $task->delete();

            return response()->json([
                'success' => true,
                'task' => $task,
                'message' => 'Task deleted successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'x-request-id' => request()->header('X-Request-Id'),
                'error-message' => $e->getMessage(),
            ], 500);
        }
    }
}
