<?php
/* add your PHP code here */
include 'config.inc.php';
try {
  $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);

  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = "select * from Galleries order by GalleryName";

  $result = $pdo->query($sql);
  $data = $result->fetchAll(PDO::FETCH_ASSOC);
  $pdo = null;
} catch (PDOException $e) {
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
      <form class="ui form" method="get">
        <h3 class="ui dividing header">Filters</h3>

        <div class="field">
          <label>Museum</label>
          <select class="ui fluid dropdown" name="museum">
            <option value='0'>Select Museum</option>
            <?php
            /* add your PHP code here */
            foreach ($data as $row) {
              echo "<option value='" . $row['GalleryID'] . "'>" . $row['GalleryName'] . "</option>";
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

    </section>

  </main>
  <footer class="ui black inverted segment">
    <div class="ui container">&copy; 2021 Fundamentals of Web Development</div>
  </footer>
</body>

</html>