<?php namespace Admin\Models;

use Illuminate\Database\Eloquent\Model as LModel;
use Auth;

class Model extends LModel
{
    protected $guarded = ['id'];

    public function scopeFilter($query, $filter)
    {
        if (isset($filter['search-q']) && isset($filter['search-q']) && strlen($filter['search-q'])) {
            $query->search($filter['search-q'], $filter['search-c']);
        }

        if (isset($filter['sort']) && $filter['sort']) {
            list($field, $mode) = explode(' ', $filter['sort']);
            $query->orderBy($field, $mode);
        }

        return $query;
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
        if (empty($data['id'])) {
            $row = $this->create(array_filter($data, function ($value) {
                return !is_null($value);
            }));
        } else {
            if (empty($row)) {
                $row = $query->where('id', '=', $data['id'])->firstOrFail();
            }

            foreach ($data as $key => $value) {
                $row->$key = $value;
            }

            $row->save();
        }

        $user = Auth::user();

        Log::insert([
            'created_at' => date('Y-m-d H:i:s'),
            'related_table' => str_replace('admin_', 'management.', $this->getTable()),
            'related_id' => $row->id,
            'action' => (empty($data['id']) ? 'insert' : 'update'),
            'users_id' => ($user ? $user->id : 0),
        ]);

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
