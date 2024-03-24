<?php

namespace App\Http\Controllers;

use App\Events\MessageEvent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index() {
        return Message::with('user')->get();
    }

    public function sendMessage(Request $request) {
        $user = Auth::user();
        $message = new Message();
        $message->message = $request->message;
        $message->user_id = $user->id;
        $message->save();

        broadcast(new MessageEvent($message, $user))->toOthers();

        return ['message' => $message->load('user')];
    }
}
