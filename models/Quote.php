<?php
class Quote
{
  private $conn;
  private $table_name = "quotes";

  // Object properties
  public $id;
  public $quote;
  public $author_id;
  public $category_id;

  // Properties to hold the joined string names for GET requests
  public $author_name;
  public $category_name;

  /**
   * Constructor requires the database connection
   *
   * @param PDO $db The database connection object
   */
  public function __construct($db)
  {
    $this->conn = $db;
  }

  // Extra credit
  public $random;

  /**
   * Get quotes (All, filtered, or random)
   *
   * @return PDOStatement
   */
  public function read(): PDOStatement
  {
    $query =
      "SELECT c.category as category_name, a.author as author_name, q.id, q.quote, q.author_id, q.category_id
                    FROM " .
      $this->table_name .
      " q
                    LEFT JOIN categories c ON q.category_id = c.id
                    LEFT JOIN authors a ON q.author_id = a.id";

    $conditions = [];
    if ($this->author_id) {
      $conditions[] = "q.author_id = :author_id";
    }
    if ($this->category_id) {
      $conditions[] = "q.category_id = :category_id";
    }

    if (count($conditions) > 0) {
      $query .= " WHERE " . implode(" AND ", $conditions);
    }

    // Extra Credit: If random is true, order by random and limit to 1
    if ($this->random === "true") {
      $query .= " ORDER BY RANDOM() LIMIT 1";
    } else {
      $query .= " ORDER BY q.id ASC";
    }

    $stmt = $this->conn->prepare($query);

    if ($this->author_id) {
      $stmt->bindParam(":author_id", $this->author_id);
    }
    if ($this->category_id) {
      $stmt->bindParam(":category_id", $this->category_id);
    }

    $stmt->execute();
    return $stmt;
  }

  /**
   * Get a single quote by ID
   *
   * @return void
   */
  public function read_single(): void
  {
    $query =
      "SELECT c.category as category_name, a.author as author_name, q.id, q.quote, q.author_id, q.category_id
                  FROM " .
      $this->table_name .
      " q
                  LEFT JOIN categories c ON q.category_id = c.id
                  LEFT JOIN authors a ON q.author_id = a.id
                  WHERE q.id = ? LIMIT 1";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
      $this->quote = $row["quote"];
      $this->author_id = $row["author_id"];
      $this->category_id = $row["category_id"];
      $this->author_name = $row["author_name"];
      $this->category_name = $row["category_name"];
    } else {
      $this->quote = null;
    }
  }

  /**
   * Create a quote
   *
   * @return bool
   */
  public function create(): bool
  {
    $query =
      "INSERT INTO " .
      $this->table_name .
      " (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id)";
    $stmt = $this->conn->prepare($query);

    // Sanitize data
    $this->quote = htmlspecialchars(strip_tags($this->quote));
    $this->author_id = htmlspecialchars(strip_tags($this->author_id));
    $this->category_id = htmlspecialchars(strip_tags($this->category_id));

    // Bind data
    $stmt->bindParam(":quote", $this->quote);
    $stmt->bindParam(":author_id", $this->author_id);
    $stmt->bindParam(":category_id", $this->category_id);

    if ($stmt->execute()) {
      $this->id = $this->conn->lastInsertId();
      return true;
    }
    return false;
  }

  /**
   * Update a quote
   *
   * @return bool
   */
  public function update(): bool
  {
    $query =
      "UPDATE " .
      $this->table_name .
      " SET quote = :quote, author_id = :author_id, category_id = :category_id WHERE id = :id";
    $stmt = $this->conn->prepare($query);

    // Sanitize data
    $this->quote = htmlspecialchars(strip_tags($this->quote));
    $this->author_id = htmlspecialchars(strip_tags($this->author_id));
    $this->category_id = htmlspecialchars(strip_tags($this->category_id));
    $this->id = htmlspecialchars(strip_tags($this->id));

    // Bind data
    $stmt->bindParam(":quote", $this->quote);
    $stmt->bindParam(":author_id", $this->author_id);
    $stmt->bindParam(":category_id", $this->category_id);
    $stmt->bindParam(":id", $this->id);

    if ($stmt->execute()) {
      return true;
    }
    return false;
  }

  /**
   * Delete a quote
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
