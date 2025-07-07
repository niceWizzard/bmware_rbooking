<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmailVerificationController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function prompt(Request $request): RedirectResponse|Response
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended($request->user()->getDashboardLink())
            : Inertia::render('Auth/VerifyEmail', ['status' => session('status')]);
    }
    /**
     * Send a new email verification notification.
     */
    public function sendEmail(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended($request->user()->getDashboardLink() .'?verified=1');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended($request->user()->getDashboardLink() .'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended($request->user()->getDashboardLink().'?verified=1');
    }
}
