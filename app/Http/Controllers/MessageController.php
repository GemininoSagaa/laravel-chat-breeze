<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Events\MessageSent;
use App\Events\UserTyping;

class MessageController extends Controller
{
    public function index(User $friend = null, Group $group = null)
    {
        $user = auth()->user();
        
        $friends = User::whereHas('sentFriendRequests', function($query) use ($user) {
                $query->where('friend_id', $user->id)->where('status', 'accepted');
            })->orWhereHas('receivedFriendRequests', function($query) use ($user) {
                $query->where('user_id', $user->id)->where('status', 'accepted');
            })->get();
        
        $groups = $user->groups;
        
        $messages = collect([]);
        $currentChat = null;
        $isGroup = false;
        
        if ($friend) {
            $messages = Message::where(function($query) use ($user, $friend) {
                    $query->where('sender_id', $user->id)->where('receiver_id', $friend->id);
                })->orWhere(function($query) use ($user, $friend) {
                    $query->where('sender_id', $friend->id)->where('receiver_id', $user->id);
                })
                ->orderBy('created_at')
                ->with('sender')
                ->get();
            
            $currentChat = $friend;
        } elseif ($group) {
            $messages = $group->messages()->with('sender')->orderBy('created_at')->get();
            $currentChat = $group;
            $isGroup = true;
        }
        
        return Inertia::render('Chat/Index', [
            'friends' => $friends,
            'groups' => $groups,
            'messages' => $messages,
            'currentChat' => $currentChat,
            'isGroup' => $isGroup,
        ]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'receiver_id' => 'required_without:group_id|nullable|exists:users,id',
            'group_id' => 'required_without:receiver_id|nullable|exists:groups,id',
        ]);
        
        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'group_id' => $request->group_id,
            'content' => $request->content,
        ]);
        
        // Cargar la relación sender para incluirla en el evento
        $message->load('sender');
        
        // Disparar evento para tiempo real
        broadcast(new MessageSent($message))->toOthers();
        
        return back();
    }
    
    public function userTyping(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required_without:group_id|nullable|exists:users,id',
            'group_id' => 'required_without:receiver_id|nullable|exists:groups,id',
        ]);
        
        broadcast(new UserTyping(
            auth()->user(),
            $request->receiver_id,
            $request->group_id
        ))->toOthers();
        
        return response()->json(['success' => true]);
    }
}