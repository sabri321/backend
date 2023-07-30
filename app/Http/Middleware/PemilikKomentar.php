<?php

namespace App\Http\Middleware;

use App\Models\Comment;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PemilikKomentar
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user(); //menangkap user yang di edit
        $comment = Comment::findOrFail($request->id);//menangkap komentar yang di edit
        
        //mengecek apakah komentar yang diedit milik user yang login
        if($comment->user_id != $user->id){
            return response()->json(['message'=> 'data tidak ditemukan'],404);
        }       
        return $next($request);
    }
}
