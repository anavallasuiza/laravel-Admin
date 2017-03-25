<?php
namespace Admin\Models;

use Illuminate\Database\Eloquent\Collection;
use Schema;

trait ModelTrait
{
    public function scopeFilter($query, $filters)
    {
        self::filterSearch($query, $filters);
        self::filterSort($query, $filters);

        return $query;
    }

    public static function filterSearch($query, $filters)
    {
        if (isset($filters['search-q']) && isset($filters['search-q']) && strlen($filters['search-q'])) {
            $query->search($filters['search-q'], $filters['search-c']);
        }
    }

    public static function filterSort($query, $filters)
    {
        if (isset($filters['sort']) && $filters['sort']) {
            $query->orderBy($filters['sort'][0], $filters['sort'][1]);
        }
    }

    public function scopeSearch($query, $q, $columns = [])
    {
        $q = trim(preg_replace('/[^[[:alnum:]]\s]/', '', $q));

        if (strlen($q) === 0) {
            return $query;
        }

        $columns = array_filter(is_array($columns) ? $columns : [$columns]);
        $columns = $columns ?: Schema::getColumnListing($this->getTable());

        $q = '%'.str_replace(' ', '%', $q).'%';

        return $query->where(function ($query) use ($columns, $q) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', $q);
            }
        });
    }

    public function scopeRelated($query, $relation, $row)
    {
        $rows = $query->with([$relation => function ($q) use ($row) {
                $q->where($row->getTable().'.id', $row->id);
            }])->get();

        foreach ($rows as $row) {
            if (count($row->$relation)) {
                if ($row->$relation instanceof Collection) {
                    $row->related = $row->$relation->first();
                } else {
                    $row->related = $row->$relation;
                }
            } else {
                $row->related = false;
            }
        }

        return $rows->sortByDesc('related');
    }

    public function scopeReplace($query, array $data, $row = null, $log = false)
    {
        $action = empty($row->id) ? 'insert' : 'update';

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                continue;
            }

            if (empty($value) && strstr($key, '_id')) {
                $value = null;
            }

            $row->$key = $value;
        }

        $row->save();

        if ($log) {
            Log::register($action, $this->getTable(), $row);
        }

        return $row;
    }

    public static function setChecksum($rows)
    {
        $fields = self::getChecksumFields();

        foreach ($rows as $row) {
            $checksum = '';

            foreach ($fields as $field) {
                $checksum .= $row->$field;
            }

            $row->checksum = md5($checksum);
        }

        return $rows;
    }

    public static function checkChecksum($checksum, array $data)
    {
        $current = '';

        foreach (self::getChecksumFields() as $field) {
            if (isset($data[$field])) {
                $current .= $data[$field];
            }
        }

        return (md5($current) === $checksum);
    }
}
