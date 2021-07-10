<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function friends($id, Request $request)
    {
        $userLogged = $request->user();
        $userPage = User::find($id);
        $followers = $userPage->followers;

        if ($userLogged->id === $userPage->id){
            $userPage = null;
        }

        return response()->json([
            'status' => true,
            'message' => 'Amigos seguidos!',
            'friendsPage' => $userPage ? $userPage->friends : [],
            'friendsProfile' => $userPage ? ($userLogged->friends()->find($userPage->id) ? true : false) : $userLogged->friends,
            'followers' => $followers,
        ], 201);
    }

    public function follow(Request $request)
    {
        $data = $request->all();
        $user = $request->user();
        $friend = User::find($data['id']);;
        if (!$friend) {
            return response()->json([
                'status' => false,
                'message' => 'Amigo não localizado!',
                'errors' => [],
            ], 201);
        }
        if ($user->id === $friend->id) {
            return response()->json([
                'status' => false,
                'message' => 'Usuário logado não pode seguir ele mesmo!',
                'errors' => [],
            ], 201);
        }
        try {
            $user->friends()->toggle($friend->id);
            return response()->json([
                'status' => true,
                'message' => 'Seguindo amigo!',
                'following' => $user->friends()->find($friend->id) ? true : false,
                'followers' => $friend->followers,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao seguir amigo!',
                'errors' => $e->getPrevious(),
            ], 201);
        }
    }

    public function register(Request $request)
    {
        // $user = User::find(1);
        // $user->token = $user->createToken($user->email)->accessToken;
        // return $user;
        $validator = Validator::make($request->all(), [
            'name'      => 'required|between:2,100',
            'email'     => 'required|email|unique:users|max:50',
            'password'  => 'required|confirmed|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Erro validação de campos!',
                'errors' => $validator->errors(),
            ], 201);
        }

        try {
            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => bcrypt($request->password),
                'image'     => '/profiles/profile.png',
            ]);

            $user->token = $user->createToken($user->email)->accessToken;

            return response()->json([
                'status' => true,
                'message' => 'Usuário registrado com sucesso!',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao registrar usuário!',
                'errors' => $e->getPrevious(),
            ], 201);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Erro validação de campos!',
                'errors' => $validator->errors(),
            ], 201);
        }

        if (!Auth::attempt($validator->validated())) {
            return response()->json([
                'status' => false,
                'message' => 'Login inválido!',
                'errors' => 'Login inválido!',
            ], 201);
        }

        $user = auth()->user();
        $user->token = $user->createToken($user->email)->accessToken;
        return response()->json([
            'status' => true,
            'message' => 'Login realizado com sucesso!',
            'user' => $user
        ], 200);
    }

    public function profile(Request $request)
    {
        $data = $request->all();
        $user = $request->user();
        if (!$data['password']) {
            unset($data['password']);
        }

        if (!$data['image']) {
            unset($data['image']);
        }

        Validator::extend('base64image', function ($attribute, $value, $parameters, $validator) {
            $explode = explode(',', $value);
            $allow = ['png', 'jpg', 'svg', 'jpeg'];
            $format = str_replace(
                [
                    'data:image/',
                    ';',
                    'base64',
                ],
                [
                    '', '', '',
                ],
                $explode[0]
            );
            // check file format
            if (!in_array($format, $allow)) {
                return false;
            }
            // check base64 format
            if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $explode[1])) {
                return false;
            }
            return true;
        });

        $validator = Validator::make(
            $data,
            [
                'name'      => 'required|between:2,100',
                'email'     => 'sometimes|email|unique:users|max:50',
                'password'  => 'sometimes|string|min:6|confirmed',
                'image'     => 'sometimes|base64image',

            ],
            ['base64image' => 'Imagem inválida']
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erro validação de campos!',
                'status' => false,
                'errors' => $validator->errors(),
            ], 201);
        }

        if (isset($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        if (isset($data['image'])) {
            $time = time();
            $mainDirectory = 'profiles';
            $imageDirectory = $mainDirectory . DIRECTORY_SEPARATOR . 'profile_id' . $user->id;
            $ext = substr($data['image'], 11, strpos($data['image'], ';') - 11);
            $urlImage = $imageDirectory . DIRECTORY_SEPARATOR . $time . '.' . $ext;

            $file = str_replace('data:image/' . $ext . ';base64,', '', $data['image']);
            $file = base64_decode($file);


            if (!file_exists($mainDirectory)) {
                mkdir($mainDirectory, 0700);
            }

            if ($user->image) {
                $imgUser = str_replace(asset('/'), '', $user->image);
                if (file_exists($imgUser)) {
                    unlink($imgUser);
                }
            }

            if (!file_exists($imageDirectory)) {
                mkdir($imageDirectory, 0700);
            }

            file_put_contents($urlImage, $file);

            $user->image = $urlImage;
        }

        $user->name = $data['name'];

        try {
            $user->save();
            $user->token = $user->createToken($user->email)->accessToken;

            return response()->json([
                'status' => true,
                'message' => 'Atualização profile realizada com sucesso!',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao realizado atualização profile!',
                'errors' => $e->getPrevious(),
            ], 201);
        }
    }
}
