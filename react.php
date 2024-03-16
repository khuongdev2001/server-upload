<?php
require "vendor/autoload.php";
$response = [
    "status" => true,
    "data" => [],
    "message" => ""
];
header("Content-Type:application/json");
switch ($_SERVER["REQUEST_URI"]) {
    case "/api/v1/read-excel":
        $inputFileName = $_FILES["file"]["tmp_name"];
        // /** Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $worksheet = $spreadsheet->getActiveSheet();
        $result = [];
        $time = time();
        foreach ($worksheet->toArray() as $index => $row) {
            if ($index == 0) {
                continue;
            }
            if (!isset($row[0], $row[2])) {
                continue;
            }
            $result[] = [
                "id" => $time + $index,
                "fullname" => $row[0],
                "phone" => $row[2]
            ];
        }
        $response["data"] = $result;
        break;
    default:
        header("HTTP/1.1 404 Not Found");
}
$fp = fopen('php://output', 'w');
fwrite($fp, json_encode($response));
fclose($fp);
