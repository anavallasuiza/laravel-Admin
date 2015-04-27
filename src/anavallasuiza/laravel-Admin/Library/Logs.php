<?php

namespace Admin\Library;

class Logs
{
    private $files = [];
    private $options = [];

    private $file = '';
    private $storage = '';

    public function __construct($options)
    {
        $files = $this->getFiles();

        $this->file = '';
        $this->options = [];

        if (empty($options['log'])) {
            return false;
        }

        $storage = $this->getStorage();

        $file = $storage.$options['log'].'.log';

        if (in_array($file, $files, true)) {
            $this->file = $file;
            $this->setOptions($options);
        }
    }

    private function setOptions(array $options)
    {
        foreach (['log', 'date', 'raw'] as $option) {
            if (empty($options[$option])) {
                $options[$option] = false;
            }
        }

        return $this->options = $options;
    }

    private function getStorage()
    {
        return $this->storage ?: ($this->storage = storage_path('logs/'));
    }

    public function getFiles()
    {
        if ($this->files) {
            return $this->files;
        }

        $this->files = glob($this->getStorage().'*.log');

        rsort($this->files);

        return $this->files;
    }

    public function getFilesNames()
    {
        return array_map(function ($file) {
            return str_replace('.log', '', basename($file));
        }, $this->files);
    }

    public function getContents()
    {
        if (empty($this->file)) {
            return '';
        }

        if ($this->options['raw']) {
            return file_get_contents($this->file);
        }

        $parser = 'parser'.ucfirst(str_replace('.log', '', basename($this->file)));
        $parser = preg_replace('/\-.*$/', '', $parser);

        if (method_exists(__CLASS__, $parser)) {
            return $this->$parser();
        } else {
            return $this->parserDefault();
        }
    }

    private function parserDefault()
    {
        $exp = '\[(\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2})\]\s[a-z]+\.([A-Z]+): (.*)';

        preg_match_all('/'.$exp.'/', file_get_contents($this->file), $headings);

        $log = [];
        $first = false;

        if ($this->options['date']) {
            $first = date('Y-m-d 00:00:00', strtotime('-1 '.$this->options['date']));
        }

        foreach (array_keys($headings[0]) as $i) {
            if ($first && ($headings[1][$i] < $first)) {
                continue;
            }

            $log[] = [
                'date' => $headings[1][$i],
                'status' => $headings[2][$i],
                'class' => $this->getClass($headings[2][$i]),
                'message' => trim($headings[3][$i]),
                'file' => '',
                'line' => '',
                'short' => '',
                'details' => '',
            ];
        }

        return array_reverse($log);
    }

    private function parserLaravel()
    {
        $log = [];

        foreach ($this->parserDefault() as $row) {
            if (preg_match('/^(.*?) in ([^:]+):([0-9]+)$/', $row['message'], $details)) {
                $row['file'] = $details[2];
                $row['line'] = $details[3];
                $row['short'] = $details[1];
            }

            $log[] = $row;
        }

        return $log;
    }

    private function parserTpv()
    {
        $log = [];

        foreach ($this->parserDefault() as $row) {
            $first = strpos($row['message'], '{');
            $last = strrpos($row['message'], '}');

            if (($first === false) || ($last === false)) {
                $log[] = $row;
                continue;
            }

            $last -= $first + 1;

            $row['short'] = substr($row['message'], 0, $first);
            $row['details'] = substr($row['message'], $first, $last);

            $log[] = $row;
        }

        return $log;
    }

    private function getClass($status)
    {
        switch (strtoupper($status)) {
            case 'ERROR':
            case 'EMERGENCY':
            case 'ALERT':
            case 'CRITICAL':
                return 'danger';
            case 'WARNING':
                return 'warning';
            case 'DEBUG':
                return 'primary';
            case 'INFO':
                return 'info';
            default:
                return 'muted';
        }
    }
}
