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
        $user = User::create($request->all());

        return $this->showMessage("Bienvenido $user->name es un placer tenerte con nosotros");
    }

    public function login(LoginRequest $request)
    {

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return $this->showMessage('Credenciales incorrectas, intenta de nuevo', 401);
        }

        $tokenResult = $request->user()->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addMonth();
        }

        $token->save();

        return $this->showLoginInfo([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'roles' => auth()->user()->roles->pluck('name'),
            'permissions' => auth()->user()->getAllPermissions()->pluck('name')
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return $this->showMessage('Tu sesion se ha cerrado, te extrañaremos');
    }
}
