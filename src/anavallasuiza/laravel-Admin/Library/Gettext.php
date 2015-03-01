<?php namespace Admin\Library;

use Exception;
use App, Config, Input, Redirect, Session;
use Gettext\Extractors, Gettext\Generators, Gettext\Translations, Gettext\Translator;

class Gettext {
    private static function base($locale)
    {
        return str_replace('%s', $locale, storage_path('locale/%s/LC_MESSAGES/messages.'));
    }

    private static function locales()
    {
        return array_keys(Config::get('app.locales'));
    }

    private static function getCache($locale)
    {
        if (is_file($file = self::base($locale).'po')) {
            return Extractors\Po::fromFile($file);
        }

        return false;
    }

    private static function store($locale, $entries)
    {
        $file = self::base($locale);
        $dir = dirname($file);

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        Generators\Mo::toFile($entries, $file.'mo');
        Generators\Po::toFile($entries, $file.'po');
        Generators\PhpArray::toFile($entries, $file.'php');

        return $entries;
    }

    private static function scan(array $dirs)
    {
        Extractors\PhpCode::$functions = [
            '__' => '__',
            '_' => '__'
        ];

        $entries = new Translations;

        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                throw new Exception(__('Folder %s not exists. Gettext scan aborted.', $dir));
            }

            foreach (self::scanDir($dir) as $file) {
                if (strstr($file, '.blade.php')) {
                    $entries->mergeWith(Extractors\Blade::fromFile($file));
                } elseif (strstr($file, '.php')) {
                    $entries->mergeWith(Extractors\PhpCode::fromFile($file));
                }
            }
        }

        return $entries;
    }

    private static function scanDir($dir)
    {
        $directory = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::LEAVES_ONLY);

        $files = [];

        foreach ($iterator as $fileinfo) {
            $name = $fileinfo->getPathname();

            if (!strpos($name, '/.')) {
                $files[] = $name;
            }
        }

        return $files;
    }

    public static function load()
    {
        $locales = Config::get('app.locales');
        $session = Session::get('locale');
        $input = Input::get('locale');

        if (empty($session) || !array_key_exists($session, $locales)) {
            $session = Config::get('app.locale');
        }

        if ($input && ($input !== $session) && array_key_exists($input, $locales)) {
            $session = $input;
        }

        Session::set('locale', $session);
        App::setLocale($session);

        if (array_key_exists($session, $locales)) {
            $locale = $locales[$session].'.UTF-8';
        } else {
            reset($locales);
            $locale = current($locales).'.UTF-8';

            if (php_sapi_name() == 'cli') {
                echo sprintf('Warning: You must have installed "%s" locales on your environment for a proper generation of the translations.', implode(', ', $locales)).PHP_EOL.PHP_EOL;
            }
        }

        $domain = 'messages';

        # IMPORTANT: locale must be installed in server!
        # sudo locale-gen es_ES.UTF-8
        # sudo update-locale

        putenv('LC_ALL='.$locale);
        setlocale(LC_ALL, $locale);

        bindtextdomain($domain, storage_path('locale'));
        bind_textdomain_codeset($domain, 'UTF-8');
        textdomain($domain);

        # Also, we will work with gettext/gettext library
        # because PHP gones crazy when mo files are updated

        $path = storage_path('locale/'.$session.'/LC_MESSAGES');
        $file = $path.'/'.$domain;

        if (is_file($file.'.php')) {
            $translations = $file.'.php';
        } elseif (is_file($file.'.mo')) {
            $translations = Translations::fromMoFile($file.'.mo');
        } elseif (is_file($file.'.po')) {
            $translations = Translations::fromPoFile($file.'.po');
        } else {
            $translations = new Translations;
        }

        Translator::initGettextFunctions((new Translator)->loadTranslations($translations));
    }

    public static function get($locale, $refresh = true)
    {
        if (empty($refresh) && ($cache = self::getCache($locale))) {
            return $cache;
        }

        $entries = clone self::scan();

        if (is_file($file = self::base($locale).'mo')) {
            $entries->mergeWith(Extractors\Mo::fromFile($file));
        }

        self::store($locale, $entries);

        return $entries;
    }

    public static function set($locale, $translations)
    {
        if (empty($translations)) {
            return true;
        }

        $entries = self::getCache($locale) ?: (new Translations);

        foreach ($translations as $msgid => $msgstr) {
            $msgid = urldecode($msgid);

            if (!($entry = $entries->find(null, $msgid))) {
                $entry = $entries->insert(null, $msgid);
            }

            $entry->setTranslation($msgstr);
        }

        self::store($locale, $entries);

        return true;
    }
}
