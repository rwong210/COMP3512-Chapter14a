<?php

require_once 'config.inc.php';
require_once 'lab14a-test03-helpers.inc.php';
require_once 'lab14a-db-classes.inc.php';

try {
  $conn = DatabaseHelper::createConnection(array(DBCONNSTRING, DBUSER, DBPASS));
  // now retrieve galleries 
  $galleries = getAllGalleries($conn);

  // now retrieve all the paintings or paintings as a subset based on querystring
  if (isset($_GET['museum']) && $_GET['museum'] > 0) {
    $paintings = getPaintingsByGallery($conn, $_GET['museum']);
  } else{
    $paintings = getTop20Paintings($conn);
  }
} catch (Exception $e) {
  die($e->getMessage());
}

?>
<!DOCTYPE html>
<html lang=en>

<head>
  <title>Lab 14</title>
  <meta charset=utf-8>
  <link href='http://fonts.googleapis.com/css?family=Merriweather' rel='stylesheet' type='text/css'>
  <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css" rel="stylesheet">
</head>

<body>

  <main class="ui segment doubling stackable grid container">

    <section class="four wide column">
      <form class="ui form" method="get" action="<?= $_SERVER['REQUEST_URI'] ?>">
        <h3 class="ui dividing header">Filters</h3>

        <div class="field">
          <label>Museum</label>
          <select class="ui fluid dropdown" name="museum">
            <option value='0'>Select Museum</option>
            <?php
            // output all the retrieved galleries 
            // (hint: set value attribute of <option> to the GalleryID field)
            foreach ($galleries as $row) {
              echo '<option value=' . $row['GalleryID'] . '>' . $row['GalleryName'] . '</option>';
            }
            ?>
          </select>
        </div>
        <button class="small ui orange button" type="submit">
          <i class="filter icon"></i> Filter
        </button>

      </form>
    </section>


    <section class="twelve wide column">
      <h1 class="ui header">Paintings</h1>
      <ul class="ui divided items" id="paintingsList">

        <?=
          outputPaintings($paintings);
        ?>



      </ul>
    </section>

  </main>
  <footer class="ui black inverted segment">
    <div class="ui container">&Copy 2019 Fundamentals of Web Development</div>
  </footer>
</body>

</html>