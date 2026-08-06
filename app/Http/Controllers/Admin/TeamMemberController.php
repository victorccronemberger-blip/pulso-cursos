<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FileUploader;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TeamMemberController extends Controller
{
    public function index(Request $request)
    {
        $query = TeamMember::orderBy('sort_order', 'asc')->orderBy('id', 'asc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('designation', 'LIKE', '%' . $search . '%');
            });
        }

        $page_data['teams'] = $query->paginate(10)->appends($request->query());

        return view('admin.team.index', $page_data);
    }

    // =========================
    // CREATE
    // =========================
    public function create()
    {
        return view('admin.team.create');
    }

    // =========================
    // STORE
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|max:255',
            'designation' => 'required|max:255',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except(['_token', 'photo']);

        if ($request->photo) {
            $data['photo'] = "uploads/team/" .
                nice_file_name($request->name, $request->photo->extension());

            FileUploader::upload(
                $request->photo,
                $data['photo'],
                400,
                null,
                300,
                300
            );
        }

        $data['sort_order'] = $request->sort_order ?? 0;
        $data['status']     = $request->status ?? 1;

        TeamMember::create($data);

        return redirect()->route('admin.teams')
            ->with('success', 'Team member created successfully.');
    }

    // =========================
    // EDIT
    // =========================
    public function edit($id)
    {
        $team = TeamMember::findOrFail($id);

        return view('admin.team.edit', compact('team'));
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|max:255',
            'designation' => 'required|max:255',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $team = TeamMember::findOrFail($id);

        $data = $request->except(['_token', '_method', 'photo']);

        // =============================
        // PHOTO UPDATE
        // =============================
        if ($request->photo) {

            // delete old file if exists
            if ($team->photo && file_exists(public_path($team->photo))) {
                unlink(public_path($team->photo));
            }

            $data['photo'] = "uploads/team/" .
                nice_file_name($request->name, $request->photo->extension());

            FileUploader::upload(
                $request->photo,
                $data['photo'],
                400,
                null,
                300,
                300
            );
        }

        $team->update($data);

        return redirect()->route('admin.teams')
            ->with('success', 'Team member updated successfully.');
    }

    // =========================
    // DELETE
    // =========================
    public function delete($id)
    {

        $query = TeamMember::where('id', $id);
        if ($query->doesntExist()) {
            Session::flash('error', get_phrase('Team member not found.'));
            return redirect()->back();
        }

        $team = $query->first();


        if ($team->photo) {
            $filePath = public_path($team->photo);
            if (is_file($filePath)) {
                unlink($filePath);
            }
        }

        $query->delete();

        Session::flash('success', get_phrase('Team member deleted successfully.'));
        return redirect()->back();
    }


    // =========================
    // STATUS TOGGLE
    // =========================
    public function changeStatus($id)
    {
        $team = TeamMember::findOrFail($id);

        $team->status = $team->status == 1 ? 0 : 1;
        $team->save();

        return redirect()->back()
            ->with('success', 'Status updated successfully.');
    }
}
