<?php

class DBResult {
    protected $stmt;
    public stdClass $params;
    public int $rowCount;
    public function __construct($stmt) {
        $this->stmt = $stmt;
        $this->params = new stdClass();
        $this->rowCount = 0;
    }

    public function Debug():void {
        $this->stmt->debugDumpParams();
    }

    public function Execute():array {
        $result = $this->stmt->execute((array)$this->params);
        $this->rowCount = $this->stmt->rowCount();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function close() {
        $this->stmt = null;
    }
}

/**
 *   @example:
 *   $db = new MysqlPdo();
 *   $sql = $db->newQuery('select * from test where id = :id');
 *   $sql->params->id = 1;
 *   $data = $sql->Execute();
 *   $sql->close();
 * 
 */

class MysqlPdo {
    protected $connection;
    protected DBResult|null $result;
    public function __construct(
        string $host, string $dbname, string $user, string $password, int $port = 3306
    ) {
        $qString = sprintf('mysql:host=%s;port=%s;dbname=%s', $host, $port, $dbname);
        $this->connection = new pdo($qString, $user, $password);
    }

    public function newQuery(string $query):DBResult {
        return new DBResult($this->connection->prepare($query));
    }
}