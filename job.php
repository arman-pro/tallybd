<?php

// $file = __DIR__.'/public/file.txt';
//         // Open the file to get existing content
//         $current = file_get_contents($file);
//         // Append a new person to the file
//         $current .= "John Smith\n - ". date('h:i');
//         // Write the contents back to the file
//         file_put_contents($file, $current);
        
    require __DIR__.'/vendor/autoload.php';
    
    $app = require_once __DIR__.'/bootstrap/app.php';
    
    use App\Http\Controllers\HomeController;
    $home = new HomeController();
    $home->drive_upload();
    
return;
