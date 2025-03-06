<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Mark the current session as logged out
        $sessionId = $request->session()->get('user_session_id');
        if ($sessionId) {
            UserSession::where('id', $sessionId)
                ->update(['logged_out_at' => now()]);
                
            $request->session()->forget('user_session_id');
        }

        // Perform regular logout
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
