<?php

namespace App\Http\Controllers;

use App\Models\TopicPost;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Question;
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

        // Lấy tên của chủ đề từ relationship
        $topicName = $post->topic->name;

        // Chuyển đổi chuỗi thành mảng
        $questionIds = $post->question_id ? json_decode($post->question_id, true) : [];

        // Lấy tất cả các câu hỏi liên quan đến bài viết
        $questions = Question::whereIn('id', $questionIds)->get();

        return response()->json(['post' => $post, 'topic_name' => $topicName, 'questions' => $questions], 200);
    }

    // Tạo mới bài viết
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'topic_id' => 'required|exists:topics,id',
        ]);

        $post = Post::create($validatedData);

        return response()->json(['post' => $post], 201);
    }

    public function removeQuestionId(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);
        // Xóa question_id từ bài viết
        $post->update(['question_id' => null]);

        return response()->json(['message' => 'Xóa question_id từ bài viết thành công'], 200);
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

    public function addQuestions(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);
        $questionIds = $request->input('question_ids', []);

        // Kiểm tra xem question_id có tồn tại và là một mảng không
        if ($post->question_id && is_array($post->question_id)) {
            // Nếu đã tồn tại và là một mảng, sử dụng giá trị hiện có
            $existingQuestionIds = $post->question_id;
        } else {
            // Nếu không tồn tại hoặc không phải là một mảng, gán một mảng rỗng
            $existingQuestionIds = [];
        }

        // Thêm các question_id mới vào mảng hiện có
        $existingQuestionIds = array_merge($existingQuestionIds, $questionIds);

        // Cập nhật question_id của bài viết
        $post->update(['question_id' => $existingQuestionIds]);

        // Lấy thông tin của các câu hỏi được thêm vào bài viết
        $questions = Question::whereIn('id', $questionIds)->get();

        return response()->json([
            'message' => 'Các ID câu hỏi đã được thêm vào bài viết thành công',
            'post' => $post,
            'questions' => $questions
        ], 201);
    }
}
