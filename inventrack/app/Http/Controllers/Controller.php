<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

abstract class Controller
{
    protected function paginate($collection, $perPage, $currentPage, $path): LengthAwarePaginator
    {
        $start = $perPage * ($currentPage - 1);
        $paginated = new LengthAwarePaginator(
            $collection->slice($start, $perPage),
            $collection->count(),
            $perPage,
            $currentPage,
            options: ['path' => $path]
        );
        return $paginated;
    }

    protected function simplePaginate($collection, $perPage, $currentPage, $path)
    {
        $start = $perPage * ($currentPage - 1);
        $paginated = new Paginator(
            $collection->slice($start, $perPage),
            $perPage,
            $currentPage,
            ['path' => $path]
        );
        $nextStart = $perPage * $currentPage;
        $paginated->hasMorePagesWhen(
            $collection->slice($nextStart, $perPage)->count() > 0
        );
        return $paginated;
    }
}
