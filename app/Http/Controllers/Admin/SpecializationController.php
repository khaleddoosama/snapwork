<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Yoeunes\Toastr\Facades\Toastr;

class SpecializationController extends Controller
{

    public function index()
    {
        $specializations = Specialization::all();
        return view('admin.specialization.index', compact('specializations'));
    }


    public function create()
    {
        return view('admin.specialization.create');
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:specializations',
        ]);

        Specialization::create($data);

        Toastr::success(__('messages.specialization_created'), __('status.success'));

        return redirect()->route('admin.specialization.index');
    }


    public function edit(Specialization $specialization)
    {
        return view('admin.specialization.edit', compact('specialization'));
    }


    public function update(Request $request, Specialization $specialization)
    {
        $data = $request->validate([
            'name' => 'required|unique:specializations,name,' . $specialization->id,
        ]);

        $specialization->update($data);

        Toastr::success(__('messages.specialization_updated'), __('status.success'));

        return redirect()->route('admin.specialization.index');
    }


    public function destroy(Specialization $specialization)
    {
        $specialization->delete();
        Toastr::success(__('messages.specialization_deleted'), __('status.success'));
        return redirect()->route('admin.specialization.index');
    }
}
