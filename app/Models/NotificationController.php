<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationController extends Model
{
    use HasFactory;


    protected $fillable = [
        'title',
        'message',
        'sender_id',
        'type',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

}
