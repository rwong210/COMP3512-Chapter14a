<?php


function makeArtistName($first, $last)
{
    return utf8_encode($first . ' ' . $last);
}


/*
  You will likely need to implement functions such as these ...
*/
function getAllGalleries($connection)
{
    $galleriesGateway = new GalleryDB($connection);
    $galleries = $galleriesGateway->getAll();
    return $galleries;
}

function getAllPaintings($connection)
{
    $paintGateway = new PaintingDB($connection);
    $paintings = $paintGateway->getALL();
    return $paintings;
}

function getPaintingsByGallery($connection, $gallery)
{
    $paintGateway = new PaintingDB($connection);
    $paintings = $paintGateway->getAllForGallery($gallery);
    return $paintings;
}

function getTop20Paintings($connection){
    $paintGateway = new PaintingDB($connection);
    $paintings = $paintGateway->getTop20();
    return $paintings;
}
function outputPaintings($paintings){
    if ($paintings != null)
    foreach ($paintings as $row){
    echo '<li class="item">';
    echo '<a class="ui small image" href="single-painting.php?id=' . $row['PaintingID'] . '"><img src="images/art/works/square-medium/' .$row['ImageFileName']. '.jpg"></a>';
    echo '<div class="content">';
    echo  '<a class="header" href="single-painting.php?id=' .$row['PaintingID'].'">' . $row['Title'] . '</a>';
    echo  '<div class="meta"><span class="cinema">' .$row['FirstName'] .' '. $row['LastName'] . '</span></div>';
    echo  '<div class="description">';
    echo  '<p>' . $row['Excerpt'] . '</p>';
    echo  '</div>';
    echo  '<div class="meta">';
    echo  '<strong>' . $row['YearOfWork'] . '</strong>';
    echo  '</div>';
    echo  '</div>';
    echo  '</li>';
}
    }