# FormSynergy.com File Storage

This package enables storing, updating, and retrieving stored data, in a local directory.

## Install using composer
```bash
composer require form-synergy/file-storage
```

## Include the library
```php
require '/vendor/autoload.php';
```

##  Create a new storage
```PHP
$local_directory = 'local-storage';
$resources = new FormSynergy\File_Storage($local_directory, 'fs-demo');
```

## Storing data
```PHP
$data = [
    'key','value'
];
$resources->Store('exampleData')->Data($data);
```

## Updating data
```PHP
$data = [
    'key','new value'
];
$resources->Update('exampleData')->Data($data);
```

## Retrieving data
```PHP
$resources->Find('exampleData', 'key')->In('fs-demo');
```
 
