<?php


namespace Components\Imports;


use Components\Exceptions\CsvImportToSqlException;
use SplFileObject;

/**
 * Class CsvImportToSql
 *
 * @package Components\Imports
 */
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
     * Импорт из данных файла CSV в файл с запросами SQL
     *
     * @param string $db имя базы данных
     * @param string $table имя таблицы
     *
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

    /**
     * Создание SQL файла для импорта из CSV
     *
     * @param string $src путь к файлу
     * @param string $name имя файла
     *
     * @return object spl объект файла
     */
    private function createSqlFile(string $src, string $name): object
    {
        return $this->sqlFile = new SplFileObject($src.$name, 'c+');
    }

    /**
     * Получить заголовки файла CSV
     *
     * @return array|null заголовки файла CSV
     */
    private function getColumnsTitle(): ?array
    {
        $this->importFile->rewind();
        if (!$this->importFile->eof()) {
            return $this->importFile->fgetcsv();
        }

        return null;
    }

    /**
     * Генератор получения новой строки из файла CSV
     *
     * @return iterable|null
     */
    private function getNextLine(): ?iterable
    {
        while (!$this->importFile->eof()) {
            yield $this->importFile->fgetcsv();
        }

        return null;
    }

    /**
     * Валидация столбцов. Доложен быть хотя-бы один столбец, тип данных в столбце должен быть string
     *
     * @param array $columns массив со столбцами
     *
     * @return bool true если валидация пройдена успешно
     */
    private function columnsValidator(array $columns): bool
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

    /**
     * Конвертация данных в запрос SQL
     *
     * @param string $table имя таблицы
     * @param array $headers заголовки таблицы
     * @param array $values значения столбцов
     *
     * @return string запрос SQL
     */
    private function convertToSql(string $table,array $headers,array $values): string
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