<?php namespace Admin\Http\Controllers\Management;

use Config, Input, Redirect;
use Admin\Http\Controllers\Controller;
use Admin\Library, Meta;

class Gettext extends Controller
{
    public function index($locale)
    {
        if (is_object($action = $this->action(['save', 'download']))) {
            return $action;
        }

        $locales = array_keys(Config::get('app.locales'));

        if (!in_array($locale, $locales, true)) {
            return Redirect::route('admin.management.gettext.index', $locales[0]);
        }

        Library\Gettext::setDirs([app_path(), base_path('vendor/anavallasuiza/laravel-admin')]);

        $entries = Library\Gettext::get($locale, Input::get('refresh'));
        $base = base_path();

        foreach ($entries as $entry) {
            $entry->lines = [];

            if (empty($references = $entry->getReferences())) {
                continue;
            }

            foreach ($references as $index => $reference) {
                $entry->lines[] = str_replace($base, '', $reference[0].'#'.$reference[1]);
            }
        }

        Meta::meta('title', __('Gettext translations'));

        return self::view('management.gettext.index', [
            'current' => $locale,
            'locales' => $locales,
            'entries' => $entries
        ]);
    }
}
