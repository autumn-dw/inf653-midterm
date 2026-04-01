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
include_once "../../models/Author.php";

// Instantiate the database and connect
$database = new Database();
$db = $database->connect();

// Instantiate the Author model
$authorModel = new Author($db);

// Get the HTTP method
$method = $_SERVER["REQUEST_METHOD"];

// Get raw posted JSON data
$data = json_decode(file_get_contents("php://input"));

switch ($method) {
  case "GET":
    if (isset($_GET["id"])) {
      $authorModel->id = $_GET["id"];
      $authorModel->read_single();

      if ($authorModel->author !== null) {
        $author_arr = [
          "id" => $authorModel->id,
          "author" => $authorModel->author,
        ];
        echo json_encode($author_arr);
      } else {
        echo json_encode(["message" => "author_id Not Found"]);
      }
    } else {
      $result = $authorModel->read();
      $num = $result->rowCount();

      if ($num > 0) {
        $author_arr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
          $author_item = [
            "id" => $row["id"],
            "author" => $row["author"],
          ];
          array_push($author_arr, $author_item);
        }
        echo json_encode($author_arr);
      } else {
        echo json_encode(["message" => "author_id Not Found"]);
      }
    }
    break;

  case "POST":
    if (!empty($data->author)) {
      $authorModel->author = $data->author;

      if ($authorModel->create()) {
        echo json_encode([
          "id" => $authorModel->id,
          "author" => $authorModel->author,
        ]);
      }
    } else {
      echo json_encode(["message" => "Missing Required Parameters"]);
    }
    break;

  case "PUT":
    if (!empty($data->id) && !empty($data->author)) {
      $authorModel->id = $data->id;

      // Check if the author exists first
      $authorModel->read_single();

      if ($authorModel->author !== null) {
        $authorModel->author = $data->author;

        if ($authorModel->update()) {
          echo json_encode([
            "id" => $authorModel->id,
            "author" => $authorModel->author,
          ]);
        }
      } else {
        echo json_encode(["message" => "author_id Not Found"]);
      }
    } else {
      echo json_encode(["message" => "Missing Required Parameters"]);
    }
    break;

  case "DELETE":
    if (!empty($data->id)) {
      $authorModel->id = $data->id;

      // Check if it exists before trying to delete
      $authorModel->read_single();

      if ($authorModel->author !== null) {
        if ($authorModel->delete()) {
          echo json_encode(["id" => $authorModel->id]);
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
