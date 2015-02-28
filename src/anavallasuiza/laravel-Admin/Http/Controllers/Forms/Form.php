<?php namespace Admin\Http\Controllers\Forms;

use FormManager\Builder as F;
use FormManager\Containers\Form as Base;

use Config, Input, View;
use Admin\Library;

class Form extends Base
{
    public function __construct()
    {
        return $this->method('post');
    }

    public function load($value = NULL, $file = NULL)
    {
        $value = is_object($value) ? $value->toArray() : $value;

        parent::load($value, $file);

        foreach ($this as $input) {
            $this->setLoader($input);
        }
    }

    public function setLoader($input)
    {
        $type = $input->getElementName();
        $type = ($type === 'input') ? $input->attr('type') : $type;

        $method = 'load'.$type;

        if (!method_exists($this, $method)) {
            return false;
        }

        $name = $input->attr('name');
        $this->$method($input, isset($value[$name]) ? $value[$name] : '');
    }

    public function setOptions($input, $rows, $title, $empty = false)
    {
        $options = [];

        if ($empty) {
            $options[0] = __('Without Selection');
        }

        foreach ($rows as $row) {
            $options[$row->id] = $row->$title;
        }

        return $this[$input]->options($options);
    }

    public function wrapperInputs()
    {
        foreach ($this as $input) {
            $this->wrapperInput($input);
        }

        return $this;
    }

    public function wrapperInput($input)
    {
        $type = $input->getElementName();
        $type = ($type === 'input') ? $input->attr('type') : $type;

        $method = 'wrapper'.$type;

        if (method_exists($this, $method)) {
            return $this->$method($input);
        }

        return $this->wrapperDefault($input);
    }

    public function add(array $children)
    {
        $languages = array_keys(Config::get('app.locales'));

        foreach ($children as $name => $input) {
            $language = $input->attr('language');

            if (empty($language)) {
                parent::add([$name => $input]);
                continue;
            }

            if ($language === true) {
                $language = $languages;
            }

            $this->setLanguage($name, $input, $languages);
        }

        return $this;
    }

    protected function loadFile($input, $value)
    {
        return $input->attr('data-value', $value);
    }

    protected function loadTextarea($input, $value)
    {
        $class = $input->attr('class');

        if (strstr($class, 'wysiwyg') || strstr($class, 'wysihtml5')) {
            return $input->val(Library\Html::fix($value));
        }

        return $input;
    }

    protected function setLanguage($name, $input, $language)
    {
        if (is_array($language)) {
            foreach ($language as $each) {
                $this->setLanguage($name, $input, $each);
            }

            return $this;
        }

        $placeholder = $input->attr('placeholder');

        $new = clone $input;

        $new->attr('placeholder', $placeholder.' '.__('language-'.$language));
        $new->removeAttr('language');

        return parent::add([$name.'-'.$language => $new]);
    }

    protected function wrapperDefault($input)
    {
        if ($placeholder = $input->attr('placeholder')) {
            $input->label($placeholder);
        }

        $input->addClass('form-control');

        $input->render(function ($input) {
            return '<div class="form-group">'.$input.'</div>';
        });
    }

    protected function wrapperTextarea($input)
    {
        if ($input->attr('class') === 'wysiwyg') {
            return $this->wrapperTextareaWysiwyg($input);
        }

        return $this->wrapperDefault($input);
    }

    protected function wrapperTextareaWysiwyg($input)
    {
        $input->label($input->attr('placeholder'));

        $input->render(function ($input) {
            $editor = View::make('admin::molecules.wysiwyg')->render();

            $editor = str_replace('%id', uniqid(), $editor);
            $editor = str_replace('%name', $input->attr('name'), $editor);
            $editor = str_replace('%value', $input->val(), $editor);

            return '<div class="form-group">'.$input->label.$editor.'</div>';
        });
    }

    protected function wrapperFile($input)
    {
        $input->label($input->attr('placeholder'));
        $input->addClass('form-control');

        $input->render(function ($input) {
            if (!($value = $input->attr('data-value'))) {
                return '<div class="form-group">'.$input.'</div>';
            }

            $html = $input->label.'<div class="input-group form-group">'.$input;

            if ($value = $input->attr('data-value')) {
                if (!strstr($value, '?')) {
                    $value = url('storage/resources/'.$value);
                }

                $html .= '<span class="input-group-btn">'
                    .'<a href="'.$value.'" target="_blank" class="btn btn-primary">'.__('View file').'</a>
                    </span>';
            }

            return $html.'</div>';
        });
    }

    protected function wrapperCheckbox($input)
    {
        $input->label($input->attr('placeholder'));

        $input->render(function ($input) {
            return '<div class="checkbox">'
                .'<label>'
                .$input.$input->attr('placeholder')
                .'</label></div>';
        });
    }

    public static function token()
    {
        return F::group([
            '_token' => F::hidden()->value(csrf_token()),
            'fake_email' => F::email()->addClass('required')->style('display: none'),
            'fake_url' => F::email()->addClass('required')->style('display: none')
        ]);
    }

    public static function referer($url = '')
    {
        $referer = Input::get('referer') ?: ($url ?: getenv('REQUEST_URI'));
        return F::hidden()->name('referer')->value($referer);
    }
}
