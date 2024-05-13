<?php

namespace App\Http\Controllers;

use App\Models\HistoryExams;
use App\Models\Post;
use Illuminate\Http\Request;

class HistoryExamsController extends Controller
{
    public function UserChoose(Request $request, $postId)
    {
        $HistoryExams = HistoryExams::create([
            'correct_answer' => 0,
            'fail_answer' => 0,
            'score' => 0,
            'question_check' => $request->question_check,
            'user_id' => $request->user_id,
        ]);
        return response()->json(['HistoryExams' => $HistoryExams], 201);
    }
    public function getIdHistory(Request $request, $historyId)
    {
        $historyExam = HistoryExams::findOrFail($historyId);

        if (!$historyExam) {
            return response()->json(['message' => 'Không tìm thấy bản ghi'], 404);
        }

        return response()->json(['historyExam' => $historyExam], 200);
    }
    public function getAllHistory()
    {
        $historyExams = HistoryExams::all();
        return response()->json(['historyExams' => $historyExams], 200);
    }
    public function getHistoryByUser($userId)
    {
        $historyExams = HistoryExams::where('user_id', $userId)->get();

        if ($historyExams->isEmpty()) {
            return response()->json(['message' => 'Không có lịch sử cho người dùng này'], 404);
        }

        return response()->json(['historyExams' => $historyExams], 200);
    }
}
