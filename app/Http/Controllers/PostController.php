<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    // Hiển thị danh sách các bài viết
    public function index()
    {
        $posts = Post::all();
        return response()->json(['posts' => $posts], 200);
    }

    // Hiển thị thông tin chi tiết của một bài viết
    public function show($id)
    {
        $post = Post::getPostWithDetails($id);

        if (!$post) {
            return response()->json(['message' => 'Bài viết không tồn tại'], 404);
        }

        return response()->json(['post' => $post], 200);
    }

    // Tạo mới bài viết
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'topic_id' => 'required|exists:topics,id',
        'question_ids' => 'required|array',
    ]);

    $post = Post::create([
        'name' => $request->name,
        'topic_id' => $request->topic_id,
    ]);

    // Thêm các câu hỏi vào bài đăng
    $post->questions()->attach($request->question_ids);

    return response()->json(['message' => 'Bài viết đã được tạo thành công', 'post' => $post], 201);
}








    // Cập nhật thông tin của một bài viết
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Bài viết không tồn tại'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'topic_id' => 'required|exists:topics,id',
        ]);

        $post->update($request->all());

        return response()->json(['message' => 'Bài viết đã được cập nhật thành công', 'post' => $post], 200);
    }

    // Xóa một bài viết
    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Bài viết không tồn tại'], 404);
        }
        $post->delete();
        return response()->json(['message' => 'Bài viết đã được xóa thành công'], 200);
    }
}
