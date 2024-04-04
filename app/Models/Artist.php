<?php

namespace App\Models;

use App\Models\Traits\AllModels;
use App\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artist extends Model
{
    use SoftDeletes;
    use UsesUuid;
    use AllModels;
    use HasFactory;

    protected $fillable = ['name'];

    public function tracks()
    {
        return $this->belongsToMany(Track::class, 'artist_track');
    }
}
