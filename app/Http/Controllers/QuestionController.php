<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\DetailsUserChoose;

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
    public function getAllQuestion()
    {
        // Lấy tất cả câu hỏi từ bảng questions
        $questions = Question::all();

        // Trả về danh sách câu hỏi
        return response()->json(['questions' => $questions], 200);
    }
    public function getIdQuestion($id)
    {
        $question = Question::find($id);
        if (!$question) {
            return response()->json(['message' => 'Question not found'], 404);
        }
        return response()->json(['question' => $question], 200);
    }
    public function checkUserChoose(Request $request, $id)
    {
        $question = Question::find($id);
        if (!$question) {
            return response()->json(['message' => 'Question not found'], 404);
        }
        // Lấy idUser và câu trả lời của người dùng từ request
        $idUser = $request->input('idUser');
        $userChoose = $request->input('user_answer');
        // Kiểm tra xem câu trả lời của người dùng có đúng không
        $isCorrect = $question->answer === $userChoose;
        // Tạo một bản ghi mới trong bảng Details_User_Choose
        $detailsUserChoose = new DetailsUserChoose();
        $detailsUserChoose->idUser = $idUser;
        $detailsUserChoose->question = $question->question; // Chỉ định trường question từ câu hỏi
        $detailsUserChoose->question_id = $question->id;
        $detailsUserChoose->userChoose = $userChoose;
        $detailsUserChoose->answer = $question->answer;
        $detailsUserChoose->image = $question->image;
        $detailsUserChoose->point = $question->point;
        $detailsUserChoose->choose = $question->choose;
        $detailsUserChoose->select = $isCorrect;
        // Cập nhật trường 'is_correct' của bản ghi Details_User_Choose
        $detailsUserChoose->save();
        // Cập nhật trường 'select' của câu hỏi
        // Trả về kết quả
        return response()->json(['message' => 'User answer checked successfully', 'is_correct' => $isCorrect], 200);
    }
    public function getAlldetailsUserChoose(Request $request, $userId)
    {
        $details = DetailsUserChoose::where('idUser', $userId)->get();
        return response()->json(['details' => $details], 200);
    }
    public function getIdDetailsUserChoose($id)
    {
        $details = DetailsUserChoose::find($id);

        if (!$details) {
            return response()->json(['message' => 'Details not found'], 404);
        }
        return response()->json(['details' => $details], 200);
    }
      public function getIdDetailsUserChooseByIdQuestion($id)
    {
        $details = DetailsUserChoose::where('question_id',$id)->get();
        if (!$details) {
            return response()->json(['message' => 'Details not found'], 404);
        }
        return response()->json(['details' => $details], 200);
    }
}
