<?php

namespace App\Models;

use App\Services\StorageService;
use App\Models\Traits\AllModels;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use AllModels;

    public const VISIBILITY_PRIVATE = 'private';
    public const VISIBILITY_PUBLIC = 'public';

    protected $fillable = [
        'uuid',
        'user_id',
        'account_id',
        'is_thumb',
        'name',
        'size',
        'mimetype',
        'extension',
        'path',
        'description',
        'sync_uuid',
        'visibility',
    ];

    protected $table = 'files';
}
