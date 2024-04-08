<?php

namespace App\Models\Traits;

use DB;
use Log;
use Auth;
use App\Models\File;
use Illuminate\Database\Eloquent\Builder;

trait AllModels
{
    use DateTimeLocale;

    public static function getFill($table = null, array $filters = [], ?string $alias = null): array
    {
        $class = new self();
        if (empty($table)) {
            $table = $class->getTable();
        }

        if (! empty($filters)) {
            $filters = array_intersect($filters, $class->getFillable());
        } else {
            $filters = $class->getFillable();
        }

        return array_map(function ($field) use ($table, $alias) {
            return $table.'.'.$field.' as '.($alias ?? $table).'_'.$field;
        }, $filters);
    }

    public static function getFkName($field): string
    {
        return self::getTableName().'.'.$field;
    }

    public function getImageNotfoundAttribute(): File
    {
        $file = new File();
        $file->path = 'not-found-deskbee.jpg';
        $file->visibility = File::VISIBILITY_PRIVATE;
        $file->is_thumb = true;

        return $file;
    }

    public static function getPkName(): string
    {
        return self::getTableName().'.'.(new self)->getKeyName();
    }

    public static function getTableName(): string
    {
        return (new self)->getTable();
    }

    public static function isJoined(Builder $query, string $table): bool
    {
        $joins = $query->getQuery()->joins;
        if ($joins == null) {
            return false;
        }

        foreach ($joins as $join) {
            if ($join->table == $table) {
                return true;
            }
        }

        return false;
    }

    public static function resetOrders()
    {
        self::getQuery()->orders = null;
    }

    public function scopeFirstByAccount(Builder $query, array $where = [], ?int $accountId = null)
    {
        $accountId = $accountId ?? Auth::user()->account_id;
        $self = (new self);
        if (! empty($where)) {
            $self->where($where);
        }

        $model = $self->first();
        if (! empty($model)) {
            return $model;
        }

        $where['account_id'] = $accountId;

        return (new self)::create($where);
    }

    public function scopeWhereSettingsJson(Builder $query, string $index = '', string $value = ''): Builder
    {
        $field = DB::raw("json_unquote(json_extract(settings, '$.".$index."'))");
        $query->where($field, '=', $value);

        return $query;
    }

    public function scopeWhereAccountLogged($query, $table = ''): Builder
    {
        $user = Auth::user();
        if (empty($user) || empty($user->account_id)) {
            return $query;
        }

        if (empty($table)) {
            $table = self::getTable();
        }

        $query->where($table.'.account_id', $user->account_id);

        return $query;
    }

    public function scopeWhereOperator(
        Builder $builder,
        string $field,
        ?string $operator = null,
        ?string $value = null
    ): Builder {
        switch ($operator) {
            case 'eq':
                $builder->where($field, $value);
                break;

            case 'gt':
                $builder->where($field, '>', $value);
                break;

            case 'gte':
                $builder->where($field, '>=', $value);
                break;

            case 'has':
                $builder->where($field, 'LIKE', '%'.$value.'%');
                break;

            case 'lt':
                $builder->where($field, '<', $value);
                break;

            case 'lte':
                $builder->where($field, '<=', $value);
                break;

            case 'in':
                $builder->whereIn($field, explode(',', $value));
                break;

            case 'range':
                $builder->whereBetween($field, explode(',', $value));
                break;

            default:
                $builder->where($field, 'LIKE', '%'.$value.'%');
                break;
        }

        return $builder;
    }

    public function scopeWherePersonLogged(Builder $query): Builder
    {
        $user = Auth::user();
        $query->wherePersonId($user->person->id);

        return $query;
    }

    public function scopeWhereUserLogged(Builder $query): Builder
    {
        $user = Auth::user();
        $query->whereUserId($user->id);

        return $query;
    }

    public function scopeWhereUuid(Builder $query, string $uuid): Builder
    {
        return $query->whereIn('uuid', explode(',', $uuid));
    }

    public function scopeToSqlDump(Builder $builder, $otherBuilder = null)
    {
        if ($otherBuilder) {
            $builder = $otherBuilder;
        }

        $sql = $builder->toSql();
        foreach ($builder->getBindings() as $key => $binding) {
            $sql = preg_replace('/\?/', "'{$binding}'", $sql, 1);
        }

        return $sql;
    }

    public function scopeToSqlListen(Builder $builder, bool $isDump = false)
    {
        DB::listen(function ($sql) use ($isDump) {
            if ($isDump) {
                printf("%s \n", $sql->sql);

                return;
            }

            Log::info($sql->sql);
        });
    }

    /* Estutura que deve ser usada em um novo Model */

    //
    // -- CONST
    //

    //
    //-- CAST / FILLABLE
    //

    //
    //-- GET_ATTRIBUTE / SET_ATTRIBUTE
    //

    //
    // -- SELECT
    //

    //
    // -- WHERE
    //

    //
    //-- IS

    //
    //-- JOINS
    //

    //
    //-- ORDER
    //

    //
    //-- GET()
    //

    //
    //-- SET()
    //

    //
    //-- UP (Updates)
    //

    //
    //-- BELLONGS / HASMANY
    //
}
