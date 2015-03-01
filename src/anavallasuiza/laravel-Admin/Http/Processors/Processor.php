<?php namespace Admin\Http\Processors;

use ErrorException;
use Auth, Input, Request, Session;

abstract class Processor {
    protected $user;
    protected $locale;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->locale = Session::get('locale');
    }

    protected function check($function, $form = null)
    {
        if (($function !== 'login') && empty($this->user)) {
            throw new ErrorException(__('You havent\'t permissions to execute this action'));
        }

        $post = Input::all();

        if (empty($post['_action']) || ($post['_action'] !== $function)) {
            return null;
        }

        if (self::isFake($post, $form)) {
            throw new ErrorException(__('Not allowed'));
        }

        unset($post['_token'], $post['created_at'], $post['updated_at']);

        if ($form === null) {
            return $post;
        }

        $form->loadFromGlobals();

        if ($form->isValid() !== true) {
            $errors = [];

            foreach ($form as $input) {
                if ($input->error()) {
                    $errors[] = $input->attr('placeholder').': '.$input->error();
                }
            }

            throw new ErrorException('<p>'.implode('</p><p>', $errors).'</p>');
        }

        $data = $form->val();

        unset($data['_action'], $data['_token'], $data['created_at'], $data['updated_at']);

        return $data;
    }

    protected static function isFake($post, $form)
    {
        $method = strtolower(Request::method());

        if (($form === null) && ($method === 'get')) {
            $token = true;
        } else {
            $token = (isset($post['_token']) && (csrf_token() === $post['_token']));
        }

        $fake = ($method === 'post') ? ['fake_email', 'fake_url'] : [];

        return (($token === false) || self::isBot($post, $fake));
    }

    protected static function isBot(array $data = [], array $fake = [])
    {
        $bots = [
            'ask jeeves','baiduspider','butterfly','fast','feedfetcher-google','firefly','gigabot',
            'googlebot','infoseek','me.dium','mediapartners-google','nationaldirectory','rankivabot',
            'scooter','slurp','sogou web spider','spade','tecnoseek','technoratisnoop','teoma',
            'tweetmemebot','twiceler','twitturls','url_spider_sql','webalta crawler','webbug',
            'webfindbot','zyborg','alexa','appie','crawler','froogle','girafabot','inktomi',
            'looksmart','msnbot','rabaz','www.galaxy.com','rogerbot'
        ];

        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);

        foreach ($bots as $bot) {
            if (strstr($agent, $bot) !== false) {
                return true;
            }
        }

        foreach ($fake as $field) {
            if (!empty($data[$field])) {
                return true;
            }
        }

        return false;
    }

    protected static function checkTags(array $data)
    {
        $inputs = Input::all();

        foreach ($data as $value) {
            if (is_array($value) && self::checkTags($data)) {
                return true;
            } elseif (is_string($value) && strstr($value, '<')) {
                return true;
            }
        }

        return false;
    }
}