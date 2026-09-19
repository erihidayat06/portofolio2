<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShareLinkLog extends Model
{
    protected $fillable = ['share_link_id', 'date', 'views', 'completed'];

    public function shareLink()
    {
        return $this->belongsTo(ShareLink::class);
    }
}
