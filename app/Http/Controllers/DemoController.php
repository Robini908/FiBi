<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DemoRequested;
use Illuminate\Support\Facades\Validator;
use App\Models\DemoRequest;

class DemoController extends Controller
{
    /**
     * Handle the demo booking form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bookDemo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:10|max:20',
            'school' => 'required|string|min:3|max:255',
            'position' => 'required|string|min:3|max:255',
            'preferredDate' => 'required|date|after:today',
            'message' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Store the demo request in the database
        $demoRequest = DemoRequest::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'school' => $request->school,
            'position' => $request->position,
            'preferred_date' => $request->preferredDate,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        // If we have an email setup, send notification to admin
        if (config('mail.mailers.smtp.username')) {
            try {
                Mail::to(config('mail.from.address'))->send(new DemoRequested($demoRequest->toArray()));
            } catch (\Exception $e) {
                // Log the error but don't stop the process
                \Log::error('Failed to send demo request email: ' . $e->getMessage());
            }
        }

        // Show success message using flash
        toast()->success('Your demo request has been received! We will contact you shortly to confirm the details.')->push();

        return redirect()->route('demo');
    }
} 