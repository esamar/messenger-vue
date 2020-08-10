<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conversation;
use DB;

class ConversationController extends Controller
{
    public function index()
    {

        return Conversation::where( 'user_id' , auth()->id() )
        ->get([
            'id',
            'contact_id',
            'has_blocked',
            'listen_notification',
            'last_message',
            'last_time'
        ]);

    }
}
