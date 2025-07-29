<?php

namespace Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::with(['subject_user'])->latest()->paginate(15);
        return view('crm::activities.index', compact('activities'));
    }

    public function create()
    {
        return view('crm::activities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_type' => 'required|string',
            'subject_id' => 'required|integer',
            'description' => 'required|string',
            'properties' => 'nullable|array',
        ]);

        Activity::create($request->all());
        return redirect()->route('crm.activities.index')->with('success', 'Activity created successfully.');
    }

    public function show(Activity $activity)
    {
        return view('crm::activities.show', compact('activity'));
    }

    public function edit(Activity $activity)
    {
        return view('crm::activities.edit', compact('activity'));
    }

    public function update(Request $request, Activity $activity)
    {
        $request->validate([
            'subject_type' => 'required|string',
            'subject_id' => 'required|integer',
            'description' => 'required|string',
            'properties' => 'nullable|array',
        ]);

        $activity->update($request->all());
        return redirect()->route('crm.activities.index')->with('success', 'Activity updated successfully.');
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();
        return redirect()->route('crm.activities.index')->with('success', 'Activity deleted successfully.');
    }
}
