#!/usr/bin/php
<html>
  <head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <style>
      td{
        padding:2px;
      }
      tr:nth-child(even) {
        background: #CCC
      }
      tr:nth-child(odd) {
        background: #FFF
      }
    </style>
  </head>
  <body>
  <div class="container-fluid">
<?php
    if(!isset($_POST)) die("Bad behavior");
    
  
    $q = str_replace("\\", "", $_POST['query']); 

    $ql = strtolower($q); 
    if (strpos($ql,'drop') !== false) die("Bad behavior");
    if (strpos($ql,'delete') !== false) die("Bad behavior");   
    if (strpos($ql,'truncate') !== false) die("Bad behavior");   
    
    
    // SQL access
    define ( 'DB_NAME', 'theinis');
    
    define ( 'DB_USER', 'lab');
    
    define ( 'DB_PASSWORD', 'lab');
    
    define ( 'DB_HOST', 'db.doc.ic.ac.uk');
    
    define ( 'DB_CHARSET', 'utf8');
    
    $DB_ID = pg_connect("host=db.doc.ic.ac.uk port=5432 dbname=theinis user=lab password=lab")
    or die("Database '".DB_NAME."' not accessible.<br>\n");
    
    
    //pg_select_db(DB_NAME, $DB_ID)
    
    //or die("Enable to select ".DB_NAME." database<br>\n");
    //ear
      
    
    
    
    $results = pg_query($DB_ID, $q)
    
    or die("<br>$q<br>".pg_last_error($DB_ID));
    
    if($results == false) die("Some problem occured :<br>$q<br>");
    
    
    
 //   echo "Request performed<br>\n";

    
    
    
    echo '<table><tr>';
    for($i=0; $i<pg_num_fields($results); $i++){
        $fieldName = pg_field_name($results, $i);
        echo '<td><b>' . $fieldName . '</b></td>';
    }
    
    echo '</tr>';
    $i = 0;
    while ($row = pg_fetch_row($results)) {
        echo '<tr>';
        $count = count($row);
        $y = 0;
        while ($y < $count) {
            $c_row = current($row);
            echo '<td>' . $c_row . '</td>';
            next($row);
            $y = $y + 1;
        }
        
        echo '</tr>';
        $i = $i + 1;
    }
    
    pg_free_result($results);
    
    echo '</table>';
?>
</div>
  </body>
</html>
