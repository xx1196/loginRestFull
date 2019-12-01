<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiController;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserStoreRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends ApiController
{
    public function register(UserStoreRequest $request)
    {
        $fields = $request->all();
        $fields['verified'] = User::USER_NOT_VERIFIED;
        $fields['verified_token'] = User::generateVerifiedToken();
        $fields['admin'] = User::USER_REGULAR;

        $user = User::create($fields);

        return $this->showMessage("Bienvenido $user->name es un placer tenerte con nosotros");
    }

    public function login(LoginRequest $request)
    {

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return $this->showMessage('No estas logueado en nuestros sistemas', 401);
        }

        $tokenResult = $request->user()->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        $token->save();

        return $this->showLoginInfo([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return $this->showMessage('Tu sesion se ha cerrado, te extrañaremos');
    }
}
