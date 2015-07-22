<?php
namespace Admin\Http\Processors\Database;

use Exception;

trait EditTrait
{
    public function duplicate($form, $row)
    {
        unset($_POST['id']);

        $form['id']->val(0);

        return $this->edit($form, self::getModel());
    }

    public function editCommon($form, $row)
    {
        if (!($data = $this->check(['edit', 'duplicate'], $form))) {
            return $data;
        }

        self::checkDuplicates($data);

        $previous = clone $row;

        self::startFiles($form, $data);

        try {
            $row = self::getModel()->replace(self::filter($data), $row, true);
        } catch (Exception $e) {
            throw new Exception(__('Error storing data: %s', $e->getMessage()));
        }

        self::endFiles($form, $data, $previous);

        session()->flash('flash-message', [
            'message' => __('Data was saved successfully'),
            'status' => 'success',
        ]);

        return $row;
    }

    public function deleteCommon($form, $row)
    {
        if (!($data = $this->check('delete'))) {
            return $data;
        }

        self::deleteFiles($row);

        $row->delete();

        session()->flash('flash-message', [
            'message' => __('Data was saved successfully'),
            'status' => 'success',
        ]);

        return $row;
    }

    protected static function filter(array $data)
    {
        foreach ($data as $field => $value) {
            if (empty($value)) {
                $data[$field] = preg_match('/_id$/', $field) ? null : '';
            }
        }

        return $data;
    }

    private static function checkDuplicates(array $data)
    {
        if (empty(self::$duplicates)) {
            return null;
        }

        $exists = self::getModel()->where(function($q) use ($data) {
            foreach (self::$duplicates as $column) {
                $q->orWhere($column, $data[$column]);
            }
        });

        if ($data['id']) {
            $exists = $exists->where('id', '!=', $data['id']);
        }

        if ($exists->first()) {
            throw new Exception(__('Already exists another content with same %s', implode(__(' or '), self::$duplicates)));
        }
    }

    private static function startFiles($form, array &$data)
    {
        if (empty(self::$files)) {
            return null;
        }

        self::saveFormFiles($form, $data, self::getClass());
    }

    private static function endFiles($form, $data, $previous)
    {
        if (empty(self::$files) || empty($data['id'])) {
            return null;
        }

        self::deleteOldFiles($form, $previous);
    }

    private static function deleteFiles($row)
    {
        if (empty(self::$files)) {
            return null;
        }

        foreach (self::$files as $file) {
            self::deleteFile($row->$file);
        }
    }
}