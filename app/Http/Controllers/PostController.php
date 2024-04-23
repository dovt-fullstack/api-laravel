<?php

namespace App\Http\Controllers;

use App\Models\TopicPost;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Validation\ValidationException;

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
    try {
        $request->validate([
            'name' => 'required|string|max:255',
            'topic_id' => ['required', 'exists:topics,id'],
            'question_ids' => ['required', 'array', 'exists:questions,id'],
        ]);

        $topic = TopicPost::findOrFail($request->topic_id);
        $post = $topic->posts()->create([
            'name' => $request->name,
        ]);
        $post->questions()->attach($request->question_ids);
        $post = Post::with('topic', 'questions')->findOrFail($post->id);

        // Trả về phản hồi JSON với thông tin chi tiết của bài đăng
        return response()->json(['message' => 'Bài viết đã được tạo thành công', 'post' => $post], 201);

    } catch (ValidationException $e) {
        // Trả về phản hồi JSON với thông báo lỗi khi dữ liệu không hợp lệ
        return response()->json(['message' => 'Dữ liệu không hợp lệ', 'errors' => $e->errors()], 422);

    } catch (\Exception $e) {
        // Trả về phản hồi JSON với thông báo lỗi nếu có lỗi không xác định
        return response()->json(['message' => 'Đã xảy ra lỗi khi xử lý yêu cầu'], 500);
    }
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
