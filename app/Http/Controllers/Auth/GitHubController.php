<?php

namespace App\Http\Controllers\Auth;

use Exception;

use App\Models\User;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GitHubController extends Controller
{
    public function gitRedirect()
    {
        return Socialite::driver('github')
        // ->stateless() ; // اختياري، حسب استخدامك للجلسات
        ->redirect();
    }

    public function handleProviderCallback()
    {
        try {
            $githubUser = Socialite::driver('github')->stateless()->user();
            $user = User::firstOrCreate(
                [
                    'email' => $githubUser->getEmail(),
                ],
                [
                    'first_name' => $githubUser->getName() ?: explode('@',$githubUser->getEmail())[0] ,
                    'github_id' => $githubUser->id,
                    'github_token' => $githubUser->token,
                    'github_refresh_token' => $githubUser->refreshToken,
                    'password' => Hash::make(Str::random(16))
                ]
            );
            if (!$user->github_id) {
                $user->github_id = $githubUser->id;
                $user->github_token = $githubUser->token;
                $user->github_refresh_token = $githubUser->token;
                $user->update();
                return redirect()->route('profile.edit')->with('success', 'Authenticated with github.');
            }
            // Log the user in
            auth()->login($user, true);
            // Redirect to dashboard
            return redirect()->route('profile.show')->with('success', 'Logged in using github account.');
        } catch (Exception $e) {
            if(auth()->user()){
                return redirect()->route('profile.show')->with('error', 'Something went wrong please try again later');
            }
            else return redirect()->route('login')->with('error', 'Something went wrong please try again later');
        }
    }
}
