<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;

class TeamController extends Controller
{
    public function index(){
        $teams=Team::all();
        return view('backend.teams.index', compact('teams'));
    }

    public function create(){
        return view('backend.teams.create');
    }

    public function store(Request $request){
        $request->validate([
            'name'=>'required|string|max:255',
            'note'=>'nullable|string',
        ]);

        Team::create([
            'name'=>$request->name,
            'note'=>$request->note,
        ]);

        return redirect()->route('admin.team.index')->with('success', 'Team created successfully.');
    }
    
    // public function show($id){
    //     $team = Team::findOrFail($id);
    //     return view('backend.teams.show', compact('team'));
    // }

    public function edit($id){
        $team = Team::find($id);
        return view('backend.teams.edit', compact('team'));
    }


    public function update(Request $request, $id){
        
        $team = Team::findOrFail($id);

        $team->name=$request->name;
        $team->note=$request->note;
        $team->save();

        return redirect()->route('admin.team.index')->with('success', 'Updated!');
    }

    public function destroy($id){
        $team=Team::findOrFail($id);
        $team->delete();
        return redirect()->route('admin.team.index')->with('success', 'Team deleted successfully.');
    }


}
