<?php

class DatabaseHelper
{
    // Returns a connection object to a database
    public static function createConnection($values = array())
    {
        $connString = $values[0];
        $user = $values[1];
        $password = $values[2];
        $pdo = new PDO($connString, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }


    public static function runQuery($connection, $sql, $parameters = array())
    {
        // ensure paramters are in an array
        if (!is_array($parameters)) {
            $parameters = array($parameters);
        }

        $statement = null;
        if (count($parameters) > 0) {
            $statement = $connection->prepare($sql);
            $executedOk = $statement->execute($parameters);
            if (!$executedOk) throw new PDOException;
        } else {
            //execute a normal query
            $statement = $connection->query($sql);
            if (!$statement) throw new PDOException;
        }
        return $statement;
    }
}


// Gateway class for Arists Table
class ArtistDB
{
    private static $baseSQL = "SELECT * FROM Artists ORDER BY LastName";

    public function __construct($connection)
    {
        $this->pdo = $connection;
    }

    public function getAll()
    {
        $sql = self::$baseSQL;
        $statement = DatabaseHelper::runQuery($this->pdo, $sql, null);
        return $statement->fetchAll();
    }
}

// Gateway class for Paintings Table
class PaintingDB
{
    private static $baseSQL = "SELECT PaintingID, Paintings.ArtistID, FirstName, LastName, 
    Paintings.GalleryID, GalleryName,ImageFileName, Title, 
    Excerpt FROM Galleries INNER JOIN (Artists INNER JOIN Paintings ON 
    Artists.ArtistID = Paintings.ArtistID) ON Galleries.GalleryID = Paintings.GalleryID";

    public function __construct($connection)
    {
        $this->pdo = $connection;
    }

    public function getALL()
    {
        $sql = self::$baseSQL;
        $statement = DatabaseHelper::runQuery($this->pdo, $sql, null);
        return $statement->fetchAll();
    }

    public function getAllForArtist($artistID)
    {
        $sql = self::$baseSQL . " WHERE Paintings.ArtistID=?";
        $statement = DatabaseHelper::runQuery($this->pdo, $sql, array($artistID));
        return $statement->fetchAll();
    }

    public function getAllForGallery($galleryID)
    {
        $sql = self::$baseSQL . " WHERE Paintings.GalleryID=?";
        $statement = DatabaseHelper::runQuery($this->pdo, $sql, array($galleryID));
        return $statement->fetchAll();
    }

    public function getTop20($galleryID)
    {
        $sql = "SELECT PaintingID, Paintings.ArtistID, FirstName, LastName, 
        Paintings.GalleryID, GalleryName,ImageFileName, Title, Excerpt, YearOfWork
        Excerpt FROM Galleries INNER JOIN (Artists INNER JOIN Paintings ON 
        Artists.ArtistID = Paintings.ArtistID) ON Galleries.GalleryID = Paintings.GalleryID";
        $sql .= " WHERE Paintings.GalleryID=?";
        $sql = addSortAndLimit($sql);
        $statement = DatabaseHelper::runQuery($this->pdo, $sql, array($galleryID));
        return $statement->fetchAll();
    }
}

// Gateway class for Galleries Table
class GalleryDB
{
    private static $baseSQL = "SELECT * FROM Galleries ORDER BY GalleryName";

    public function __construct($connection)
    {
        $this->pdo = $connection;
    }

    public function getAll()
    {
        $sql = getGallerySQL();
        $statement = DatabaseHelper::runQuery($this->pdo, $sql, null);
        return $statement->fetchAll();
    }
}

function getGallerySQL()
{
    $sql = 'SELECT GalleryID, GalleryName, GalleryNativeName, GalleryCity, 
    GalleryCountry, Latitude, Longitude, GalleryWebSite FROM Galleries';
    $sql .= " ORDER BY GalleryName";
    return $sql;
}

function getPaintingSQL()
{
    $sql = "SELECT PaintingID, Paintings.ArtistID AS ArtistID, FirstName, 
    LastName, GalleryID, ImageFileName, Title, ShapeID, MuseumLink, AccessionNumber, 
    CopyrightText, Description, Excerpt, YearOfWork, Width, Height, Medium, Cost, MSRP, 
    GoogleLink, GoogleDescription, WikiLink, JsonAnnotations FROM Paintings 
    INNER JOIN Artists ON Paintings.ArtistID = Artists.ArtistID  ";
    return $sql;
}

function addSortAndLimit($sqlOld)
{
    $sqlNew = $sqlOld . " ORDER BY YearOfWork limit 20";
    return $sqlNew;
}
