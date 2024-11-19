<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\UserTemplate;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserTemplateController extends Controller implements HasMiddleware
{
    public static function middleware() //create middleware function
    {
        return [
            new Middleware('auth:sanctum') //authorize all routes except index and show , except: ['index', 'show']
        ];
    }
    /**
     * Display a list of templates assigned to the authenticated user.
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $templates = UserTemplate::where('user_id', $user->id)->get();

        return response()->json([
            'success' => true,
            'templates' => $templates,
        ]);
    }

    /**
     * Attach a template to the authenticated user.
     */
    public function attachTemplate(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $templateId = $request->template_id;
        $template = Template::find($templateId);

        if (!$template) {
            return response()->json(['
            message' => 'Template not found'
        ], 404);
        }

        $user->templates()->attach($templateId);

        return response()->json([
            'success' => true,
            'message' => 'Template successfully attached to user',
            'template' => $template,
        ]);
    }

    /**
     * Detach a template from the authenticated user.
     */
    public function detachTemplate(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $templateId = $request->input('template_id');
        $template = Template::find($templateId);

        if (!$template) {
            return response()->json(['message' => 'Template not found'], 404);
        }

        $user->templates()->detach($templateId);

        return response()->json([
            'success' => true,
            'message' => 'Template successfully detached from user',
        ]);
    }

    /**
     * List users assigned to a specific template.
     */
    public function getUsersForTemplate($templateId)
    {
        $template = Template::find($templateId);

        if (!$template) {
            return response()->json(['message' => 'Template not found'], 404);
        }

        $users = $template->users;

        return response()->json([
            'success' => true,
            'users' => $users,
        ]);
    }

    /**
     * Attach multiple templates to a user.
     */
    public function attachMultipleTemplates(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $templateIds = $request->input('template_ids'); // Expecting an array of template IDs

        if (!is_array($templateIds) || empty($templateIds)) {
            return response()->json(['message' => 'Invalid template IDs provided'], 400);
        }

        $user->templates()->attach($templateIds);

        return response()->json([
            'success' => true,
            'message' => 'Templates successfully attached to user',
        ]);
    }
}
