<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('client.contact');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        Mail::raw(
            "Họ tên: {$validated['name']}\n"
            . "Email: {$validated['email']}\n"
            . "SĐT: " . ($validated['phone'] ?? 'N/A') . "\n\n"
            . $validated['message'],
            function ($mail) use ($validated) {
                $mail->to(config('app.admin.email'))
                     ->subject($validated['subject'])
                     ->replyTo($validated['email'], $validated['name']);
            }
        );

        return back()->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất.');
    }
}
