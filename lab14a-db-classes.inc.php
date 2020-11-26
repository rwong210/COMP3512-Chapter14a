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

/*This creates two table gateway classes.
A gateway class ideally encapsulates all database access details within it. 
*/

class ArtistDB {
    private static $baseSQL = "SELECT * FROM Artists ORDER BY LastName";

    public function __construct($connection) {
        $this->pdo =$connection;
    }

    public function getAll() {
        $sql = self::$baseSQL;
        $statement = DatabaseHelper::runQuery($this->pdo, $sql, null);
        return $statement->fetchAll();
    }
}

class PaintingDB {
    private static $baseSQL = "SELECT PaintingID, Paintings.ArtistID AS ArtistID, FirstName,
        LastName, ImageFileName, Title, Excerpt FROM Paintings INNER JOIN Artists 
        ON Paintings.ArtistID = Artists.ArtistID";

    public function __construct($connection) {
        $this->pdo = $connection;
    }

    public function getALL() {
        $sql = self::$baseSQL;
        $statement = DatabaseHelper::runQuery($this-pdo, $sql, null);
        return $statement->fetchAll();
    }

    public function getAllForArtist($artistID) {
        $sql = self::$baseSQL . " WHERE Paintings.ArtistID=?";
        $statement = DatabaseHelper::runQuery($this->pdo, $sql, Array($artistID));
        return $statement->fetchAll();
    }
}