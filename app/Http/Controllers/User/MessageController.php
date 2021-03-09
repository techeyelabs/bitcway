<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\Message;

class MessageController extends Controller
{
    public function index()
    {
        Message::where('user_id', Auth::user()->id)->update(['read_by_user' => true]);
        $data['messages'] = Message::where('user_id', Auth::user()->id)->orderBy('created_at', 'asc')->get();
        return view('user.message.index', $data);
    }
    public function getMessages(Request $request)
    {
        $messages = Message::where('user_id', Auth::user()->id)->get();
        return response()->json(['status' => true, 'messages' => $messages]);
    }
    public function send(Request $request)
    {
        $Message = new Message();
        $Message->type = 1;
        $Message->message = $request->message;
        $Message->read_by_user = true;
        $Message->user_id = Auth::user()->id;
        $Message->save();
        return response()->json(['status' => true, 'item' => $Message->toArray()]);
    }
}
