<?php
namespace Admin\Http\Controllers\Management;

use Redirect;
use Eusonlito\LaravelGettext\Gettext as LGettext;
use Eusonlito\LaravelFormManager\Form;
use Meta;
use Admin\Http\Controllers\Controller;

class Gettext extends Controller
{
    protected function show($form, $config, $locale)
    {
        if (empty($locale)) {
            $locale = $config['locales'][0];
        } elseif (!in_array($locale, $config['locales'], true)) {
            return Redirect::route('admin.management.gettext.app', $config['locales'][0]);
        }

        LGettext::setConfig($config);

        if (is_object($processor = $this->processor(['save', 'download'], $form, $config))) {
            return $processor;
        }

        $entries = LGettext::getEntries($locale);
        $base = base_path();

        foreach ($entries as $entry) {
            $entry->lines = [];

            if (!($references = $entry->getReferences())) {
                continue;
            }

            foreach ($references as $index => $reference) {
                $entry->lines[] = str_replace($base, '', $reference[0].'#'.$reference[1]);
            }
        }

        return self::view('management.gettext.index', [
            'form' => $form,
            'current' => $locale,
            'locales' => $config['locales'],
            'entries' => $entries,
        ]);
    }

    public function app($locale = '')
    {
        $form = new Forms\Gettext\App();

        $config = config('gettext');

        $config['storage'] = base_path($config['storage']);
        $config['domain'] = preg_replace('/\-admin$/', '', $config['domain']);

        foreach ($config['directories'] as $key => $directory) {
            $config['directories'][$key] = base_path($directory);
        }

        Meta::meta('title', __('App Gettext translations'));

        return $this->show($form, $config, $locale);
    }

    public function admin($locale = '')
    {
        $form = new Forms\Gettext\Admin();

        $directory = realpath(__DIR__.'/../../..');

        $config = config('gettext');

        $config['storage'] = base_path($config['storage']);
        $config['directories'] = [$directory];

        Meta::meta('title', __('Admin Gettext translations'));

        return $this->show($form, $config, $locale);
    }
}
