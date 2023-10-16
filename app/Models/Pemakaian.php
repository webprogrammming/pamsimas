<?php

namespace App\Models;

use App\Models\Bulan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pemakaian extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function bulan()
    {
        return $this->belongsTo(Bulan::class);
    }
}
