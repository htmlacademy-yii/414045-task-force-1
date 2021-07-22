<?php


namespace Components\Imports;


use Components\Exceptions\CsvImportToSqlException;
use Components\GeoData\ConvertStringToGeoPoint;
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
        private string $importFileSrc,
        private string $importFileName,
        private string $newSqlFileSrc,
        private string $newSqlFileName,
        private string $newSqlFilePrefix = ''
    ) {
    }

    /**
     * Импорт из данных файла CSV в файл с запросами SQL
     *
     * @param string $table имя таблицы
     *
     * @throws CsvImportToSqlException
     */
    public function import(string $table): void
    {
        $hasGeoPoint = false;

        if (!file_exists($this->importFileSrc.$this->importFileName)) {
            throw new CsvImportToSqlException(
                'Не удалось открыть файл. Возможно директория или файл не существуют или указаны не верно.'
            );
        }

        $this->importFile = new SplFileObject(
            $this->importFileSrc.$this->importFileName
        );

        if (!file_exists($this->newSqlFileSrc)) {
            throw new CsvImportToSqlException(
                'Директория SQL файла не существует.'
            );
        }

        $this->sqlFile = $this->createSqlFile(
            $this->newSqlFileSrc,
            $this->newSqlFileName,
            $this->newSqlFilePrefix
        );
        $headers = $this->getColumnsTitle();

        if ($this->hasGeoPoint()) {
            $hasGeoPoint = true;
        }

        foreach ($this->getNextLine() as $line) {
            if ($this->columnsValidator($line) && $line) {
                $sql = $this->convertToSql(
                    $table,
                    $headers,
                    $line,
                    $hasGeoPoint
                );
                $writeString = $this->sqlFile->fwrite($sql);
                if (!$writeString) {
                    throw new CsvImportToSqlException(
                        'Ошибка записи в SQL файл'
                    );
                }
            }
        }
    }

    /**
     * Создание SQL файла для импорта из CSV
     *
     * @param string $src  путь к файлу
     * @param string $name имя файла
     * @param string $prefix префикс имени файла
     *
     * @return SplFileObject spl объект файла
     */
    private function createSqlFile(
        string $src,
        string $name,
        string $prefix
    ): SplFileObject {
        return new SplFileObject($src.$prefix.$name, 'c+');
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
     * Определяет есть ли в заголовках импортируемого CSV файла широта и долгота. Широта - "lat", долгота - "long".
     *
     * @return bool true если в файле есть координаты
     */
    private function hasGeoPoint(): bool
    {
        $headers = $this->getColumnsTitle();
        foreach ($headers as $header) {
            if ($header === 'lat' || $header === 'long') {
                return true;
            }
        }

        return false;
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
     * @param array|null $columns массив со столбцами
     *
     * @return bool true если валидация пройдена успешно
     */
    private function columnsValidator(mixed $columns): bool
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
     * @param string $table   имя таблицы
     * @param array  $headers заголовки таблицы
     * @param array  $values  значения столбцов
     *
     * @return string запрос SQL
     */
    private function convertToSql(
        string $table,
        array $headers,
        array $values,
        bool $has_geoPoint
    ): string {
        $geoPointForSql = '';

        if ($has_geoPoint) {
            $indexLat = array_search('lat', $headers, true);
            $indexLong = array_search('long', $headers, true);
            $convertToGeoPoint = new ConvertStringToGeoPoint(
                $values[$indexLat],
                $values[$indexLong]
            );
            $geoPointForSql = ', '.$convertToGeoPoint->getGeoStringForSql();
            unset($headers[$indexLat], $headers[$indexLong], $values[$indexLat], $values[$indexLong]);
            $headers[] = 'location';
        }

        $valuesForSql = [];

        foreach ($values as $value) {
            $valuesForSql[] = "'".$value."'";
        }

        $values = implode(',', $valuesForSql);
        $headers = implode(', ', $headers);

        return 'INSERT INTO '.$table.' ('.$headers.') VALUE ('.$values
            .$geoPointForSql.');'
            .PHP_EOL;
    }
}