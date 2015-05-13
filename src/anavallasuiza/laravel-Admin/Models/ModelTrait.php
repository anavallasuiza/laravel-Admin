<?php

namespace Admin\Models;

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
        if (strlen($q) === 0) {
            return $query;
        }

        $columns = array_filter(is_array($columns) ? $columns : [$columns]);
        $columns = $columns ?: Schema::getColumnListing($this->getTable());

        $q = '%'.str_replace(' ', '%', trim($q)).'%';

        return $query->where(function ($query) use ($columns, $q) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', $q);
            }
        });
    }

    public function scopeRelated($query, $table, $row)
    {
        $rows = $query->with([$table => function ($q) use ($table, $row) {
                $q->where($table.'.id', $row->id);
            }])->get();

        foreach ($rows as $row) {
            if (count($row->$table)) {
                $row->related = $row->$table->first();
            } else {
                $row->related = false;
            }
        }

        return $rows->sortByDesc('related');
    }

    public function scopeReplace($query, array $data, $row = null)
    {
        $action = empty($row->id) ? 'insert' : 'update';

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                continue;
            }

            if (empty($value)) {
                $value = strstr('_id', $key) ? null : $value;
            }

            $row->$key = $value;
        }

        $row->save();

        Log::register($action, $this->getTable(), $row);

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
