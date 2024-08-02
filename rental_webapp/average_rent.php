<!DOCTYPE html>
<html>
    <head>
        <title>Average Cost</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <header>
            <h1>Average Rent In Kingston</h1>
        </header>
        <?php
        echo "<h2>Average Rent:</h2>";
        // connect to the rentaldb
        include 'connectdb.php';

        // get average cost of each property type
        $query = "SELECT 'House' AS PropertyType, AVG(rp.Cost) AS AverageCost
        FROM 
            rentalproperty rp
        JOIN 
            house h ON rp.ID = h.PropertyID
        UNION ALL
        SELECT 
            'Apartment' AS PropertyType,
            AVG(rp.Cost) AS AverageCost
        FROM 
            rentalproperty rp
        JOIN 
            apartment a ON rp.ID = a.PropertyID
        UNION ALL
        SELECT 
            'Room' AS PropertyType,
            AVG(rp.Cost) AS AverageCost
        FROM 
            rentalproperty rp
        JOIN room r ON rp.ID = r.PropertyID;";

        // execute the query
        $result = $connection->query($query);

        // prepare data for table
        foreach ($result as $row) {
            switch ($row["PropertyType"]) {
                case "House":
                    $avgCostHouse = (float)$row["AverageCost"];
                    break;
                case "Apartment":
                    $avgCostApartment = (float)$row["AverageCost"];
                    break;
                case "Room":
                    $avgCostRoom = (float)$row["AverageCost"];
                    break;
            }
        }
        
        // create table
        echo "<table style='max-width: 500px;'>";
        echo "<tr><th>House</th><th>Apartment</th><th>Room</th></tr>"; // Column headers
        // Displaying the average costs as a single row
        echo "<tr>";
        echo "<td>$" . number_format((float)$avgCostHouse, 2, '.', '') . "</td>";
        echo "<td>$" . number_format((float)$avgCostApartment, 2, '.', '') . "</td>";
        echo "<td>$" . number_format((float)$avgCostRoom, 2, '.', '') . "</td>";
        echo "</tr>";
        echo "</table>";
        echo "<section class='hero'>";
        echo "<img style='max-width: 500px;' src='img/house1.jpg' alt='Kingston home' />";
        echo "</section>";
        ?>
    </body>
    <p class="center-wrapper"><a href="rental.html" class="back-button">back</a></p>
</html>