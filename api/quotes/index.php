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
include_once "../../models/Quote.php";
include_once "../../models/Author.php";
include_once "../../models/Category.php";

// Instantiate the database and connect
$database = new Database();
$db = $database->connect();

// Instantiate the models
$quoteModel = new Quote($db);
$authorModel = new Author($db);
$categoryModel = new Category($db);

$method = $_SERVER["REQUEST_METHOD"];
$data = json_decode(file_get_contents("php://input"));

switch ($method) {
  case "GET":
    // Handle specific ID request
    if (isset($_GET["id"])) {
      $quoteModel->id = $_GET["id"];
      $quoteModel->read_single();

      if ($quoteModel->quote !== null) {
        $quote_arr = [
          "id" => $quoteModel->id,
          "quote" => $quoteModel->quote,
          "author" => $quoteModel->author_name,
          "category" => $quoteModel->category_name,
        ];
        echo json_encode($quote_arr);
      } else {
        echo json_encode(["message" => "No Quotes Found"]);
      }
    } else {
      // Check for the extra credit random parameter
      if (isset($_GET["random"])) {
        $quoteModel->random = $_GET["random"];
      }

      if (isset($_GET["author_id"])) {
        $quoteModel->author_id = $_GET["author_id"];
      }
      if (isset($_GET["category_id"])) {
        $quoteModel->category_id = $_GET["category_id"];
      }

      $result = $quoteModel->read();
      $num = $result->rowCount();

      if ($num > 0) {
        // If random=true was used, only return the object, not an array
        if (isset($_GET["random"]) && $_GET["random"] === "true") {
          $row = $result->fetch(PDO::FETCH_ASSOC);
          echo json_encode([
            "id" => $row["id"],
            "quote" => $row["quote"],
            "author" => $row["author_name"],
            "category" => $row["category_name"],
          ]);
        } else {
          $quote_arr = [];
          while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $quote_item = [
              "id" => $row["id"],
              "quote" => $row["quote"],
              "author" => $row["author_name"],
              "category" => $row["category_name"],
            ];
            array_push($quote_arr, $quote_item);
          }
          echo json_encode($quote_arr);
        }
      } else {
        echo json_encode(["message" => "No Quotes Found"]);
      }
    }
    break;

  case "POST":
    if (
      !empty($data->quote) &&
      !empty($data->author_id) &&
      !empty($data->category_id)
    ) {
      // Verify Author Exists
      $authorModel->id = $data->author_id;
      $authorModel->read_single();
      if ($authorModel->author === null) {
        echo json_encode(["message" => "author_id Not Found"]);
        break;
      }

      // Verify Category Exists
      $categoryModel->id = $data->category_id;
      $categoryModel->read_single();
      if ($categoryModel->category === null) {
        echo json_encode(["message" => "category_id Not Found"]);
        break;
      }

      // Create Quote
      $quoteModel->quote = $data->quote;
      $quoteModel->author_id = $data->author_id;
      $quoteModel->category_id = $data->category_id;

      if ($quoteModel->create()) {
        echo json_encode([
          "id" => $quoteModel->id,
          "quote" => $quoteModel->quote,
          "author_id" => $quoteModel->author_id,
          "category_id" => $quoteModel->category_id,
        ]);
      }
    } else {
      echo json_encode(["message" => "Missing Required Parameters"]);
    }
    break;

  case "PUT":
    if (
      !empty($data->id) &&
      !empty($data->quote) &&
      !empty($data->author_id) &&
      !empty($data->category_id)
    ) {
      // Verify Quote Exists
      $quoteModel->id = $data->id;
      $quoteModel->read_single();
      if ($quoteModel->quote === null) {
        echo json_encode(["message" => "No Quotes Found"]);
        break;
      }

      // Verify Author Exists
      $authorModel->id = $data->author_id;
      $authorModel->read_single();
      if ($authorModel->author === null) {
        echo json_encode(["message" => "author_id Not Found"]);
        break;
      }

      // Verify Category Exists
      $categoryModel->id = $data->category_id;
      $categoryModel->read_single();
      if ($categoryModel->category === null) {
        echo json_encode(["message" => "category_id Not Found"]);
        break;
      }

      // Update Quote
      $quoteModel->quote = $data->quote;
      $quoteModel->author_id = $data->author_id;
      $quoteModel->category_id = $data->category_id;

      if ($quoteModel->update()) {
        echo json_encode([
          "id" => $quoteModel->id,
          "quote" => $quoteModel->quote,
          "author_id" => $quoteModel->author_id,
          "category_id" => $quoteModel->category_id,
        ]);
      }
    } else {
      echo json_encode(["message" => "Missing Required Parameters"]);
    }
    break;

  case "DELETE":
    if (!empty($data->id)) {
      $quoteModel->id = $data->id;

      // Check if it exists before trying to delete
      $quoteModel->read_single();

      if ($quoteModel->quote !== null) {
        if ($quoteModel->delete()) {
          echo json_encode(["id" => $quoteModel->id]);
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
