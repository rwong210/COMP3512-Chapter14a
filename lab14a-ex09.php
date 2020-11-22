<?php
/* add your PHP code here */
require_once('config.inc.php');

function findPaintings($search)
{
  try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT Title, YearOfWork FROM Paintings";
    $sql .= " WHERE Title LIKE '%$search%'";
    $sql .= " ORDER BY YearOfWork";

    $statement = $pdo->query($sql);
    $statement->bindValue(1, '%' . $search . '%');
    $statement->execute();

    $paintings = $statement->fetchAll(PDO::FETCH_ASSOC);
    $pdo = null;
    return $paintings;
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}
if (isset($_POST['search'])) {
  $paintings = findPaintings($_POST['search']);
}
function outputPaintings($paintings)
{
  foreach ($paintings as $row) {
    echo $row['Title'] . " (" . $row['YearOfWork'] . ")<br/>";
  }
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
      <form class="ui form" method="post">
        <h3 class="ui dividing header">Filters</h3>

        <div class="field">
          <label>Find painting: </label>
          <input type="text" placeholder="enter search string" name="search" />
        </div>
        <button class="small ui orange button" type="submit">
          <i class="filter icon"></i> Filter
        </button>
      </form>
    </section>


    <section class="twelve wide column">
      <?php
      /* add your PHP code here */
      if (isset($_POST['search'])) {
        if (count($paintings) > 0) {
          outputPaintings($paintings);
        } else {
          echo "no paintings found with search term = " . $_POST['search'];
        }
      } else {
        echo "Enter a search term and press Filter";
      }

      ?>
    </section>

  </main>
  <footer class="ui black inverted segment">
    <div class="ui container">&copy; 2021 Fundamentals of Web Development</div>
  </footer>
</body>

</html>