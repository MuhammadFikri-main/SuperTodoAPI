<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Gate;

class TemplateController extends Controller implements HasMiddleware // implement HasMiddleware
{
    public static function middleware() //create middleware function
    {
        return [
            new Middleware('auth:sanctum') //authorize all routes except index and show , except: ['index', 'show']
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
            $templates = Template::where('user_id', $user->id)->get();

            if ($templates->isEmpty()) {
                return response()->json([
                    'message' => "You do not have any templates listed",
                ]);
            }

            return response()->json([
                'success' => true,
                'template' => $templates
            ], 200); // OK

        }catch (Exception $e){
            return response()->json([
                'x-request-id' => $request->header('X-Request-Id'),
                'error-message' => $e->getMessage(),
            ], 500); // Internal serve error
        }
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

            // Create a new task and associate the authenticated user
            $template = $request->user()->templates()->create([
                'title' => $title,
                'description' => $description
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Task created successfully',
                'template' => $template
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
    public function show(Request $request, Template $template)
    {
        try{

            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'message' => 'User not authenticated',
                ], 401); // Unauthorized
            }
    
            // Check if the authenticated user owns the template
            if ($template->user_id !== $user->id) {
                return response()->json([
                    'message' => 'You do not have permission to view this template',
                ], 403); // Forbidden
            }

            return response()->json([
                'success' => true,
                'template' => $template
            ], 200); // OK

        }catch (Exception $e){
            return response()->json([
                'x-request-id' => $request->header('X-Request-Id'),
                'error-message' => $e->getMessage(),
            ], 500); // Internal serve error
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Template $template)
    {
        Gate::authorize('modify', $template);
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

            // Ensure the user is authenticated before proceeding
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'code' => 'UNAUTHORIZED',
                    'description' => 'User not authenticated',
                    'message' => 'You must be logged in to create a template'
                ], 401); // Unauthorized
            }

            // Create a new task and associate the authenticated user
            $template->update([
                'title' => $title,
                'description' => $description,
                'user_id' => $user->user_id // Associate the template with the authenticated user
            ]);

            return response()->json([
                'success' => true,
                'message' => 'template updated successfully',
                'template' => $template
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
    public function destroy(Template $template)
    {
        Gate::authorize('modify', $template);
        try {
            // Attempt to delete the template
            $template->delete();
    
            return response()->json([
                'success' => true,
                'template' => $template,
                'message' => 'template deleted successfully'
            ], 200); // OK
        } catch (Exception $e) {
            return response()->json([
                'x-request-id' => request()->header('X-Request-Id'),
                'error-message' => $e->getMessage(),
            ], 500); // Internal server error
        }
    }

    /**
     * List all of templates
     */
    public function getListOfTemplates(Request $request){
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

            // Fetch template that is_public
            $templates = Template::where('is_public', true)
            ->with('tasks')->get();

            if ($templates->isEmpty()) {
                return response()->json([
                    'message' => "There is no public templates available",
                ]);
            }

            return response()->json([
                'success' => true,
                'template' => $templates
            ], 200); // OK

        }catch (Exception $e){
            return response()->json([
                'x-request-id' => $request->header('X-Request-Id'),
                'error-message' => $e->getMessage(),
            ], 500); // Internal serve error
        }
    }

    /**
     * Use other template
     * @param \Illuminate\Http\Request $request
     * @param mixed $templateId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function useTemplate(Request $request)
    {
        try{
            $templateId = $request->template_id;

            // Debugging input data
            logger()->info('Task creation data', [
                'user_id' => $request->user()->id,
                'template_id' => $templateId
            ]);

            $template = Template::where('id', $templateId)
                    ->where('is_public', true)
                    ->with('tasks')
                    ->firstOrFail();

            $user = auth()->user();

            // Check if the user has already linked to the template in `user_template`
            if ($user->sharedTemplates->contains($templateId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already used this template.',
                ], 400); // Bad Request
            }

            // Link the template to the user via the pivot table
            $user->sharedTemplates()->attach($templateId);

            // Add template tasks to the user's task list
            foreach ($template->tasks as $task) {
                $user->tasks()->create([
                    'template_id' => $task->template_id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'status' => 'pending',
                    'priority' => $task->priority,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Template tasks added to your task list',
            ]);

        }catch(Exception $e){
            return response()->json([
                'x-request-id' => $request->header('X-Request-Id'),
                'error-message' => $e->getMessage(),
            ], 500); // Internal serve error
        }
        
    }
}
