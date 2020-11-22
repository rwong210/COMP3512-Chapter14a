<?php require_once('config.inc.php'); ?>
<!DOCTYPE html>
<html>
<body>
<h1>Database Tester (PDO)</h1>
    <?php
        try {
            /*create a new PHP Data Object PDO) to define the interface with the database. This
            includes passing the constructor the connection string, the database user name and the database user password
            */ 
            $pdo = new PDO(DBCONNSTRING,DBUSER, DBPASS);

            /* set attributes on the database handle; error reporting on (default is silent)
            and throw error exceptions (throws exception and stops running)
             */
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // query statement for database
            $sql = "select * from Artists order by LastName";

            // execute an SQL statement, return the result set as PDOStatement Object (a "cursor")
            $result = $pdo->query($sql);
            $data =$result->fetchAll(PDO::FETCH_ASSOC);
            
            /*loop through the data - note that fetch returns FALSE on failure, else it returns the current data
            basically, variable row is assigned the associative array returned by fetch method acting/of result,
            row is the current row of the filtered table returned to result with each column being a field/column by name.
            In this case our SQL returns all entries from Artist table ordered by LastName field value.
            We then loop through the returned data row by row until there are no more rows, echoing the 
            value of each row's ArtistID and LastName column. Also note that the array returned from fetch has 
            an index and a fieldname key(can use 0 or ArtistID etc). setting FETCH_ASSOC means the array will only
            have a fieldname key (associative array)
            */
            
            //while($row = $result->fetch(PDO::FETCH_ASSOC)){
            //    echo $row['ArtistID'] . " - " . $row['LastName'] . "<br/>";

            // same thing as foreach loop - with a foreach the fetch() happens implicitly
            foreach ($data as $row) {
                echo $row['ArtistID'] . " - " . $row['LastName'] . "<br/>";
            }

            echo '<h2>-------- Second Loop -------</h2>';
            foreach ($data as $row) {
                echo $row['ArtistID'] . " - " . $row['LastName'] . "<br/>";
            }

            // close the connection after completion 
            $pdo = null;
        }

        // if exception occurs exit and output the exception error message
        catch (PDOException $e) {
            die( $e->getMessage() );
        }
        ?>
</body>
</html>