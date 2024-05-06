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
    return slug($str);
}

function slug($str) {
    return str_replace(
        array('á', 'à', 'ả', 'ạ', 'ã', 'ă', 'ắ', 'ằ', 'ẳ', 'ẵ', 'ặ', 'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ',
              'Á', 'À', 'Ả', 'Ạ', 'Ã', 'Ă', 'Ắ', 'Ằ', 'Ẳ', 'Ẵ', 'Ặ', 'Â', 'Ấ', 'Ầ', 'Ẩ', 'Ẫ', 'Ậ',
              'é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ',
              'É', 'È', 'Ẻ', 'Ẽ', 'Ẹ', 'Ê', 'Ế', 'Ề', 'Ể', 'Ễ', 'Ệ',
              'í', 'ì', 'ỉ', 'ĩ', 'ị',
              'Í', 'Ì', 'Ỉ', 'Ĩ', 'Ị',
              'ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ', 'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ',
              'Ó', 'Ò', 'Ỏ', 'Õ', 'Ọ', 'Ô', 'Ố', 'Ồ', 'Ổ', 'Ỗ', 'Ộ', 'Ơ', 'Ớ', 'Ờ', 'Ở', 'Ỡ', 'Ợ',
              'ú', 'ù', 'ủ', 'ũ', 'ụ', 'ư', 'ứ', 'ừ', 'ử', 'ữ', 'ự',
              'Ú', 'Ù', 'Ủ', 'Ũ', 'Ụ', 'Ư', 'Ứ', 'Ừ', 'Ử', 'Ữ', 'Ự',
              'Ý', 'Ỳ', 'Ỷ', 'Ỹ', 'Ỵ',
              'đ', 'Đ'),
        array('a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
              'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A',
              'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
              'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E',
              'i', 'i', 'i', 'i', 'i',
              'I', 'I', 'I', 'I', 'I',
              'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
              'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O',
              'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
              'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U',
              'Y', 'Y', 'Y', 'Y', 'Y',
              'd', 'D'),
        $str
    );
}

$fp = fopen('php://output', 'w');
fwrite($fp, json_encode($response));
fclose($fp);
