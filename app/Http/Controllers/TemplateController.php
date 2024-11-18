<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Http\Requests\StoreTemplateRequest;
use App\Http\Requests\UpdateTemplateRequest;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return 'index';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTemplateRequest $request)
    {
        return 'store';
    }

    /**
     * Display the specified resource.
     */
    public function show(Template $template)
    {
        return 'show';
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTemplateRequest $request, Template $template)
    {
        return 'update';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Template $template)
    {
        return 'destroy';
    }
}
