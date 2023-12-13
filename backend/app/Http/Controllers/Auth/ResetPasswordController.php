<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Emails\PasswordResetSuccessEmail;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
     */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function reset(Request $request)
    {
        $messages = [
            'required' => 'A(z) :attribute mező kitöltése kötelező!',
            'max' => 'A jelszó legfeljebb :max karakter lehet.',
            'min' => 'A jelszó legalább :min karakter legyen.',
            'confirmed' => 'A beírt jelszavak nem egyeznek.',
            'regex' => 'Nem megfelelő jelszó',
        ];

        //Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:12|max:32|regex:/^.*(?=.{3,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[$&+,:;=,@#!%_~^]).*$/',
            'token' => 'required'],
            $messages
        );

        //check if payload is valid before moving on
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $password = $request->password;
        // Validate the token
        $exists = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('created_at', '>', Carbon::now()->subHours(1))
            ->exists();
        $tokenData = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();
        if (empty($tokenData)) {
            return redirect()->route('password.request');
        }
        // Redirect the user back to the password reset request form if the token is invalid
        if (! Hash::check($request->token, $tokenData->token) || ! $exists) {
            return view('auth.passwords.email');
        }

        $user = User::where('email', $tokenData->email)->first();
        // Redirect the user back if the email is invalid
        if (! $user) {
            return redirect()->back()->withErrors(['common' => 'Érvénytelen adatok']);
        }

        //Hash and update the new password
        $user->password = Hash::make($password);
        $user->update(); //or $user->save();

        //login the user immediately they change password successfully
        Auth::login($user);

        //Delete the token
        DB::table('password_resets')->where('email', $user->email)
            ->delete();

        //Send Email Reset Success Email
        if ($this->sendSuccessEmail($tokenData->email)) {
            return redirect()->route('admin');
        } else {
            return redirect()->back()->withErrors(['email' => trans('Hibába ütköztünk. Próbáld meg újra.')]);
        }
    }

    private function sendSuccessEmail($email)
    {
        Mail::to($email)->send(new PasswordResetSuccessEmail);

        return 1;
    }
}
