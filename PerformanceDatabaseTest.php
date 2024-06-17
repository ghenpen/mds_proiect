<?php
// Design pattern: Singleton pattern
use PHPUnit\Framework\TestCase;

class PerformanceDatabaseTest extends TestCase
{
    private $conn;
    private static $instance = null;

    // Private constructor to prevent direct instantiation
    private function __construct()
    {
        $this->conn = new mysqli("localhost", "root", "", "proiect_mds");

        if ($this->conn->connect_error) {
            die("Eroare la conectare: " . $this->conn->connect_error);
        }
    }

    // Singleton method to get the instance
    public static function getInstance(): PerformanceDatabaseTest
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function setUp(): void
    {
        // Ensure connection is available for tests
        if (!$this->conn) {
            $this->conn = new mysqli("localhost", "root", "", "proiect_mds");

            if ($this->conn->connect_error) {
                die("Eroare la conectare: " . $this->conn->connect_error);
            }
        }
    }

    protected function tearDown(): void
    {
        // Close the database connection
        if ($this->conn) {
            $this->conn->close();
            $this->conn = null;
        }
    }

    public function testDatabasePerformance()
    {
        // Measure the time for a simple query
        $startTime = microtime(true);
        $sql = "SELECT * FROM user";
        $result = $this->conn->query($sql);
        $endTime = microtime(true);
        $queryTime = $endTime - $startTime;

        // Assert for execution time
        $this->assertLessThan(0.1, $queryTime); // Time should be less than 0.1 seconds

        // Assert for query results
        $this->assertTrue($result->num_rows > 0); // Ensure we have at least one row in the results

        // Depending on your application specifics, you can add more relevant assertions
    }
}

?>
