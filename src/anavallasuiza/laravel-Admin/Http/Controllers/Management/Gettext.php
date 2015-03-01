<?php namespace Admin\Http\Controllers\Management;

use Config, Input, Redirect;
use Admin\Http\Controllers\Controller;
use Admin\Library, Meta;

class Gettext extends Controller
{
    public function index($locale)
    {
        $action = Input::get('_action');

        if (empty($action) || !in_array($action, [__FUNCTION__, 'gettextDownload'], true)) {
            $action = null;
        }

        if ($action && is_object($action = $this->action($action))) {
            return $action;
        }

        $locales = array_keys(Config::get('app.locales'));

        if (!in_array($locale, $locales, true)) {
            return Redirect::route('admin.management.gettext.index', $locales[0]);
        }

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
