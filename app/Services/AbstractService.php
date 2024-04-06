<?php
/**
 * Base de Servicos.
 */

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

abstract class AbstractService
{
    abstract public function getBySearch(Builder $buildModel, array $search = []): Builder;
}
