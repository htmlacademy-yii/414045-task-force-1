<?php


namespace Components\Imports;


use Components\Exceptions\CsvImportToSqlException;
use SplFileObject;

class CsvImportToSql
{
    private object $importFile;
    private object $sqlFile;

    public function __construct(
        private string $importFileName,
        private string $importFileSrc,
        private string $newSqlFileName,
        private string $newSqlFileSrc
    ) {
    }

    /**
     * @throws CsvImportToSqlException
     */
    public function import(string $db, string $table): void
    {
        if (!file_exists($this->importFileSrc.$this->importFileName)) {
            throw new CsvImportToSqlException('Не удалось открыть файл. Возможно директория или файл не существуют или указаны не верно.');
        }

        $this->importFile = new SplFileObject(
            $this->importFileSrc.$this->importFileName
        );

        if (!file_exists($this->newSqlFileSrc)) {
            throw new CsvImportToSqlException('Директория SQL файла не существует.');
        }

        $this->createSqlFile($this->newSqlFileSrc, $this->newSqlFileName);
        $headers = $this->getColumnsTitle();

        $writeFirstString = $this->sqlFile->fwrite('USE '.$db.";".PHP_EOL);
        if (!$writeFirstString) {
            throw new CsvImportToSqlException('Ошибка записи в SQL файл');
        }
        foreach ($this->getNextLine() as $line) {
            if ($this->columnsValidator($line)) {
                $sql = $this->convertToSql($table, $headers, $line);
                $writeString = $this->sqlFile->fwrite($sql);
                if (!$writeString) {
                    throw new CsvImportToSqlException('Ошибка записи в SQL файл');
                }
            }
        }
    }

    public function createSqlFile(string $src, string $name): object
    {
        return $this->sqlFile = new SplFileObject($src.$name, 'c+');
    }

    public function getColumnsTitle(): ?array
    {
        $this->importFile->rewind();
        if (!$this->importFile->eof()) {
            return $this->importFile->fgetcsv();
        }

        return null;
    }

    private function getNextLine(): ?iterable
    {
        while (!$this->importFile->eof()) {
            yield $this->importFile->fgetcsv();
        }

        return null;
    }

    private function columnsValidator($columns): bool
    {
        if (!count($columns)) {
            return false;
        }
        foreach ($columns as $column) {
            if (!is_string($column)) {
                return false;
            }
        }

        return true;
    }

    private function convertToSql($table, $headers, $values): string
    {
        $valuesForSql = [];

        foreach ($values as $value) {
            $valuesForSql[] = "'".$value."'";
        }

        $values = implode(',', $valuesForSql);
        $headers = implode(', ', $headers);

        return 'INSERT INTO '.$table.' ('.$headers.') VALUE ('.$values.');'
            .PHP_EOL;
    }
}