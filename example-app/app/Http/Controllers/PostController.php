<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{

    //Mostra todos os itens
    public function index()
    {
        return Post::all();
    }

    //Usado para gravar
    public function store(Request $request)
    {
        $requestData  = $request->all();
        $post = Post::create($requestData);

        return response()->json([
            $post,
            "message" => "Post Criado com Sucesso"
        ], 201);
    }
   
    //Mostra uma única postagem
    public function show(Request $request,$post_id)
    {
        $post = Post::find($post_id);
        if ($post) {
            return response()->json([
                $post
            ],201);
        }
        return response()->json([
                'message' => 'Nao foi possivel achar o post'
            ],400);
    }

    //Usado para editar um item
    public function update(Request $request, $post_id)
    {
        try {
            $requestData  = $request->all();
            $post = Post::find($post_id);
            if (!$post) {
                throw new Exception("Nao Foi possivel achar o post");
            }

            $post->usuario = $requestData['usuario'];
            $post->titulo = $requestData['titulo'];
            $post->descricao = $requestData['descricao'];
            $post->save();

            return response()->json([
                $post,
                "message" => "Post atualizado com sucesso"
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                "message" => $th->getMessage()
            ], 200);
        }
    }
    //Remover um item específico
    public function destroy(Request $request, $post_id)
    {
        try {
            $post = Post::find($post_id);
            if (!$post) {
                throw new Exception("Nao Foi possivel achar o post");
            }
            $post->delete();   

            return response()->json([
                $post,
                "message" => "Post deletado"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => $th->getMessage()
            ], 200);
        }
    }
}
