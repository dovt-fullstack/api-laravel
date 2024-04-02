<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function createQuestion(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'image' => 'nullable|string',
            'options' => 'required|string',
            'choose' => 'required|array',
            'answer' => 'required|string',
            'point' => 'required|integer',
        ]);
        $question = Question::create([
            'question' => $request->question,
            'image' => $request->image,
            'options' => $request->options,
            'choose' => $request->choose,
            'answer' => $request->answer,
            'point' => $request->point,
        ]);
        return response()->json(['message' => 'Question created successfully'], 201);
    }
    public function deleteQuestion($id)
    {
        $question = Question::find($id);
        if (!$question) {
            return response()->json(['message' => 'Question not found'], 404);
        }
        $question->delete();
        return response()->json(['message' => 'Question deleted successfully'], 200);
    }

    public function updateQuestion(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string',
            'image' => 'nullable|string',
            'options' => 'required|string',
            'choose' => 'required|array',
            'answer' => 'required|string',
            'point' => 'required|integer',
        ]);

        $question = Question::find($id);
        if (!$question) {
            return response()->json(['message' => 'Question not found'], 404);
        }

        // Cập nhật các trường thông tin cơ bản
        $question->question = $request->question;
        $question->image = $request->image;
        $question->options = $request->options;
        $question->answer = $request->answer;
        $question->point = $request->point;

        // Cập nhật trường choose
        $newChoose = [];
        foreach ($request->choose as $chooseItem) {
            $newChoose[] = ['q' => $chooseItem['q']];
        }
        $question->choose = $newChoose;

        // Lưu câu hỏi đã cập nhật
        $question->save();

        return response()->json(['message' => 'Question updated successfully'], 200);
    }

}
