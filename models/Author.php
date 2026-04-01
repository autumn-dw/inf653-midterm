<?php
class Author
{
  // Database connection and table name
  private $conn;
  private $table_name = "authors";

  // Object properties
  public $id;
  public $author;

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
   * Get all authors
   *
   * @return PDOStatement
   */
  public function read(): PDOStatement
  {
    $query = "SELECT id, author FROM " . $this->table_name . " ORDER BY id ASC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
  }

  /**
   * Get a single author by ID
   *
   * @return void
   */
  public function read_single(): void
  {
    $query =
      "SELECT id, author FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
      $this->author = $row["author"];
    } else {
      $this->author = null;
    }
  }

  /**
   * Create an author
   *
   * @return bool
   */
  public function create(): bool
  {
    $query = "INSERT INTO " . $this->table_name . " (author) VALUES (:author)";
    $stmt = $this->conn->prepare($query);

    // Sanitize data
    $this->author = htmlspecialchars(strip_tags($this->author));

    // Bind data
    $stmt->bindParam(":author", $this->author);

    if ($stmt->execute()) {
      $this->id = $this->conn->lastInsertId();
      return true;
    }
    return false;
  }

  /**
   * Update an author
   *
   * @return bool
   */
  public function update(): bool
  {
    $query =
      "UPDATE " . $this->table_name . " SET author = :author WHERE id = :id";
    $stmt = $this->conn->prepare($query);

    // Sanitize data
    $this->author = htmlspecialchars(strip_tags($this->author));
    $this->id = htmlspecialchars(strip_tags($this->id));

    // Bind data
    $stmt->bindParam(":author", $this->author);
    $stmt->bindParam(":id", $this->id);

    if ($stmt->execute()) {
      return true;
    }
    return false;
  }

  /**
   * Delete an author
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
