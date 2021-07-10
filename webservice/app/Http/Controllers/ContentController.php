<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContentController extends Controller
{
    public function page($id, Request $request)
    {

        $userPage = User::find($id);
        if (!$userPage) {
            return response()->json([
                'status' => false,
                'message' => 'usuário página inválido',
                'errors' => [],
            ], 201);
        }

        $user = $request->user();
        $contents = $userPage->contents()->with("user")->orderBy("created_at", "DESC")->paginate(5);

        foreach ($contents as $key => $content) {
            $content->quantityLikes = $content->likes()->count();
            $content->comments = $content->comments()->with("user")->orderBy("created_at", "DESC")->get();
            $content->liked = $user->likes()->find($content->id) ? true : false;
        }
        return response()->json([
            'status' => true,
            'contents' => $contents,
            'userPage' => $userPage,
        ], 201);
    }

    public function comment($id, Request $request)
    {
        $user = $request->user();
        $content = Content::find($id);
        if (!$content) {
            return response()->json([
                'status' => false,
                'message' => 'Conteúdo inválido',
                'errors' => [],
            ], 201);
        }

        $validator = Validator::make($request->all(), [
            'text' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Erro na inclusão de comentário!',
                'errors' => $validator->errors(),
            ], 201);
        }

        try {
            $user->comments()->create(
                [
                    'content_id' =>  $content->id,
                    'text' => $request->text,
                ]
            );

            return response()->json([
                'status' => true,
                'message' => 'Comentário incluído com sucesso!',
                'quantityLikes' => $content->likes()->count(),
                'liked' => $user->likes()->find($content->id) ? true : false,
                'list' => $this->getList($request),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao incluir comentário!',
                'errors' => $e->getPrevious(),
            ], 201);
        }
    }

    public function liked($id, Request $request)
    {
        $user = $request->user();
        $content = Content::find($id);
        if (!$content) {
            return response()->json([
                'status' => false,
                'message' => 'Conteúdo inválido',
                'errors' => [],
            ], 201);
        }
        try {
            $user->likes()->toggle($content->id);
            return response()->json([
                'status' => true,
                'message' => 'Cometário incluído com sucesso!',
                'list' => $this->getList($request),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao curtir conteúdo!',
                'errors' => $e->getPrevious(),
            ], 201);
        }
    }

    public function getList(Request $request)
    {
        $user = $request->user();
        $contents = Content::with("user")->orderBy("created_at", "DESC");
        if (isset($request->userPage)) {
            $contents = $contents->where('user_id', $request->userPage);
        } else {
            $friends = $user->friends()->pluck('id');
            $friends->push($user->id);
            $contents->whereIn('user_id',$friends);
        }
        $contents = $contents->paginate(5);

        foreach ($contents as $key => $content) {
            $content->quantityLikes = $content->likes()->count();
            $content->comments = $content->comments()->with("user")->orderBy("created_at", "DESC")->get();
            $content->liked = $user->likes()->find($content->id) ? true : false;
        }
        return $contents;
    }

    public function list(Request $request)
    {
        return response()->json([
            'status' => true,
            'contents' => $this->getList($request),
        ], 201);
    }

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


        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'text' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Erro na inclusão de conteúdo!',
                'errors' => $validator->errors(),
            ], 201);
        }

        try {
            $user->contents()->create($data);
            return $this->list($request);
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
