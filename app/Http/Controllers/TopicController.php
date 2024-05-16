<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TopicPost;

class TopicController extends Controller
{
    // Hiển thị danh sách các chủ đề
    public function index()
    {
        $topics = TopicPost::all();
        return response()->json(['topics' => $topics], 200);
    }

    // Hiển thị thông tin chi tiết của một chủ đề
    public function show($id)
    {
        $topic = TopicPost::find($id);
        if (!$topic) {
            return response()->json(['message' => 'Chủ đề không tồn tại'], 404);
        }
        return response()->json(['topic' => $topic], 200);
    }

    // Tạo mới chủ đề
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $topic = TopicPost::create($request->all());

        return response()->json(['message' => 'Chủ đề đã được tạo thành công', 'topic' => $topic], 201);
    }

    // Cập nhật thông tin của một chủ đề
    public function update(Request $request, $id)
    {
        $topic = TopicPost::find($id);
        if (!$topic) {
            return response()->json(['message' => 'Chủ đề không tồn tại'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $topic->update($request->all());

        return response()->json(['message' => 'Chủ đề đã được cập nhật thành công', 'topic' => $topic], 200);
    }

    // Xóa một chủ đề
    public function destroy($id)
    {
        $topic = TopicPost::find($id);
        if (!$topic) {
            return response()->json(['message' => 'Chủ đề không tồn tại'], 404);
        }
        $topic->delete();
        return response()->json(['message' => 'Chủ đề đã được xóa thành công'], 200);
    }
}
