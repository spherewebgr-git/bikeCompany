<?php

namespace App\Http\Controllers;

use App\Mail\ContactUsMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactUsController extends Controller
{
    public function index(): View
    {
        return view('contact-us');
    }

    public function send(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'subject' => [
                'required',
                'string',
                'max:150',
            ],
            'message' => [
                'required',
                'string',
                'max:3000',
            ],
        ], [
            'name.required' => 'Please enter your name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'subject.required' => 'Please enter a subject.',
            'message.required' => 'Please enter your message.',
        ]);

        $staffUsers = User::whereHas('role', function ($query) {
            $query->where('name', 'staff');
        })->get();

        if ($staffUsers->isEmpty()) {
            return back()
                ->withInput()
                ->withErrors([
                    'contact' => 'Your message could not be sent because no staff email is available.',
                ]);
        }

        foreach ($staffUsers as $staff) {
            Mail::to($staff->email)->queue(
                new ContactUsMail(
                    senderName: $validated['name'],
                    senderEmail: $validated['email'],
                    contactSubject: $validated['subject'],
                    contactMessage: $validated['message'],
                )
            );
        }

        return back()->with(
            'success',
            'Your message has been sent successfully. Our staff will contact you soon.'
        );
    }
}
