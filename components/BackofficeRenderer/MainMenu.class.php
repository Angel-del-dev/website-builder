<?php
require_once(sprintf('%s/../components/pdo/Mysql.class.php', $_SERVER['DOCUMENT_ROOT']));

class MainMenu {
    private MysqlPdo $connection;
    public function __construct(MysqlPdo $connection) {
        $this->connection = $connection;
    }

    public function Render():string {
        $html = "";

        $html .= "<div class='w-100 h-100 overflow-y'>";
        $html .= "</div>";

        return $html;
    }
}