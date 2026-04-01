<?php
class Database
{
  private $conn;

  /**
   * Connect to the Render PostgreSQL database
   *
   * @return PDO|null
   */
  public function connect()
  {
    $this->conn = null;

    // Fetch credentials from the environment variables
    $host = getenv("DB_HOST");
    $port = getenv("DB_PORT");
    $db_name = getenv("DB_NAME");
    $username = getenv("DB_USER");
    $password = getenv("DB_PASS");

    try {
      $dsn =
        "pgsql:host=" .
        $host .
        ";port=" .
        $port .
        ";dbname=" .
        $db_name .
        ";sslmode=require";
      $this->conn = new PDO($dsn, $username, $password);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      echo "Connection Error: " . $e->getMessage();
    }

    return $this->conn;
  }
}
?>
