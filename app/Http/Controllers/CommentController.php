<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    //method atau function menambahkan data comment
    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id', //exists ini fungsinya untuk ngecek apakah ada/exist atau tidak data post yang mau di comment
            'comments_content' => 'required',
        ]);

        //create atau menyimpan data comment
        $request['user_id'] = auth()->user()->id;
        $comment = Comment::create($request->all());

        return new CommentResource($comment->loadMissing(['commentator:id,username'])); //kenapa pakai new karna kita akan create data baru
    }   

    //method atau function update comment
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'comments_content' => 'required',
        ]);

        //menyimpan update comment
        $comment = Comment::findOrFail($id);
        $comment->update($request->only('comments_content'));

        return new CommentResource($comment->loadMissing(['commentator:id,username']));
    }

    //method atau function delete comment
    public function destroy($id){
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return new CommentResource($comment->loadMissing(['commentator:id,username']));
    }
}
