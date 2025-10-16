<?php
  $query = $_GET['query']; // Ottieni la query di ricerca dalla barra di ricerca
  $dir = 'SITO_WEB_PROVA'; // Specifica la cartella in cui desideri effettuare la ricerca
  
  // Funzione ricorsiva per cercare nei file all'interno della cartella
  function searchFiles($dir, $query) {
    $results = array();
  
    // Apri la cartella e ottieni un elenco dei file al suo interno
    if ($handle = opendir($dir)) {
      while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
          $filepath = $dir . '/' . $file;
          
          // Se il file è una cartella, effettua una ricerca all'interno della cartella
          if (is_dir($filepath)) {
            $results = array_merge($results, searchFiles($filepath, $query));
          } else {
            // Se il file è un file di testo, cerca all'interno del suo contenuto
            if (strpos(mime_content_type($filepath), 'text') !== false) {
              $content = file_get_contents($filepath);
              if (strpos($content, $query) !== false) {
                $results[] = $filepath;
              }
            }
          }
        }
      }
      closedir($handle);
    }
  
    return $results;
  }
  
  // Esegui la ricerca
  $results = searchFiles($dir, $query);
  
  // Mostra i risultati
  if (count($results) > 0) {
    echo "<h2>Risultati della ricerca:</h2>";
    echo "<ul>";
    foreach ($results as $result) {
      echo "<li><a href=\"$result\">$result</a></li>";
    }
    echo "</ul>";
  } else {
    echo "<p>Nessun risultato trovato.</p>";
  }
?>
