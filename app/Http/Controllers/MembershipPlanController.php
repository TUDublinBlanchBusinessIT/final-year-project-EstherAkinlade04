<?php

namespace App\Http\Controllers;

use App\Models\MembershipPlan;
use Illuminate\Http\Request;

class MembershipPlanController extends Controller
{
    public function index()
    {
        $plans = MembershipPlan::all();

        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'duration_days' => 'required|integer',
        ]);

        MembershipPlan::create($request->all());

        return redirect()
            ->route('admin.membership-plans.index')
            ->with('success','Plan created successfully');
    }

    public function edit($id)
    {
        $plan = MembershipPlan::findOrFail($id);

        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $plan = MembershipPlan::findOrFail($id);

        $plan->update($request->all());

        return redirect()
            ->route('admin.membership-plans.index')
            ->with('success','Plan updated');
    }

    public function destroy($id)
    {
        $plan = MembershipPlan::findOrFail($id);

        $plan->delete();

        return back()->with('success','Plan deleted');
    }
}