<?php
namespace Admin\Http\Processors\Database;

use Exception;
use Hash;
use Imagecow\Image;

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

        $this->checkDuplicates($data);

        $previous = clone $row;

        $this->startFiles($form, $data, $previous);

        try {
            $row = self::getModel()->replace($this->filter($data), $row, true);
        } catch (Exception $e) {
            throw new Exception(__('Error storing data: %s', $e->getMessage()));
        }

        $this->endFiles($form, $data, $previous);

        $this->checkDeletedAt($data, $row);

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

        $this->deleteFiles($row);

        if (method_exists($row, 'forceDelete')) {
            $row->forceDelete();
        } else {
            $row->delete();
        }

        session()->flash('flash-message', [
            'message' => __('Data was saved successfully'),
            'status' => 'success',
        ]);

        return $row;
    }

    private function checkDuplicates(array $data)
    {
        if (empty($this->duplicates)) {
            return null;
        }

        $exists = self::getModel()->where(function($q) use ($data) {
            foreach ($this->duplicates as $column) {
                $q->orWhere($column, $data[$column]);
            }
        });

        if ($data['id']) {
            $exists = $exists->where('id', '!=', $data['id']);
        }

        if ($exists->first()) {
            throw new Exception(__('Already exists another content with same %s', implode(__(' or '), $this->duplicates)));
        }
    }

    private function filter(array $data)
    {
        foreach ($data as $column => $value) {
            if (empty($value)) {
                $data[$column] = preg_match('/_id$/', $column) ? null : '';
            }
        }

        return $this->setModifiers($data);
    }

    private function setModifiers(array $data)
    {
        $data = $this->setModifyPassword($data);
        $data = $this->setModifySlug($data);

        return $data;
    }

    private function setModifyPassword(array $data)
    {
        if (empty($this->passwords)) {
            return $data;
        }

        foreach ($this->passwords as $column) {
            if (empty($data[$column])) {
                unset($data[$column]);
            } else {
                $data[$column] = Hash::make($data[$column]);
            }
        }

        return $data;
    }

    private function setModifySlug(array $data)
    {
        if (empty($this->slugs)) {
            return $data;
        }

        foreach ($this->slugs as $column) {
            if (isset($data[$column])) {
                $data[$column] = str_slug($data[$column]);
            }
        }

        return $data;
    }

    private function startImages()
    {
        if (empty($this->images)) {
            return null;
        }

        if (empty($this->files)) {
            $this->files = [];
        }

        $this->files = array_unique(array_merge($this->files, array_keys($this->images)));
    }

    private function startFiles($form, array &$data, $previous)
    {
        $this->startImages();

        if (empty($this->files)) {
            return null;
        }

        self::saveFormFiles($form, $data, self::getClass());

        $this->removeDeletedFiles($data, $previous);
    }

    private function removeDeletedFiles(array &$data, $previous)
    {
        foreach ($this->files as $key => $value) {
            $name = is_numeric($key) ? $value : $key;

            if (!self::toDelete($name)) {
                continue;
            }

            if (self::deleteFile($previous->$name)) {
                $data[$name] = '';
            }
        }
    }

    private static function toDelete($name)
    {
        return array_key_exists(self::deleteName($name), $_POST);
    }

    private static function deleteName($name)
    {
        return '_'.$name.'_delete';
    }

    private function endFiles($form, array $data, $previous)
    {
        if (empty($this->files) || empty($data['id'])) {
            return null;
        }

        self::deleteOldFiles($form, $previous);

        $this->endImages($data);
    }

    private function endImages(array $data)
    {
        if (empty($this->images) || empty($data['id'])) {
            return null;
        }

        clearstatcache();

        foreach ($this->images as $name => $size) {
            if (empty($data[$name])) {
                continue;
            }

            if (!is_file($file = self::getStoragePath($data[$name]))) {
                continue;
            }

            list($width, $height) = explode('x', $size);

            Image::create($file)->resize($width, $height)->save();
        }
    }

    private function deleteFiles($row)
    {
        if (empty($this->files)) {
            return null;
        }

        foreach ($this->files as $file) {
            self::deleteFile($row->$file);
        }
    }

    private function checkDeletedAt(array $data, $row)
    {
        if (!isset($data['deleted_at']) || $data['deleted_at']) {
            return true;
        }

        if ($row->trashed() && empty($data['deleted_at'])) {
            $row->restore();
        }
    }
}
