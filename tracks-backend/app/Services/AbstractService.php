<?php
/**
 * Base de Servicos.
 */

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModel
 * Classe base de serviços.
 */
abstract class AbstractService
{
    /**
     * Busca e retorna o builder da query.
     *
     * @param Builder $query
     * @param array $search
     * @return Builder
     */
    abstract public function getBySearch(Builder $buildModel, array $search = []): Builder;
}
