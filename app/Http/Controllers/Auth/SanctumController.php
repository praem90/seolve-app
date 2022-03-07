<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;

class SanctumController extends Controller
{
	public function token(LoginRequest $loginRequest)
	{
		$user = User::whereEmail($loginRequest->email)->first();

		if (Hash::check($loginRequest->password, $user->password)) {
			return response()->json([
				'token' => $user->createToken('sanctum')->plainTextToken
			]);
		}

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
	}

	public function register(Request $request)
	{
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

		return response()->json([
			'token' => $user->createToken('sanctum')->plainTextToken
		]);
	}
}
