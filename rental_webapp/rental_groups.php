<!DOCTYPE html>
<html>
    <head>
        <title>Rental Groups</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <header>
            <h1>List of all Rental Groups</h1>
        </header>
        <div class="content-container">
            <div class="left-container">
                <?php
                // connect to the rentaldb
                include 'connectdb.php';

                // query to get all rental groups        
                $query = "SELECT * FROM rentalgroup";
                $result = $connection->query($query);

                // Display list of rental groups
                echo "<form action='rental_groups.php' method='post'>";
                echo "<h2>Select a rental group to view details.</h2>";
                echo "<ul>";
                while($row = $result->fetch()){
                    // create a form for each rental group, so that we can submit the name of the group
                    echo "<li>";
                    echo "<button type='submit' name='group_name' value='" . $row['code'] . "'>" . $row['code'] . "</button>";
                    echo "</li>";
                }
                echo "</ul>";
                echo "</form>";
                ?>
            </div>
            <div class="right-container">
                <?php
                // if the user has submitted the form, then we need to display the preferences of the selected group
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['group_name'])) {
                    // store the name of the selected group
                    $group_name = $_POST['group_name'];

                    // query the rentalgroup table for the selected group
                    $query = "SELECT * FROM rentalgroup WHERE code = '$group_name'";

                    // execute the query
                    $result = $connection->query($query);
                    // this will return a single row, since the group name is unique
                    $row = $result->fetch((PDO::FETCH_ASSOC));

                    // if the group was found, display the preferences
                    if ($row) {
                        echo "<h2>Selected Group: " . $row['code'] . "</h2>";
                        echo "<h4>Preferences:</h4>";

                        echo "<ul class='preferences-list'>";
                        // Display the preferences of the group
                        echo "<li>Parking: " . ($row['parking'] == 1 ? 'Yes' : 'No') . "</li>";
                        echo "<li>Accessibility: " . ($row['accessibility'] == 1 ? 'Yes' : 'No') . "</li>";
                        echo "<li>Laundry: " . ($row['laundry'] == 1 ? 'Yes' : 'No') . "</li>";            
                        echo "<li>Bedrooms: " . $row['numBeds'] . "</li>";
                        echo "<li>Bathrooms: " . $row['numBaths'] . "</li>";
                        echo "<li>Max Cost: $" . $row['maxCost'] . "</li>";
                        echo "<li>Rental Type: " . $row['rentalType'] . "</li>";
                        echo "</ul>"; 

                        // now display the names in the group
                        echo "<br><h4>Members:</h4>";
                        
                        // names of groups memebrs is found in the person table
                        $query = "SELECT p.fname, p.lname FROM renter r JOIN person p on r.PersonID = p.ID WHERE r.RentalGroupCode = '$group_name'";
                        $result = $connection->query($query);

                        $person_found = false;

                        // if there are students in the group, display them
                        echo "<ul class='preferences-list'>";
                        while($row = $result->fetch()) {
                            $person_found = true;
                            echo "<li>Student: " . $row['fname'] . " " . $row['lname'] . "</li>";
                        }
                        echo "</ul>"; 
                        // if no students were found, display a message
                        if (!$person_found) {
                            echo "<p>No members found...</p>";
                        }
                    }
                    // if the group was not found, display a message
                    else{
                        echo "<p>No group found with the name: $group_name</p>";
                    }
                }
                ?>
            </div>
        </div>
        <p class="center-wrapper"><a href="rental.html" class="back-button">back</a></p>';
    </body>
</html>
