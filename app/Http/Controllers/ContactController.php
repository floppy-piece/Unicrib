<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageMail;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function submit(Request $request)
    {
        // 1) Validate input (server‑side) [web:305]
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email:rfc,dns', 'max:255'],
            'role'    => ['nullable', 'string', 'max:50'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        // 2) Add meta data (IP, user agent) for security/audit
        $data['ip']         = $request->ip();
        $data['user_agent'] = substr((string) $request->userAgent(), 0, 255);

        // 3) Store in DB
        ContactMessage::create($data);

        // 4) Email UniCrib inbox (configure .env mail first) [web:289][web:293]
        try {
            Mail::to('unicrib@gmail.com')->send(new ContactMessageMail($data));
        } catch (\Throwable $e) {
            // Optionally log the failure; DB still has the message
            report($e);
        }

        // 5) Redirect back with success flash message
        return back()->with('success', 'Thanks for reaching out. The UniCrib team will get back to you shortly.');
    }
}
