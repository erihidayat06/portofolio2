<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareLink extends Model
{
    /** @use HasFactory<\Database\Factories\ShareLinkFactory> */
    use HasFactory;


    public function shareLink()
    {
        return $this->belongsTo(ShareLink::class);
    }

    public function logs()
    {
        return $this->hasMany(ShareLinkLog::class);
    }
    protected $guarded = ['id'];
}
