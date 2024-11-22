<?php

class ColdStorageRequests
{
    private $db;
    private $validator;

    public const PRIORITY_LOW = 'low';
    public const PRIORITY_MIDDLE = 'middle';
    public const PRIORITY_HIGHT = 'hight';
    public const PRIORITY_HIGHTEST = 'highest';
        
    public const STATUS_APPROVED = 'approved';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_ACTIVE = 'active';


    public function __construct($db, $validator)
    {
        $this->db = $db;
        $this->validator = $validator;
    }

    public function createRequest($data)
    {
        $stmt = $this->db->insert('cold_storage_requests', $data);
        $requestId = $this->db->lastInsertId();
        $this->notifyOwner($requestId, $data);
    }

    private function notifyOwner(int $requestId, array $data)
    {
    }

    public function approveRequest(int $coldStorageUnitId, array $data)
    {
        $this->db->insert('cold_storage_approvals', $data);
        $requestId = $data['request_id'];

        $this->notifyRequester($requestId, $coldStorageUnitId);
    }

    public function selectApprovedOwner(int $requestId, int $coldStorageUnitId)
    {
        $sql = "UPDATE cold_storage_requests 
                SET approved_cold_storage_unit_id = :cold_storage_unit_id, status = 'approved' 
                WHERE id = :request_id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'cold_storage_unit_id' => $coldStorageUnitId,
            'request_id' => $requestId,
        ]);
    }

    private function notifyRequester($requestId, $coldStorageUnitId)
    {
    }

    public function bookAvaliability($data) {
        $this->db->insert('cold_storage_availabilities', $data);
    }

    public function showRequestByFilter($data) 
    {
        $sql = "SELECT * 
            FROM cold_storage_units u
            LEFT JOIN cold_storage_availabilities a ON u.id = a.unit_id
            LEFT JOIN cold_storage_requests r ON r.avaliability_id = a.id
            WHERE u.owner_id = :owner_id 
            AND r.status = :status
            ORDER BY r.updated DESC";

        return $this->db->select($sql, $data);
    }

    public function setRequestStatus($data)
    {
        $sql = 'UPDATE cold_storage_requests 
                SET status = :status
                WHERE id = :request_id';
        $this->db->execute($sql, $data);
    }

    public function saveMessage($data)
    {
        $sql = "UPDATE cold_storage_requests 
            SET comments = 
            CASE 
                WHEN comments IS NULL THEN JSON_ARRAY(JSON_OBJECT('author', :author, 'message', :message, 'timestamp', :timestamp))
                ELSE JSON_ARRAY_APPEND(comments, '$', JSON_OBJECT('author', :author, 'message', :message, 'timestamp', :timestamp))
                END
            WHERE id = :id";

        $params = [
            'author' => $data['author'],
            'message' => $data['message'],
            'timestamp' => date('Y-m-d H:i:s'),
            'id' => $data['request_id']
        ];

        $this->db->execute($sql, $params);
    }

    public function getСonversation($data)
    {
        $sql = 'SELECT id, comments FROM `cold_storage_requests` WHERE id = :request_id';
        $conv = $this->db->select($sql, ['request_id' => $data['request_id']], 'one');

        return [
            'request_id' => $conv['id'],
            'comments' => json_decode($conv['comments'], true),
        ];
    }
}
