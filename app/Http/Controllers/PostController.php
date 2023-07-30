<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostDetailResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Faker\Core\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

  //menampilkan data post
  public function index()
  {
    //perbedaan penggunaan loadmissing dan with ialah
    //jika ingin menggunakan with maka penulisannya di pemanggilan elequen nya contoh penulisan ---> $posts = Post::with('writer:id,username)->get();
    //jika menggunakan loadmissing maka penulisannya setelah elequen nya
    $posts = Post::all();
    return PostDetailResource::collection($posts->loadMissing(['writer:id,username', 'comments:id,post_id,user_id,comments_content']));
  }

  //menampilkan detail post
  public function show($id)
  {
    $post = Post::with('writer:id,username')->findOrFail($id);
    return new PostDetailResource($post->loadMissing(['writer:id,username', 'comments:id,post_id,user_id,comments_content']));
  }



  //method atau function create data post baru
  public function store(Request $request)
  {
    //validasi data
    $validated = $request->validate([
      'title' => 'required|max:255',
      'news_content' => 'required',
    ]);


    $image = '';
    //pengecekan upload file atau image
    if ($request->file) {
      $fileName = $this->generateRandomString(); //ngeriname file image secara random
      $extension = $request->file->extension(); //mendapatkan extension image
      $image = $fileName . '.' . $extension;

      //validasi file extension apakah jpg. img, dll


      //upload file image
      Storage::putFileAs('image', $request->file, $image); //image ini adalah nama folder
    }

    //simpan data ke database
    $request['image'] = $image;
    $request['author_id'] = Auth::user()->id;
    $post = Post::create($request->all()); //Post ini ialah nama dari model
    return new PostDetailResource($post->loadMissing('writer:id,username')); //loadmissing fungsinya kalau datanya satu
  }

  //method atau function update data post
  public function update(Request $request, $id) //$id ini fungsinya untuk menangkap id postingan yang mau di update
  {
    $validated = $request->validate([
      'title' => 'required|max:255',
      'news_content' => 'required',
    ]);

    //menyimpan data yang di update
    $post = Post::findOrFail($id); //menangkap postingan
    $post->update($request->all()); //update data

    return new PostDetailResource($post->loadMissing('writer:id,username'));
  }

  //method atau function delete data post
  public function destroy($id)
  {
    $post = Post::findOrFail($id); // mencari id yang mau di delete
    $post->delete();

    return response()->json(['message' => 'data berhasil di hapus.']);
  }

  //random string atau function ialah untuk random name file gambar secara random
  function generateRandomString($length = 30)
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
  }
}
