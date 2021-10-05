<?php

namespace App\Http\Controllers;

use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TokenController extends Controller
{

    public function gerarToken(Request $request) {
        
        // Define a validação para autenticação no api
        $this->validate($request, 
            [
                'email' => 'required|email',
                'password' => 'required'
            ],
            [
                'email.required' => "É necesseário informar o e-mail para autenticação",
                'email.email' => "É necesseário informar um e-mail válido para autenticação",
                'password.required' => "É necesseário informar a senha para autenticação",
            ]
        );

        $data = $request->all();

        // Tenta encontrar o usuário informado no banco
        $usuario = User::where('email', $data['email'])->first();
         
        /* Caso os dados de autenticação ou o usuário 
         * estejam inválidos, não realiza a autenticação
         */
        if(!$usuario || !Hash::check($data['password'], $usuario->password)) {
            return response()->json('Usuário ou senha inválidos', 401);
        }

        // Gera o token de autenticação com o endereço de e-mail
        $token = JWT::encode(['email' => $usuario->email], env('JWT_KEY'));

        return [
            'access_token' => $token
        ];
    }

}