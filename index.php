<?php
// link the descroptions file
$fileDescriptions = './descriptions.json';
// Set the base directory securely
$baseDir = './';
// $baseDir = realpath('./');
if (!$baseDir) {
    http_response_code(403); // Forbidden
    throw new Exception('Invalid directory path.');
}

// File types to include (already handled by RegexIterator)
$fileTypes = '/^.+\.(php|cpp|cs|js|json|java|html|css|scss|sass|jsx|tsx|ts|razor|aspx|cshtml|vbhtml|htm|xhtml|xml|vue|jsp|asp|less|md|yml|yaml|config|svg|svgz)$/i';
$metaTypes = '/\.(php|html)$/i'; // handle for just html or php
// Create a RecursiveDirectoryIterator to iterate over the directory
$Directory = new RecursiveDirectoryIterator($baseDir, RecursiveDirectoryIterator::SKIP_DOTS);

// Create a RecursiveIteratorIterator to flatten the recursive structure
$Iterator = new RecursiveIteratorIterator($Directory);

// Apply RegexIterator to filter files by extension
$Regex = new RegexIterator($Iterator, $fileTypes, RecursiveRegexIterator::GET_MATCH);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>School Assignments</title>
    <link rel="stylesheet" href="./src/styles.css">
    <style>
        li {
            list-style-type: none;
            margin-bottom: 0px;
        }
        ul {
            list-style-type: none;
            padding-left: 0;
        }
        .indent-0 { margin-left: 0px; }
        .indent-1 { margin-left: 20px; }
        .indent-2 { margin-left: 40px; }
        .indent-3 { margin-left: 60px; }
    </style>
</head>

<body>
    <h1>List of Files</h1>
    <ul>
    <?php
// Load the JSON file containing descriptions
$descriptions = json_decode(file_get_contents('./descriptions.json'), true);

// Display the files as links using the RegexIterator directly
foreach ($Regex as $file) {
    $filePath = $file[0]; // RegexIterator returns matches in an array
    $fileUrl = htmlspecialchars($filePath, ENT_QUOTES, 'UTF-8');
    $fileName = basename($fileUrl);

    // Use the full relative path to match the description
    // $description = $descriptions->$file[0];
    $description = isset($descriptions[$filePath]) ? $descriptions[$filePath] : "No description available.";
    // echo $descriptions->[$filePath];
    $depth = $Iterator->getDepth();
    $indentClass = 'indent-' . min($depth, 3); // Limit depth to 3
    // Display the file and its description
    echo "<li class='$indentClass'>";
    // echo $depth . ' ';
    echo "<strong>- </strong><a href='$fileUrl'>$filePath</a>";
    echo "<br><strong>-</strong> $description";
    echo "</li><br>";
}
?>
    </ul>
</body>

</html>