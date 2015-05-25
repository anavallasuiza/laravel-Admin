<?php
namespace Admin\Http\Processors;

use Exception;
use Response;

trait ExportCsvTrait
{
    protected $excludeCSV = [];
    protected $csv;

    public function exportCsv($null, $rows)
    {
        if (!($data = $this->check(__FUNCTION__))) {
            return $data;
        }

        $rows = $rows->get();
        $table = $rows->first()->getTable();
        $list = $rows->toArray();

        if (count($list) === 0) {
            throw new Exception(__('There aren\'t any contents to export'));
        }

        $this->csv = $this->exportCsvHeaders($table, $list[0]);

        $this->exportCsvRows($table, $list);

        return Response::make($this->csv, 200, array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="list.csv"'
        ));
    }

    private function isExcluded($table, $name)
    {
        return (in_array($table.'|'.$name, $this->excludeCSV, true) || preg_match('/_id$/', $name));
    }

    private function exportCsvHeaders($table, $header)
    {
        $csv = '';

        foreach ($header as $name => $value) {
            if ($this->isExcluded($table, $name)) {
                continue;
            }

            if (!is_array($value)) {
                $csv .= '"'.$name.' ('.$table.')";';
                continue;
            }

            if (array_key_exists(0, $value)) {
                $csv .= $this->exportCsvHeaders($name, $value[0]);
            } else {
                $csv .= $this->exportCsvHeaders($name, $value);
            }
        }

        return $csv;
    }

    private function exportCsvRows($table, $rows)
    {
        foreach ($rows as $row) {
            $this->csv .= "\n";
            $this->exportCsvRow($table, $row);
        }
    }

    private function exportCsvRow($table, $row) {
        foreach ($row as $name => $value) {
            if ($this->isExcluded($table, $name)) {
                continue;
            }

            if (!is_array($value)) {
                $this->csv .= '"'.str_replace('"', '\\"', str_replace(["\n", "\r"], '', $value)).'";';
                continue;
            }

            if (array_key_exists(0, $value)) {
                $this->csv .= $this->exportCsvRow($name, $value[0]);
            } else {
                $this->csv .= $this->exportCsvRow($name, $value);
            }
        }
    }
}
