<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FriendshipController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $friends = User::whereHas('sentFriendRequests', function($query) use ($user) {
                $query->where('friend_id', $user->id)->where('status', 'accepted');
            })->orWhereHas('receivedFriendRequests', function($query) use ($user) {
                $query->where('user_id', $user->id)->where('status', 'accepted');
            })->get();
        
        $pendingRequests = Friendship::with('user')
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->get();
        
        $users = User::where('id', '!=', $user->id)
            ->whereDoesntHave('sentFriendRequests', function($query) use ($user) {
                $query->where('friend_id', $user->id);
            })
            ->whereDoesntHave('receivedFriendRequests', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();
        
        return Inertia::render('Friends/Index', [
            'friends' => $friends,
            'pendingRequests' => $pendingRequests,
            'users' => $users,
        ]);
    }
    
    public function sendRequest(User $user)
    {
        Friendship::create([
            'user_id' => auth()->id(),
            'friend_id' => $user->id,
            'status' => 'pending'
        ]);
        
        return back();
    }
    
    public function acceptRequest(Friendship $friendship)
    {
        if ($friendship->friend_id !== auth()->id()) {
            abort(403);
        }
        
        $friendship->update(['status' => 'accepted']);
        
        return back();
    }
    
    public function rejectRequest(Friendship $friendship)
    {
        if ($friendship->friend_id !== auth()->id()) {
            abort(403);
        }
        
        $friendship->delete();
        
        return back();
    }
}