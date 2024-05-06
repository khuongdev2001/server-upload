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
                "fullname" => handleText($row[0]),
                "phone" => handleText($row[2])
            ];
        }
        $response["data"] = $result;
        break;
    default:
        
}

function handleText($str){
    if(empty($_POST["is_slug"])){
        return $str;
    }
    return removeVietnameseAccents($str);
}

function removeVietnameseAccents($str) {
    $str = preg_replace('/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/g', 'a', $str);
    $str = preg_replace('/Á|À|Ả|Ạ|Ã|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ/g', 'A', $str);
    $str = preg_replace('/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/g', 'e', $str);
    $str = preg_replace('/É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ/g', 'E', $str);
    $str = preg_replace('/i|í|ì|ỉ|ĩ|ị/g', 'i', $str);
    $str = preg_replace('/I|Í|Ì|Ỉ|Ĩ|Ị/g', 'I', $str);
    $str = preg_replace('/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/g', 'o', $str);
    $str = preg_replace('/Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ/g', 'O', $str);
    $str = preg_replace('/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/g', 'u', $str);
    $str = preg_replace('/Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự/g', 'U', $str);
    $str = preg_replace('/Ý|Ỳ|Ỷ|Ỹ|Ỵ/g', 'Y', $str);
    $str = preg_replace('/đ/g', 'd', $str);
    $str = preg_replace('/Đ/g', 'D', $str);
    return $str;
}

$fp = fopen('php://output', 'w');
fwrite($fp, json_encode($response));
fclose($fp);
