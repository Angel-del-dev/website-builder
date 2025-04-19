<?php
require_once(sprintf('%s/../components/pdo/Mysql.class.php', $_SERVER['DOCUMENT_ROOT']));

class MainMenu {
    private MysqlPdo $connection;
    public function __construct(MysqlPdo $connection) {
        $this->connection = $connection;
    }

    public function Render():string {
        $html = "";

        $sql = $this->connection->newQuery("
            SELECT ME.TRANSLATIONCODE AS NAME, MS.TRANSLATIONCODE AS SECTION, ME.SLUG
            FROM MENUENTRIES ME
            LEFT JOIN MENUSECTIONS MS ON ME.SECTION = MS.TRANSLATIONCODE
            WHERE NOT EXISTS(
                SELECT 1
                FROM MENURESTRICTIONS
                WHERE USER = :USER AND
                    ENTRY = ME.TRANSLATIONCODE
            )
            ORDER BY ME.SECTION ASC
        ");
        $sql->params->USER = AUTH::Get('login', 'NAME');
        $Entries = $sql->Execute();

        $html .= "
            <div 
                class='w-100 flex flex-column align-center justify-start gap-1 flex-grow-1 overflow-y no-user-select'
            >
        ";

        if(count($Entries) > 0) {
            // Entries with null sections will always be on top
            $last_section = '';
            /**
                @var $isSectionOpen [ Required to handle the closing of the section ]
            */
            $isSectionOpen = false;
            $li_class = 'pointer text-ellipsis';
            $li_style = 'padding: 10px 10px;';
        
            foreach($Entries as $Entry) {
                $isSameSection = $Entry['SECTION'] === $last_section;
                $Name = Translation::Get('backoffice', $Entry['NAME']);
                $Section = Translation::Get('backoffice', $Entry['SECTION']);
                if($isSameSection) {
                    $html .= sprintf("
                        <li title='{$Name}' class='{$li_class}' style='{$li_style}'>
                            <a href='{$Entry['SLUG']}'>
                                %s
                            </a>
                        </li>
                    ", $Name);
                    continue;
                }

                if($isSectionOpen) $html .= "
                        </ul>
                    </ul>
                ";
                
                $isSectionOpen = $Entry['SECTION'] !== '';
                $last_section = $Entry['SECTION'];

                $html .= sprintf("
                    <ul 
                        class='w-100 p-1 m-0 overflow-y'
                        style='list-style: none; background-color: var(--white);'
                    >
                        <li
                            class='menu-section pointer radius-1 text-ellipsis'
                            style='padding: 10px 5px;' title='{$Section}'
                        >
                            %s
                        </li>
                        <ul class='d-none m-0 p-0' style='list-style: none; border-top: 1px solid var(--black);'>
                            <li title='{$Name}' class='{$li_class}' style='{$li_style}'>
                                <a href='{$Entry['SLUG']}'>
                                    %s
                                </a>
                            </li>

                ", $Section, $Name);
            }
        }

        $html .= "</div>";

        return $html;
    }
}