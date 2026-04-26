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

    public function sendConsultation(Request $request)
    {
        $validated = $request->validate([
            'consultation_name' => 'required|string|max:255',
            'consultation_phone' => 'required|string|max:20',
            'consultation_area' => 'required|string|max:255',
            'consultation_type' => 'required|in:ca_nhan,nha_vuon,dai_ly',
        ]);

        $typeLabels = [
            'ca_nhan' => 'Cá nhân',
            'nha_vuon' => 'Nhà vườn',
            'dai_ly' => 'Đại lý',
        ];

        $recipient = config('app.consultation_admin_email');

        Mail::raw(
            "Biểu mẫu tư vấn miễn phí\n"
            . "Tên: {$validated['consultation_name']}\n"
            . "Số điện thoại: {$validated['consultation_phone']}\n"
            . "Khu vực: {$validated['consultation_area']}\n"
            . "Tôi là: " . ($typeLabels[$validated['consultation_type']] ?? $validated['consultation_type']),
            function ($mail) use ($recipient) {
                $mail->to($recipient)
                    ->subject('Đăng ký tư vấn miễn phí');
            }
        );

        return back()->with('consultation_success', 'Cảm ơn bạn đã đăng ký tư vấn. Chúng tôi sẽ liên hệ sớm nhất.');
    }
}
