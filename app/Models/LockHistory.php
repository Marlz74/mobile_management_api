<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LockHistory extends Model
{
    use HasFactory;

    protected $fillable = ['device_id', 'action', 'reason', 'performed_by', 'action_at'];
}