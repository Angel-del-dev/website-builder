## Values
### PHP
```php
    // Global variables
    string $cfg
    string $root_path
    // Global constants
    bool IS_DEPLOY
    bool IS_INITIAL
    string REQUESTED_URI
    string|null BACKOFFICE_PREFIX
    string _AND
    string _OR
```

### JS
```js
    // Global constants
    BACKOFFICE_PREFIX
```

## Naming convention
```ini
    # PHP
    regular_functions = *.inc.php
    class = *.class.php
    class_with_inheritance = *.h.php
    # JS
    page_scripts = *.page.js
    library = *.inc.js
    # DB
    initial_schema = *.db.sql
    scripts_executed_by_application = *.script.sql
```