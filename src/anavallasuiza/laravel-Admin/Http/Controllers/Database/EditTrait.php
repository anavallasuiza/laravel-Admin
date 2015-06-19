<?php
namespace Admin\Http\Controllers\Database;

trait EditTrait
{
    protected function editCommon($row, $form)
    {
        if ($row->id && is_object($processor = $this->processor('delete', null, $row))) {
            return $processor;
        }

        if (is_object($processor = $this->processor(['edit', 'duplicate'], $form, $row))) {
            return $processor;
        }

        if ($processor === null) {
            $form->preload($row);
        }

        return true;
    }
}
