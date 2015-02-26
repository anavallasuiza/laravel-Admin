<?php namespace Admin\Http\Controllers\Forms;

use FormManager\FormElementInterface;
use FormManager\Inputs\Input;
use FormManager\Fields\Field;

use Config, Input as LInput, View;

use Admin\Library;

class Form extends \FormManager\Form {
    public function __construct()
    {
        return $this->method('post');
    }

    public function load($value = NULL, $file = NULL)
    {
        $value = is_object($value) ? $value->toArray() : $value;

        parent::load($value, $file);

        foreach ($this as $field) {
            $this->setLoader($field);
        }
    }

    public function setLoader($field)
    {
        $input = isset($field->input) ? $field->input : $field;

        $type = $input->getElementName();
        $type = ($type === 'input') ? $field->attr('type') : $type;

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

    public function wrapperInput($field)
    {
        if (empty($field->input)) {
            return $field;
        }

        $type = $field->input->getElementName();
        $type = ($type === 'input') ? $field->attr('type') : $type;

        $method = 'wrapper'.$type;

        if (method_exists($this, $method)) {
            return $this->$method($field);
        }

        return $this->wrapperDefault($field);
    }

    public function add($key, FormElementInterface $value = null)
    {
        $languages = array_keys(Config::get('app.locales'));

        if (is_string($key)) {
            $key = [$key => $value];
        }

        foreach ($key as $name => $field) {
            $language = $field->attr('language');

            if (empty($language)) {
                parent::add($name, $field);
                continue;
            }

            if ($language === true) {
                $language = $languages;
            }

            $this->setLanguage($name, $field, $languages);
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

    protected function setLanguage($name, $field, $language)
    {
        if (is_array($language)) {
            foreach ($language as $each) {
                $this->setLanguage($name, $field, $each);
            }

            return $this;
        }

        $placeholder = $field->attr('placeholder');

        $new = clone $field;

        $new->attr('placeholder', $placeholder.' '.__('language-'.$language));
        $new->removeAttr('language');

        return parent::add($name.'-'.$language, $new);
    }

    protected function wrapperDefault($input)
    {
        if ($placeholder = $input->attr('placeholder')) {
            $input->label($placeholder);
        }

        $input->addClass('form-control');

        $input->render(function ($field) {
            return '<div class="form-group">'.$field->label.$field->input.'</div>';
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

        $input->render(function ($field) {
            $editor = View::make('admin.molecules.wysiwyg')->render();

            $editor = str_replace('%id', uniqid(), $editor);
            $editor = str_replace('%name', $field->attr('name'), $editor);
            $editor = str_replace('%value', $field->val(), $editor);

            return '<div class="form-group">'.$field->label.$editor.'</div>';
        });
    }

    protected function wrapperFile($input)
    {
        $input->label($input->attr('placeholder'));
        $input->addClass('form-control');

        $input->render(function ($field) {
            if (!($value = $field->attr('data-value'))) {
                return '<div class="form-group">'.$field->label.$field->input.'</div>';
            }

            $html = $field->label.'<div class="input-group form-group">'.$field->input;

            if ($value = $field->attr('data-value')) {
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

        $input->render(function ($field) {
            return '<div class="checkbox">'
                .'<label>'
                .$field->input.$field->input->attr('placeholder')
                .'</label></div>';
        });
    }

    public static function token()
    {
        return Input::hidden()->name('_token')->value(csrf_token());
    }

    public static function fake()
    {
        $email = Field::email()->attr('name', 'fake_email')->addClass('required');
        $url = Field::text()->attr('name', 'fake_url')->addClass('required');

        return '<div class="hidden">'.$email.$url.'</div>';
    }

    public static function referer($url = '')
    {
        return Input::hidden()->name('referer')->value(LInput::get('referer') ?: ($url ?: getenv('REQUEST_URI')));
    }
}
