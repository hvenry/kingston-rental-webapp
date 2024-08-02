<!DOCTYPE html>
<html>
    <head>
        <title>Update Groups</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <header>
            <h1>Update Group Preferences</h1>
        </header>
        <?php
        // connect to the rentaldb
        include 'connectdb.php';
        // store a message to display to the user
        $message = '';
        // flag to check if the group ID is invalid
        $invalidGroupID = false;

        // check if the update preferences form was submitted (user updated preferences)
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_preferences'])) {
            // retrieve groupID from the form
            $groupId = $_POST['group_id'];
            // retrieve the other preferences from the form, using $_POST['name'] to get the value from the form
            // parking, accessability, laundry are checkboxes, so we use isset to check if they are checked
            $parking = isset($_POST['parking']) ? 1 : 0;
            $accessibility = isset($_POST['accessibility']) ? 1 : 0;
            $laundry = isset($_POST['laundry']) ? 1 : 0;
            // number of beds, baths, max cost are number inputs, so we can just get the value
            $numBeds = $_POST['numBeds'];
            $numBaths = $_POST['numBaths'];
            $maxCost = $_POST['maxCost'];
            // rental type is a dropdown, so we can just get the value
            $rentalType = $_POST['rentalType'];

            // update the preferences in the database for the given group
            $query = "UPDATE rentalgroup
            SET parking='$parking', accessibility='$accessibility', laundry='$laundry', numBeds='$numBeds',
            numBaths='$numBaths', maxCost='$maxCost', rentalType='$rentalType' WHERE code='$groupId'";

            // execute the query and check if it was successful
            $result = $connection->query($query);
            if ($result) {
                $message = "<h4><span style='color: green;'>Preferences updated successfully.</span></h4>";
            } else {
                $message = "<h4><span style='color: red;'>Failed to update preferences.</span></h4>";
            }
        }

        // Check if the get_preferences form was submitted (user searched for group ID)
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['get_preferences'])) {
            // retrieve the group ID from the form
            $groupId = $_POST['group_id'];
            
            // query the database to retrieve the group information
            // the ? is a placeholder for the group ID, makes sure the query is safe
            $query = "SELECT * FROM rentalgroup WHERE code = ?";
            $stmt = $connection->prepare($query);
            $stmt->execute([$groupId]);
            $row = $stmt->fetch();
            
            // if the group ID is invalid, set a flag to show an error message
            if(!$row) {
                $invalidGroupID = true;
            }
        }

        // display the form to get the group ID
        // create a form to get the group ID - post means the form data will be sent to the server
        echo "<form method='post'>";
        echo "<h3>Please enter your rental group ID (ie RG001):</h3>";
        // create an input field for the group ID
        echo "<input type='text' name='group_id' required />";
        if ($invalidGroupID) {
            // if the groupID is invalid, show rerror message
            echo "<h4><span style='color: red;'>! Invalid group ID. Please try again.</span></h4>";
        }
        // create a submit button to send the form data
        echo "<p><input type='submit' name='get_preferences' value='Search'/></p>";
        // display any update messages to the user (under the form)
        echo $message;
        echo "</form>";
        
        
        // Display the preferences form if row is set (group ID is valid)
        if (isset($row) && $row) {
            // display which group is being updated
            
            
            // create a form to update the preferences
            echo "<form method='post'>";
            // store the group ID in a hidden input field
            echo "<h3>Update Preferences for Group ID: {$row['code']}</h3>";
            echo "<input type='hidden' name='group_id' value='{$row['code']}' />";
            
            // input fields for each preference
            // use the checked attribute for parking, accessibility, laundry
            echo "Parking: <input type='checkbox' name='parking' ".($row['parking'] ? 'checked' : '')."/><br>";
            echo "<br>Accessibility: <input type='checkbox' name='accessibility' ".($row['accessibility'] ? 'checked' : '')."/><br>";
            echo "<br>Laundry: <input type='checkbox' name='laundry' ".($row['laundry'] ? 'checked' : '')."/><br>";
            
            // input fields for number of beds, baths, and max cost
            echo "<br>Bedrooms: <input type='number' name='numBeds' value='{$row['numBeds']}' min='1' max='10' required/><br>";
            echo "<br>Bathrooms: <input type='number' name='numBaths' value='{$row['numBaths']}' min='1' max='10' required/><br>";
            echo "<br>Max Cost: <input type='number' name='maxCost' value='{$row['maxCost']}' step='0.01' required/><br>";

            // dropdown for rental type
            echo "<br>Rental Type:
            <select name='rentalType'>
                <option value='house' ".($row['rentalType'] == 'house' ? 'selected' : '').">House</option>
                <option value='apartment' ".($row['rentalType'] == 'apartment' ? 'selected' : '').">Apartment</option>
                <option value='room' ".($row['rentalType'] == 'room' ? 'selected' : '').">Room</option>
            </select><br>";

            // submit button to update preferences
            echo "<p><input type='submit' name='update_preferences' value='Update Preferences'/></p>";
            echo "</form>";
        }
        ?>
        <p class="center-wrapper"><a href="rental.html" class="back-button">back</a></p>
    </body>
</html>