<?php

class JsonStructureValidator
{
    public array $unitStructure = [
        'owner_id' => 'integer',
        'temperature_range' => 'integer',
        'total_capacity' => 'integer',
        'has_stability' => 'integer',
        'has_remote_monitoring' => 'integer',
        'has_backup_power' => 'integer',
        'has_humidity_control' => 'integer',
        'model' => 'string',
        'type' => 'string',
        'production_year' => 'integer',
        'usage_start_year' => 'integer',
    ];

    public array $createAvaliabilityStructure = [
        'unit_id' => 'integer',
        'start_time' => 'string',
        'end_time' => 'string',
        'free_volume' => 'integer',
    ];

    public array $getAvaliabilitiesStructure = [
        'need_volume' => 'integer',
    ];
        
    public function validate($data, $expectedStructure)
    {
        $errors = []; 

        foreach ($expectedStructure as $key => $type) {
            if (!array_key_exists($key, $data)) {
                $errors[] = "Missing key: $key";
                continue;
            }
    
            if (is_array($type)) {
                if (isset($data[$key]) && is_array($data[$key])) {
                    foreach ($data[$key] as $index => $item) {
                        if (!is_array($item)) {
                            $errors[] = "Element at index $index in $key should be an array.";
                            continue;
                        }
                        $subErrors = $this->validate($item, $type);
                        if ($subErrors !== true) {
                            $errors = array_merge($errors, $subErrors);
                        }
                    }
                } else {
                    $errors[] = "Key $key should be an array.";
                }
            } else {
                if ($type === 'number') {
                    if (!is_numeric($data[$key])) {
                        $errors[] = "Key $key should be a number. Found: " . gettype($data[$key]);
                    }
                } elseif (gettype($data[$key]) !== $type) {
                    $errors[] = "Key $key should be of type $type. Found: " . gettype($data[$key]);
                }
            }
        }
    
        if (!empty($errors)) {
            $errMsg = 'JSON is not valid. ' . implode(', ', $errors);
            echo json_encode(['errors' => $errMsg]);
            die();
        }

        // Повертаємо true, якщо помилок немає, інакше масив помилок
        return true;
    }
}
