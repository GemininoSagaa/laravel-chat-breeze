<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GroupController extends Controller
{
    public function index()
    {
        $groups = auth()->user()->groups;
        
        return Inertia::render('Groups/Index', [
            'groups' => $groups,
        ]);
    }
    
    public function create()
    {
        $user = auth()->user();
        
        $friends = User::whereHas('sentFriendRequests', function($query) use ($user) {
                $query->where('friend_id', $user->id)->where('status', 'accepted');
            })->orWhereHas('receivedFriendRequests', function($query) use ($user) {
                $query->where('user_id', $user->id)->where('status', 'accepted');
            })->get();
        
        return Inertia::render('Groups/Create', [
            'friends' => $friends,
        ]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'members' => 'required|array|min:1',
            'members.*' => 'exists:users,id',
        ]);
        
        $group = Group::create([
            'name' => $request->name,
            'created_by' => auth()->id(),
        ]);
        
        // Agregar al creador al grupo
        $group->users()->attach(auth()->id());
        
        // Agregar a los miembros seleccionados
        $group->users()->attach($request->members);
        
        return redirect()->route('groups.index');
    }
}