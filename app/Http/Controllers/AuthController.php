<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        // autenticacao por email e senha
        // retornar o jwt Json web token
        $validated = $request->validated();
        $credentials = [
            'email' => $validated['email'],
            'password' => $validated['password']
        ];
        $token = auth('api')->attempt($credentials);
        if ($token) { // foi autenticado
            return response()->json(['token' => $token]);
        } else {
            return response()->json(['Erro' => 'Usuário ou senha inválido!'], 403);
            // 403 = forbidden -> proibido (login inválido)
            // 401 = Unauthorized -> nao autorizado, quando vc nao tem a autorizacao
        }
    }

    public function logout()
    {
        auth('api')->logout(); // logout user
        return response()->json(['mensagem' => 'O usuário foi deslogado com sucesso.']);
    }

    public function refresh()
    {
        return response()->json(['refresh_token' => auth('api')->refresh()]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

}
