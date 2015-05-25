<?php
namespace Admin\Library;

use Eusonlito\LaravelFormManager\Form as BaseForm;

class Forms extends BaseForm
{
    public function setSanitize()
    {
        foreach ($this as $input) {
            if (strstr($input->attr('class'), 'htmleditor')) {
                $input->sanitize(__NAMESPACE__.'\\Html::fix');
            } elseif ($input->attr('type') === 'text') {
                $input->sanitize('strip_tags');
            }
        }

        return $this;
    }
}