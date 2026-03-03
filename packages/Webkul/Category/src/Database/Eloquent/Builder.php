<?php

namespace Webkul\Category\Database\Eloquent;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use InvalidArgumentException;
use Kalnoy\Nestedset\QueryBuilder as BaseBuilder;

/**
 * @mixin \Illuminate\Database\Query\Builder
 */
class Builder extends BaseBuilder
{
    /**
     * Paginate the given query.
     *
     * @param  null  $perPage
     * @param  array|string|string[]  $columns
     * @param  string  $pageName
     * @param  null  $page
     * @param  null  $total
     *
     * @throws InvalidArgumentException
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null, $total = null): LengthAwarePaginator
    {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);

        $perPage = $perPage ?: $this->model->getPerPage();

        $results = ($total = $this->toBase()->getCountForPagination($columns))
            ? $this->forPage($page, $perPage)->get($columns)
            : $this->model->newCollection();

        return $this->paginator($results, $total, $perPage, $page, [
            'path'     => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }
}
