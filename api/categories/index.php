<?php
// REST API Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header(
  "Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With",
);

// Include core files
include_once "../../config/Database.php";
include_once "../../models/Category.php";

$database = new Database();
$db = $database->connect();
$category = new Category($db);

$method = $_SERVER["REQUEST_METHOD"];
$data = json_decode(file_get_contents("php://input"));

switch ($method) {
  case "GET":
    if (isset($_GET["id"])) {
      $category->id = $_GET["id"];
      $category->read_single();

      if ($category->category !== null) {
        $cat_arr = [
          "id" => $category->id,
          "category" => $category->category,
        ];
        echo json_encode($cat_arr);
      } else {
        echo json_encode(["message" => "category_id Not Found"]);
      }
    } else {
      $result = $category->read();
      $num = $result->rowCount();

      if ($num > 0) {
        $cat_arr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
          $cat_item = [
            "id" => $row["id"],
            "category" => $row["category"],
          ];
          array_push($cat_arr, $cat_item);
        }
        echo json_encode($cat_arr);
      } else {
        echo json_encode(["message" => "category_id Not Found"]);
      }
    }
    break;

  case "POST":
    if (!empty($data->category)) {
      $category->category = $data->category;
      if ($category->create()) {
        echo json_encode([
          "id" => $category->id,
          "category" => $category->category,
        ]);
      }
    } else {
      echo json_encode(["message" => "Missing Required Parameters"]);
    }
    break;

  case "PUT":
    if (!empty($data->id) && !empty($data->category)) {
      $category->id = $data->id;
      $category->read_single();

      if ($category->category !== null) {
        $category->category = $data->category;
        if ($category->update()) {
          echo json_encode([
            "id" => $category->id,
            "category" => $category->category,
          ]);
        }
      } else {
        echo json_encode(["message" => "category_id Not Found"]);
      }
    } else {
      echo json_encode(["message" => "Missing Required Parameters"]);
    }
    break;

  case "DELETE":
    if (!empty($data->id)) {
      $category->id = $data->id;
      $category->read_single();

      if ($category->category !== null) {
        if ($category->delete()) {
          echo json_encode(["id" => $category->id]);
        }
      } else {
        echo json_encode(["message" => "No Quotes Found"]);
      }
    } else {
      echo json_encode(["message" => "Missing Required Parameters"]);
    }
    break;
}
?>
