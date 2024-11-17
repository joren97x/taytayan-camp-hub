<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class ProviderController extends Controller
{
    //
    public function redirect($provider) 
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider) 
    {
        try {
            // Proceed with getting the user data
            $socialiteUser = Socialite::driver($provider)->user();
            
            // Split the full name into first and last name
            $fullName = $socialiteUser->getName();
            $nameParts = explode(' ', $fullName);
            $firstName = $nameParts[0];
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
    
            // Check if the user already exists by email
            $user = User::where('email', $socialiteUser->getEmail())->first();
            
            if (!$user) {
                // If the user doesn't exist, create a new user
                $user = User::create([
                    'provider_id' => $socialiteUser->getId(),
                    'provider' => $provider,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $socialiteUser->getEmail(),
                    'provider_token' => $socialiteUser->token,
                    'email_verified_at' => now(),
                ]);
            } else {
                // If the user exists, update their provider-related information
                $user->update([
                    'provider_id' => $socialiteUser->getId(),
                    'provider' => $provider,
                    'provider_token' => $socialiteUser->token,
                ]);
            }
    
            // Log the user in
            Auth::login($user);
    
            // Redirect to the home page
            return redirect()->route('home');
        } catch (\Exception $e) {
            // If there's any error with OAuth (other than cancellation)
            return redirect()->route('login')->withErrors(['socialite_error' => 'There was an error logging in with ' . ucfirst($provider) . '.']);
        }
    }
}
