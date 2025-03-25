<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\ContactFormSubmitted;

class ContactController extends Controller
{
    /**
     * Handle the contact form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:10|max:20',
            'subject' => 'required|string|min:3|max:255',
            'message' => 'required|string|min:10|max:1000',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // If we have an email setup, send the contact form data
        $contactData = $request->only(['name', 'email', 'phone', 'subject', 'message']);
        
        if (config('mail.mailers.smtp.username')) {
            try {
                Mail::to(config('mail.from.address'))->send(new ContactFormSubmitted($contactData));
            } catch (\Exception $e) {
                // Log the error but don't stop the process
                \Log::error('Failed to send contact form email: ' . $e->getMessage());
            }
        }
        
        // Show success message using our toast system
        toast()->success('Your message has been sent! We will get back to you shortly.')->push();

        return redirect()->back();
    }
} 