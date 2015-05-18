<?php
namespace Admin\Library;

use Eusonlito\LaravelFormManager\Form as BaseForm;

class Forms extends BaseForm
{
    public function setSanitize()
    {
        foreach ($this as $input) {
            if ($input->getElementName() === 'textarea') {
                $input->sanitize(__NAMESPACE__.'\\Html::fix');
            } elseif ($input->attr('type') === 'text') {
                $input->sanitize('strip_tags');
            }
        }

        return $this;
    }
}