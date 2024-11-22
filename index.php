<?php

ini_set("display_errors",1);
error_reporting(E_ALL);

require_once 'autoload.php';
require_once 'common.php';

try {
    if (!isset($_GET['method'])) {
        throw new Exception('No method provided');
    }

    $method = $_GET['method'];

    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body, true);

    if ($data === null) {
        throw new Exception('Invalid JSON format');
    }

    $db = new Db('local');
    $validator = new JsonStructureValidator();

    $return = handleMethod($method, $data, $db, $validator);

    echo json_encode($return);
} catch (Exception $e) {
    echo json_encode(['errors' => $e->getMessage()]);
}

