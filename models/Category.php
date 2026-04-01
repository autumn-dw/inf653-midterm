<?php
class Category
{
  private $conn;
  private $table_name = "categories";

  public $id;
  public $category;

  /**
   * Constructor requires the database connection
   *
   * @param PDO $db The database connection object
   */
  public function __construct($db)
  {
    $this->conn = $db;
  }

  /**
   * Get all categories
   *
   * @return PDOStatement
   */
  public function read(): PDOStatement
  {
    $query =
      "SELECT id, category FROM " . $this->table_name . " ORDER BY id ASC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
  }

  /**
   * Get a single category by ID
   *
   * @return void
   */
  public function read_single(): void
  {
    $query =
      "SELECT id, category FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
      $this->category = $row["category"];
    } else {
      $this->category = null;
    }
  }

  /**
   * Create a category
   *
   * @return bool
   */
  public function create(): bool
  {
    $query =
      "INSERT INTO " . $this->table_name . " (category) VALUES (:category)";
    $stmt = $this->conn->prepare($query);
    $this->category = htmlspecialchars(strip_tags($this->category));
    $stmt->bindParam(":category", $this->category);

    if ($stmt->execute()) {
      $this->id = $this->conn->lastInsertId();
      return true;
    }
    return false;
  }

  /**
   * Update a category
   *
   * @return bool
   */
  public function update(): bool
  {
    $query =
      "UPDATE " .
      $this->table_name .
      " SET category = :category WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $this->category = htmlspecialchars(strip_tags($this->category));
    $this->id = htmlspecialchars(strip_tags($this->id));
    $stmt->bindParam(":category", $this->category);
    $stmt->bindParam(":id", $this->id);

    if ($stmt->execute()) {
      return true;
    }
    return false;
  }

  /**
   * Delete a category
   *
   * @return bool
   */
  public function delete(): bool
  {
    $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $this->id = htmlspecialchars(strip_tags($this->id));
    $stmt->bindParam(":id", $this->id);

    if ($stmt->execute()) {
      return true;
    }
    return false;
  }
}
?>
