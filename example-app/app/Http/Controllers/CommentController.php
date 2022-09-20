<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use Exception;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $commentQuery = Comment::query();
        if($request->post_id){
            $commentQuery->where('fk_postagem_id', $request->post_id);
        }
    
        return response()->json([
            $commentQuery->get()
        ]);
    }

    public function store(Request $request)
    {
        try {
            $requestData  = $request->all();
            if(!Post::find($requestData['post_id'])){
                throw new Exception("post_id nao bate com nenhum post do banco");
            }
            $comment = Comment::create([
                'usuario' => $requestData['usuario'],
                'descricao' => $requestData['descricao'],
                'fk_postagem_id' => $requestData['post_id']
            ]);

            return response()->json([
                $comment,
                "message" => "Comment atualizado com sucesso"
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                "message" => $th->getMessage()
            ], 200);
        }
    }

    public function show(Request $request, $comment_id)
    {
        $comment = Comment::find($comment_id);
        if ($comment) {
            return response()->json([
                $comment
            ],201);
        }
        return response()->json([
                'message' => 'Nao foi possivel achar o Comment'
            ],400);
    }

    public function update(Request $request, $comment_id)
    {
        try {
            $requestData  = $request->all();
            $comment = Comment::find($comment_id);
            if (!$comment) {
                throw new Exception("Nao Foi possivel achar o comment");
            }
            if(!Post::find($requestData['post_id'])){
                throw new Exception("post_id nao bater com nenhum post do banco");
            }
            $comment->usuario = $requestData['usuario'];
            $comment->descricao = $requestData['descricao'];
            $comment->fk_postagem_id = $requestData['post_id'];
            $comment->save();

            return response()->json([
                $comment,
                "message" => "Comment atualizado com sucesso"
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                "message" => $th->getMessage()
            ], 200);
        }
    }

    public function destroy(Request $request, $comment_id)
    {
        try {
            $comment = Comment::find($comment_id);
            if (!$comment) {
                throw new Exception("Nao Foi possivel achar o comentario");
            }
            $comment->delete();   

            return response()->json([
                $comment,
                "message" => "Comentario deletado"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => $th->getMessage()
            ], 200);
        }
    }
}
