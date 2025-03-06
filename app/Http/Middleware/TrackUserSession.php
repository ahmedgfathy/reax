<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserSession;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackUserSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Get or create a session record
            if (!$request->session()->has('user_session_id')) {
                $sessionData = [
                    'user_id' => $user->id,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'payload' => [
                        'browser' => $this->getBrowser($request->userAgent()),
                        'platform' => $this->getPlatform($request->userAgent()),
                        'device' => $this->getDevice($request->userAgent()),
                    ],
                    'last_activity' => now(),
                ];

                $session = UserSession::create($sessionData);
                $request->session()->put('user_session_id', $session->id);
            } else {
                // Update the last activity time for existing session
                $sessionId = $request->session()->get('user_session_id');
                UserSession::where('id', $sessionId)
                    ->update(['last_activity' => now()]);
            }
        }

        return $next($request);
    }
    
    /**
     * Extract browser information from user agent
     */
    private function getBrowser($userAgent): string
    {
        $browser = 'Unknown';
        
        if (preg_match('/MSIE/i', $userAgent) || preg_match('/Trident/i', $userAgent)) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/Firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Chrome/i', $userAgent)) {
            if (preg_match('/Edge/i', $userAgent)) {
                $browser = 'Microsoft Edge';
            } elseif (preg_match('/Edg/i', $userAgent)) {
                $browser = 'Microsoft Edge (Chromium)';
            } else {
                $browser = 'Chrome';
            }
        } elseif (preg_match('/Safari/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/Opera/i', $userAgent)) {
            $browser = 'Opera';
        }
        
        return $browser;
    }
    
    /**
     * Extract platform information from user agent
     */
    private function getPlatform($userAgent): string
    {
        $platform = 'Unknown';
        
        if (preg_match('/linux/i', $userAgent)) {
            $platform = 'Linux';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $platform = 'macOS';
        } elseif (preg_match('/windows|win32/i', $userAgent)) {
            $platform = 'Windows';
        } elseif (preg_match('/android/i', $userAgent)) {
            $platform = 'Android';
        } elseif (preg_match('/iphone/i', $userAgent)) {
            $platform = 'iOS';
        }
        
        return $platform;
    }
    
    /**
     * Determine if the user is using a mobile device
     */
    private function getDevice($userAgent): string
    {
        if (preg_match('/mobile/i', $userAgent)) {
            return 'Mobile';
        } elseif (preg_match('/tablet/i', $userAgent)) {
            return 'Tablet';
        } else {
            return 'Desktop';
        }
    }
}
