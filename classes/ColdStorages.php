<?php

class ColdStorages {
    private $db;
    private $validator;

    public const TYPE_STATIONARE = 'stationare';
    public const TYPE_PORTABLE = 'portable';

    public function __construct($db, $validator)
    {
        $this->db = $db;
        $this->validator = $validator;
    }

    public function addColdStorageUnit($data)
    {
        $this->validator->validate($data, $this->validator->unitStructure);

        $this->db->insert('cold_storage_units', $data);
        return ['unit_id' => $this->db->lastInsertId()];
    }

    public function getAvaliabilies($data)
    {
        $this->validator->validate($data, $this->validator->getAvaliabilitiesStructure);
        $filters = [];        
        if (isset($data['need_volume'])) $filters[] = 'a.free_volume >= ' . $data['need_volume'].'';
        if (isset($data['start_time'])) $filters[] = 'a.start_time >= "' . $data['start_time'].'"';
        if (isset($data['end_time'])) $filters[] = 'a.end_time <= "' . $data['end_time'].'"';
        
        foreach (['temperature_range', 'has_stability',
                'has_remote_monitoring', 'has_backup_power',
                'has_humidity_control', 'type'] as $column) {
            if (isset($data[$column])) $filters[] = 'u.'.$column.' = "' . $data[$column].'"';          
        }
        
        $where = implode(' AND ', $filters);
        if (strlen($where) > 0) $where = ' WHERE ' . $where;

        $sql = 'SELECT a.free_volume, a.start_time, a.end_time, u.*
                FROM cold_storage_availabilities a 
                LEFT JOIN cold_storage_units u ON u.id = a.unit_id ' . $where;

        return $this->db->select($sql);
    }

    public function createAvaliability($data)
    {
        $this->validator->validate($data, $this->validator->createAvaliabilityStructure);

        $this->db->insert('cold_storage_availabilities', $data);
        return ['avaliability_id' => $this->db->lastInsertId()];
    }
}
