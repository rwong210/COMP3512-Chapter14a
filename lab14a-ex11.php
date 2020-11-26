<?php
/* add your PHP code here */
require_once('config.inc.php');

try {
   $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   $galleries = getGalleries($pdo);
   if (isset($_GET['gallery'])) {
      $paintings = getPaintings($pdo, $_GET['gallery']);
   }
   $pdo = null;
} catch (PDOException $e) {
   die($e->getMessage());
}

function getGalleries($pdo){
   $sql = "SELECT GalleryID, GalleryName FROM Galleries
               ORDER BY GalleryName";
               $result = $pdo->query($sql);
               return $result->fetchAll(PDO::FETCH_ASSOC);
}

function getPaintings($pdo, $id) {
   $sql = "SELECT PaintingID, Title, YearOfWork, ImageFileName FROM Paintings WHERE GalleryID=?";
   $statement = $pdo->prepare($sql);
   $statement->bindValue(1,$id);
   $statement->execute();
   return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function outputGalleries($galleries)
{
   foreach ($galleries as $row) {
      echo "<li >";
      echo "<a href='" . $_SERVER['PHP_SELF'] . "?gallery=" . $row['GalleryID'] . "'>";
      echo $row['GalleryName'];
      echo "</a></li>";
   }
}
function outputPaintings($paintings)
{
   echo "<h3 class='is-size-5 has-text-weight-medium'>Paintings</h3>";
   echo "<table class='table'>";
   foreach ($paintings as $row) {
      echo "<tr>";
      $filename = "images/art/works/square-medium/" . $row['ImageFileName'] . '.jpg';
      echo "<td>" . "<img src='" . $filename . "'></td>";
      echo "<td>" . $row['Title'] . "</td>";
      echo "<td>" . $row['YearOfWork'] . "</td>";
      echo "</tr>";
   }
   echo "</table>";
}


?>
<!DOCTYPE html>
<html lang=en>

<head>
   <title>Lab 14a</title>
   <meta charset=utf-8>
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
   <link href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css" rel="stylesheet">
</head>

<body>
   <header class="p-3">
      <h2 class="is-size-2 has-background-light">Multiple Queries</h2>
   </header>
   <main class="p-3">
      <article class="columns">

         <section class="column is-one-third">
            <ul class="ui list">
               <?php
               /* add your PHP code here */
               outputGalleries($galleries);
               ?>
            </ul>
         </section>


         <section class="column">
            <?php
            /* add your PHP code here */
            if (isset($paintings) && count($paintings) > 0) {
               outputPaintings($paintings);
            }
            ?>
         </section>
      </article>


   </main>
</body>

</html>