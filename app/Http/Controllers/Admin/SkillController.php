<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;
use Yoeunes\Toastr\Facades\Toastr;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::all();
        return view('admin.skill.index', compact('skills'));
    }


    public function create()
    {
        return view('admin.skill.create');
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:skills',
        ]);

        Skill::create($data);

        Toastr::success(__('messages.skill_created'), __('status.success'));

        return redirect()->route('admin.skill.index');
    }


    // public function destroy(Skill $skill)
    // {
    //     $skill->delete();
    //     Toastr::success(__('messages.skill_deleted'), __('status.success'));
    //     return redirect()->route('admin.skill.index');
    // }
}
