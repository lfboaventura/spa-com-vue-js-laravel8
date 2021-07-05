<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function add(Request $request)
    {
        $data = $request->all();
        $user = $request->user();
        if (!$data['link']) {
            unset($data['link']);
        }

        if (!$data['image']) {
            unset($data['image']);
        }


        /**validação - inserir */

        try {
            $user->contents()->create($data);
            return response()->json([
                'status' => true,
                'message' => 'Conteúdo cadastrado com sucesso!',
                'contents' => $user->contents,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao cadastrar conteúdo!',
                'errors' => $e->getPrevious(),
            ], 201);
        }
    }
}
