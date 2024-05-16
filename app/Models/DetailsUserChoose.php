<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailsUserChoose extends Model
{
    protected $table = 'Details_User_Choose'; // Đúng tên bảng trong cơ sở dữ liệu

    protected $fillable = ['idUser', 'question_id', 'userChoose', 'select']; // Thêm các trường cần điền dữ liệu

    protected $casts = [
        'options' => 'json', // Cast trường options sang kiểu json
        'choose' => 'json', // Cast trường choose sang kiểu json
    ];

    // Định nghĩa mối quan hệ với bảng users nếu cần
    public function user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }

    // Định nghĩa mối quan hệ với bảng questions nếu cần
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}

?>
