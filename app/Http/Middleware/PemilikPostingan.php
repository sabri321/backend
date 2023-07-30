<?php

namespace App\Http\Middleware;

use App\Models\Post;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PemilikPostingan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //membuat middeware untuk mencegah update data yang bukan postinggannya

        
        $currentUser = Auth::user(); //mendapatkan user siapa yang login
                                     //user ini ialah nama tabel
        $post = Post::findOrFail($request->id); //mendapatkan atau mencari id postingan
        
        //perbandingan id apakah sama atau tidak
        if($post->author_id != $currentUser->id){
            return response()->json(['message' => 'data postingan tidak ditemukan.'], 404);
        }

        
        return $next($request);
    }
}
