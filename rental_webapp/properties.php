<!DOCTYPE html>
<html>
    <head>
        <title>Properties</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <header>
            <h1>Properties in Kingston:</h1>
        </header>
        <?php
        // connect to the rentaldb
        include 'connectdb.php';

        // query to classify what type of property (apartment, house, room) each rental property is
        $query = "SELECT rp.*, 
            CASE 
                WHEN a.propertyid IS NOT NULL THEN 'Apartment'
                WHEN h.propertyid IS NOT NULL THEN 'House'
                WHEN r.propertyid IS NOT NULL THEN 'Room'
                ELSE 'Unknown'
            END AS PropertyType,
            -- check for owner first and lastname
            CONCAT(p.fname, ' ', p.lname) AS Owner,
            CONCAT(p2.fname, ' ', p2.lname) AS Manager
        FROM rentalproperty rp
        -- join based on property type
        LEFT JOIN apartment a ON rp.id = a.propertyid
        LEFT JOIN house h ON rp.id = h.propertyid
        LEFT JOIN room r ON rp.id = r.propertyid
        -- property owner name
        LEFT JOIN ownership o ON rp.id = o.propertyid
        LEFT JOIN person p ON o.OwnerID = p.id
        -- property manager name
        LEFT JOIN manages m ON rp.id = m.propertyid
        LEFT JOIN person p2 ON m.PropertyManagerID = p2.id;";

        // execute the query
        $result = $connection->query($query);
        
        // create an associative array to store the properties
        $properties = ['Room' => [], 'House' => [], 'Apartment' => [], 'Unknown' => []];

        // loop through each row and categorize each property
        while ($row = $result->fetch()) {
            // in PHP, you do not need to index the array, you can just append to it
            $properties[$row['PropertyType']][] = $row;
        }

        // create dynamic table headers
        function generateTableHeaders($type) {
            // all headers contain ID, Street
            $headers = '<tr><th>ID</th><th>Street</th>';
            // only add the apartment number header if the type is an apartment
            if ($type === 'Apartment') {
                $headers .= '<th>Unit #</th>';
            }
            // all headers also containt City, Province, Postal Code, Cost, Owner, Manager
            $headers .= '<th>City</th><th>Province</th><th>Postal Code</th><th>Cost</th><th>Owner</th><th>Manager</th></tr>';
            return $headers;
        }

        // dynamic table rows
        function generateTableRows($rows, $type) {
            // loop through each row and display the data
            foreach ($rows as $row) {
                echo '<tr>';
                echo '<td>' . $row['ID'] . '</td>';
                echo '<td>' . $row['Street'] . '</td>';
                // only add the apartment number if the type is an apartment
                if ($type === 'Apartment') {
                    echo '<td>' . $row['ApartmentNum'] . '</td>';
                }
                echo '<td>' . $row['City'] . '</td>';
                echo '<td>' . $row['Province'] . '</td>';
                echo '<td>' . $row['PostalCode'] . '</td>';
                echo '<td>$' . $row['Cost'] . '</td>';
                echo '<td>' . $row['Owner'] . '</td>';
                echo '<td>' . $row['Manager'] . '</td>';
                echo '</tr>';
            }
        }

        // loop through the properties and display them in a table
        foreach ($properties as $type => $rows) {
            // only display the table if there are properties of that type
            if (!empty($rows)) {
                // table title
                echo "<h2>{$type}s for Rent:</h2>";
                // table content
                echo '<table border="1">';
                // Generate the headers based on the type
                echo generateTableHeaders($type);
                // Generate the rows, adjusting for the presence of an apartment number
                generateTableRows($rows, $type);
                // close the table
                echo '</table>';
            }
        }
        ?>
        <p class="center-wrapper"><a href="rental.html" class="back-button">back</a></p>
    </body>
</html>
