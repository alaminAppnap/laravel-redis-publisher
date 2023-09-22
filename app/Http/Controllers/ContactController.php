<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;

class ContactController extends Controller
{
     public function submit(Request $request)
    {
        $validatedData = $request->validate([
            'phone' => 'required|number|min:11|max:11',
            'message' => 'required|string',
            'send_to' => 'required|string',
        ]);


        if ($validatedData['send_to'] == 'slack' || $validatedData['send_to']=='all') {
            Redis::publish('slackchannel', json_encode([
                'phone' => $validatedData['phone'],
                'message' => $validatedData['message']
                ]));
        }

        if ($validatedData['send_to'] == 'sms' || $validatedData['send_to']=='all') {
            Redis::publish('smschannel', json_encode([
                'phone' => $validatedData['phone'],
                'message' => $validatedData['message']
                ]));
        }
	

        // Optionally, you can set a success message in the session
        $request->session()->flash('message', 'Contact information send successfully!');

        // Redirect back to the contact form
        return redirect()->back();
    }
}
