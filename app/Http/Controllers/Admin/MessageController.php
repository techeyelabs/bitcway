<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Message;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        // $data['messages'] = Message::with('user')->latest()->groupBy('user_id')->get();
        $data['messages'] = Message::with('user')->latest()->groupBy('user_id')->get();
        return view('admin.message.index', $data);
    }
    public function details(Request $request)
    {
        Message::where('user_id', $request->to_id)->update(['read_by_admin' => true]);
        $data['user'] = User::find($request->to_id);
        return view('admin.message.details', $data);
    }
    public function getMessages(Request $request)
    {
        $messages = Message::where('user_id', $request->to_id)->get();
        return response()->json(['status' => true, 'messages' => $messages]);
    }
    public function send(Request $request)
    {
        $Message = new Message();
        $Message->type = 2;
        $Message->message = $request->message;
        $Message->read_by_admin = true;
        $Message->user_id = $request->to_id;
        $Message->save();
        return response()->json(['status' => true, 'item' => $Message->toArray()]);
    }
}
