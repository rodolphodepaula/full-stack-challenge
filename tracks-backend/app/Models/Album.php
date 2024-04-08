<?php

namespace App\Models;

use App\Models\Traits\AllModels;
use App\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Album extends Model
{
    use SoftDeletes;
    use UsesUuid;
    use AllModels;
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'thumb_path'
    ];

    protected $dates = [
        'created_at',
        'deleted_at',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }
}
