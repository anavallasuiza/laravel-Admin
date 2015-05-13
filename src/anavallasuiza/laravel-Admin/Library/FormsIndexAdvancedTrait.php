<?php
namespace Admin\Library;

use FormManager\Builder as B;

trait FormsIndexAdvancedTrait
{
    public function loadIndexAdvanced(array $options)
    {
        $filters = B::collection([
            'f-search-f' => B::select()->options($options),
            'f-search-q' => B::search()->attr([
                'placeholder' => __('Search')
            ])
        ]);

        return $this->method('get')->add([
            'filters' => $filters,
            'f-rows' => B::number(),
            'f-sort-f' => B::text(),
            'f-sort-m' => B::select()->options([
                'ASC' => __('Ascending'),
                'DESC' => __('Descending')
            ])
        ])->setRender('Bootstrap');
    }
}