<?php
require_once 'config.inc.php';
require_once 'lab14a-ex13-helpers.inc.php';
require_once 'lab14a-db-classes.inc.php';

/* try {
   $conn = DatabaseHelper::createConnection(array(DBCONNSTRING,DBUSER,DBPASS));
   $artSql = "SELECT ArtistID,FirstName,LastName FROM Artists ORDER BY LastName";
   $artists = DatabaseHelper::runQuery($conn,$artSql,null);

   if (isset($_GET['id']) && $_GET['id'] > 0) {
      $paintSql = 'SELECT * FROM Paintings where ArtistId=?';
      $paintings = DatabaseHelper::runQuery($conn, $paintSql, Array($_GET['id']));
   } else {
      $paintResults = null;
   }
} catch (Exception $e) { die( $e->getMessage() ); }
*/

try {
   $conn = DatabaseHelper::createConnection(array(DBCONNSTRING, DBUSER, DBPASS));
   $artGateway = new ArtistDB($conn);
   $artists = $artGateway->getAll();
   if (isset($_GET['id']) && $_GET['id'] > 0) {
      $paintGateway = new PaintingDB($conn);
      $paintings = $paintGateway->getAllForArtist($_GET['id']);
   } else {
      $paintings = null;
   }
} catch (Exception $e) {
   die($e->getMessage());
}

?>

<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Chapter 14</title>
   <link href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css" rel="stylesheet">
</head>

<body>
   <main class="ui container">
      <div class="ui secondary segment">
         <h1>Designing Data Acccess</h1>
      </div>
      <div class="ui segment">
         <div class="ui grid">
            <div class="four wide column">
               <ul class="ui link list">
                  <?php outputArtists($artists); ?>
               </ul>
            </div>
            <div class="twelve wide column">
               <div class="ui items">
                  <?php outputPaintings($paintings); ?>
               </div>
            </div>
         </div>
      </div>
   </main>

</body>

</html>