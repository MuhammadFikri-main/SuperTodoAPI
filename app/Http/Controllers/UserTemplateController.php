<?php

namespace App\Http\Controllers;

use App\Models\UserTemplate;
use App\Http\Requests\StoreUserTemplateRequest;
use App\Http\Requests\UpdateUserTemplateRequest;

class UserTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserTemplateRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UserTemplate $userTemplate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserTemplateRequest $request, UserTemplate $userTemplate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserTemplate $userTemplate)
    {
        //
    }
}
