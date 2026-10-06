<?php
namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QueryFilterService
{
    /**
     * Handle DataTable filtering, sorting, and pagination dynamically.
     *
     * @param Request $request
     * @param Builder $query
     * @param array $filters
     * @param array $sorts
     * @return array
     */
    public function handle(Request $request, Builder $query, $filters = [], $sorts = []): array
    {
        $totalRecords = $query->count();
        if ($request->has('columns')) {
            $filters['columns'] = $request->input('columns');

            $filters['columns'] = array_filter($filters['columns'], function ($column) {
                return isset($column['searchable']) && $column['searchable'] === 'true';
            });

            $filters['columns'] = array_values($filters['columns']);
        }
        $query = $this->applyFilters($query, $filters);
        if ($request->has('user_type')) {
            $userType = $request->input('user_type');
            $query->whereHas('roles', function (Builder $q) use ($userType) {
                $q->where('role_id', $userType);
            });
        }

        if ($request->has('min_price') && $request->has('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }

        $filteredRecords = $query->count();
        $sorts = $this->getSortingFromRequest($request);
        $query = $this->applySorting($query, $sorts);
        return $query;
        
    }

    /**
     * Apply filters including search, custom filters, and range filters.
     *
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    public function applyFilters(Builder $query, array $filters): Builder
    {
        if (isset($filters['search']) && !empty($filters['search']['value'])) {
            $searchTerm = $filters['search']['value'];
            $searchableColumns = array_column($filters['columns'] ?? [], 'data');

            $query->where(function ($q) use ($searchTerm, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    $q->orWhere($column, 'like', "%{$searchTerm}%");
                }
            });
        }

        foreach ($filters as $key => $value) {
            if ($key === 'search' || $key === 'columns') continue; 

            if (method_exists($this, $key)) {
                $this->{$key}($query, $value);
            } elseif (is_array($value) && count($value) == 2) {
                $query->whereBetween($key, $value);
            } else {
                $query->where($key, 'like', "%{$value}%");
            }
        }

        return $query;
    }

    /**
     * Get sorting parameters from the request.
     *
     * @param Request $request
     * @return array
     */
    protected function getSortingFromRequest(Request $request): array
    {
        $sorts = [];
        if ($request->has('order')) {
            $order = $request->input('order');
            foreach ($order as $sort) {
                $columnIndex = $sort['column'];
                $sortDirection = $sort['dir'];
                $columnName = $request->input("columns.{$columnIndex}.data");
                $sorts[$columnName] = $sortDirection;
            }
        }
        return $sorts;
    }

    /**
     * Apply sorting to the query dynamically.
     *
     * @param Builder $query
     * @param array $sorts
     * @return Builder
     */
    public function applySorting(Builder $query, array $sorts): Builder
    {
        foreach ($sorts as $field => $direction) {
            if (in_array($direction, ['asc', 'desc'])) {
                $query->orderBy($field, $direction);
            }
        }

        return $query;
    }

    /**
     * Custom filter for filtering by state in an address relation.
     *
     * @param Builder $query
     * @param string $value
     * @return void
     */
    protected function state(Builder $query, $value): void
    {
        $query->whereHas('address', function ($q) use ($value) {
            $q->where('state', $value);
        });
    }

    /**
     * Custom filter for filtering by status.
     *
     * @param Builder $query
     * @param string $value
     * @return void
     */
    protected function status(Builder $query, $value): void
    {
        $query->where('status', $value);
    }

    // protected function status_label(Builder $query, $value): void
    // {
    //     $query->where('status', $value);
    // }

    /**
     * Custom filter for filtering by a date range.
     *
     * @param Builder $query
     * @param string $value
     * @return void
     */
    protected function date_range(Builder $query, $value): void
    {
        [$startDate, $endDate] = explode(',', $value);
        $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}


