<?php

class DB {

    const _PATH = '/.data/';

    private $structure = [];
    private $fileName = '';

    /**
     * Construct
     * 
     * @param string $table 
     * @param array $structure
     */
    public function __construct(string $table, array $structure)
    {
        $this->fileName = getcwd() . self::_PATH . $table;
        $this->structure = $structure;

        if (!file_exists($this->fileName)) {
            $file = fopen($this->fileName, "w");
            fclose($file);
            $this->createStructure();
        }
    }

    /**
     * Create structure for initial file creation
     * 
     * @return void
     * @access private
     */
    private function createStructure(): void
    {
        $data = [];
        $columns = [];

        foreach ($this->structure as $columnName => $defaultValue) {
            $columns[] = $columnName;
        }

        $data[] = $columns;

        $this->save($data);
    }

    /**
     * Save as json
     * 
     * @param array $data
     * @return void
     * @access private
     */
    private function save(array $data): void
    {
        file_put_contents($this->fileName, json_encode($data));
    }

    /**
     * Setup data array based on new values
     * 
     * @param array $data
     * @return array
     * @access private
     */
    private function getDataArrayForSaving(array $data) 
    {
        $structure = $this->structure;
        $createData = [];

        foreach ($structure as $columnName => $defaultValue) {

            if (!isset($data[$columnName])) {
                $createData[] = $defaultValue;
                continue;
            }

            $createData[] = $data[$columnName];
        }

        return $createData;
    }

    /**
     * Return all data including structure
     * 
     * @return array
     * @access public
     */
    public function get(): array
    {
        $data = file_get_contents($this->fileName);

        return json_decode($data, true);
    }

    /**
     * Get only the structure
     * 
     * @return array
     * @access public
     */
    public function getStructure(): array
    {
        return $this->structure;
    }

    /**
     * Return only data
     * 
     * @return array
     * @access public
     */
    public function getAll(): array
    {
        $data = $this->get();
        unset($data[0]);

        if (empty($data)) {
            return [];
        }

        return array_reverse($data);
    }

    /**
     * Create new entry in db
     * 
     * @param array $data
     * @access public
     */
    public function create(array $data): void
    {
        $dbData = $this->get();
        $dbData[] = $this->getDataArrayForSaving($data);

        $this->save($dbData);
    }

    /**
     * Get data by ID
     * 
     * @param int $id
     * @access public
     * @return array
     */
    public function findById(int $id): array
    {
        $data = $this->get();

        $response = [];

        foreach ($data as $key => $val) {
            if ($val == $id) {
                $response = $data[$key];
                break;
            }
        }

        return $response;
    }

    /**
     * Update a specific item by ID
     * 
     * @param int $id
     * @param array $data
     * @return void
     * @access public
     */
    public function update(int $id, array $data): void
    {
        $dbData = $this->get();

        if (!isset($dbData[$id])) {
            return;
        }

        $dbData[$id] = $this->getDataArrayForSaving($data);

        $this->save($dbData);
    }
    
    /**
     * Delete an item by ID
     * 
     * @param int $id
     * @return void
     * @access public
     */
    public function destroy(int $id): void
    {
        $data = $this->get();

        if (!isset($data[$id])) {
            return;
        }

        unset($data[$id]);

        $this->save(array_values($data));
    }
}