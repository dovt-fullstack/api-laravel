<?php
namespace App\Http\Controllers;

use App\Models\HistoryExams;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoryExamsController extends Controller
{
    public function UserChoose(Request $request, $postId)
    {
        $HistoryExams = HistoryExams::create([
            'correct_answer' => $request->correct_answer,
            'fail_answer' => $request->fail_answer,
            'score' => $request->score,
            'question_check' => $request->question_check,
            'user_id' => $request->user_id,
            'name_user'=> $request->name_user,
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
       public function rankingController(Request $request)
{
    $period = $request->get('period'); // Lấy giá trị của param 'period'

    if ($period == 'week') {
        $historyExams = HistoryExams::select(
            'id',
            'user_id',
            DB::raw('YEARWEEK(created_at) as week'),
            DB::raw('MAX(score) as max_score')
        )
        ->groupBy('id','user_id','week')
        ->orderBy('week', 'desc')
        ->get();

    } elseif ($period == 'month') {
        $historyExams = HistoryExams::select(
            'id',
            'user_id',
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('MAX(score) as max_score')
        )
        ->groupBy('id', 'user_id','year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

    } else {
        return response()->json(['error' => 'Invalid period'], 400);
    }

    // Lặp qua kết quả và lấy thông tin user từ bảng User
    foreach ($historyExams as $exam) {
        $user = User::find($exam->user_id);
        $exam->user = $user; // Gán thông tin người dùng vào mỗi bản ghi
    }

    // Trả về kết quả
    return response()->json($historyExams);
}
}
