<?php

function interpolateQuery(string $query, array $params)
{
    foreach ($params as $key => $value) {
        $value = is_string($value) ? "'" . $value . "'" : $value;
        $query = is_numeric($key)
            ? preg_replace('/\?/', $value, $query, 1)
            : str_replace(":$key", $value, $query);
    }
    echo $query;
    return $query;
}

//save request to file, for debug
function saveToLog(): void
{
    $dirName = 'data/' . date("Y-m");
    if (!is_dir($dirName)) {
        if (!mkdir($dirName, 0755, true)) {
            echo '{"success":false,"error":"Error:failed to create directory."}';
            die;
        }
    }

    $fileLog = $dirName . '/data_uv__'. date("Y-m-d_H-i-s"). '.log';
    $fh = fopen($fileLog, 'w');
    foreach($_SERVER as $h => $v) {
        fwrite($fh, "$h = $v\n");
    }
    fwrite($fh, "\r\n------------------------------------------\r\n");
    fwrite($fh, "\r\n");
    fwrite($fh, file_get_contents('php://input'));
    fclose($fh);
}

//debug only

function handleMethod($method, $data, $db, $validator)
{
    $classes = [
        'addColdStorageUnit' => ColdStorages::class,
        'getAvaliabilies' => ColdStorages::class,
        'createAvaliability' => ColdStorages::class,
    ];

    $class = $classes[$method] ?? ColdStorageRequests::class;

    $instance = new $class($db, $validator);

    if (!method_exists($instance, $method)) {
        throw new Exception("$method - Unknown method");
    }

    return $instance->$method($data);
}
