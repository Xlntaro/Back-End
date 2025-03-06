<?php
function sortWordsAlphabetically($inputFile, $outputFile) {
    // Read words from input file
    $content = file_get_contents($inputFile);
    $words = array_filter(explode(' ', $content));
    
    // Sort words alphabetically
    sort($words);
    
    // Write sorted words to output file
    file_put_contents($outputFile, implode(' ', $words));
    
    return $words;
}

// Process form submission
$sortedWords = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inputFile = $_POST['input_file'] ?? '';
    $outputFile = $_POST['output_file'] ?? 'sorted_words.txt';
    
    if (!empty($inputFile) && file_exists($inputFile)) {
        $sortedWords = sortWordsAlphabetically($inputFile, $outputFile);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Word Sorting</title>
</head>
<body>
    <h2>Alphabetical Word Sorting</h2>
    <form method="POST">
        <label>Input File: <input type="text" name="input_file" required></label><br>
        <label>Output File (optional): <input type="text" name="output_file" value="sorted_words.txt"></label><br>
        <input type="submit" value="Sort Words">
    </form>

    <?php if (!empty($sortedWords)): ?>
        <h3>Sorted Words:</h3>
        <p><?= implode(', ', $sortedWords) ?></p>
    <?php endif; ?>
</body>
</html>