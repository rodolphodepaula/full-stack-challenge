<?php

namespace App\Models;

use App\Models\Traits\AllModels;
use App\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Track extends Model
{
    use SoftDeletes;
    use UsesUuid;
    use AllModels;
    use HasFactory;

    protected $fillable = [
        'album_id',
        'isrc',
        'release_date',
        'duration',
        'spotify_url',
        'preview_url',
        'available_in_brazil'
    ];

    protected $dates = [
        'created_at',
        'deleted_at',
    ];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function artists()
    {
        return $this->belongsToMany(Artist::class, 'artist_track');
    }
}
