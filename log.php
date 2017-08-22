<?
date_default_timezone_set('America/Los_Angeles');
require('config/config.php');
require('config/db.php');
require('functions.php');

switch($_GET['mode']){

    case "modify_frequency":
        $direction = $_GET['direction'];
        $id = $_GET['id'];

        if($direction == "up"){
            $direction_for_query = 1;
        } elseif ($direction == "down" ){
            $direction_for_query = -1;
        }

        $stmt = mysqli_prepare($conn,
        "UPDATE plants
        SET water_frequency = water_frequency + ?
        WHERE id = ?");

        mysqli_stmt_bind_param($stmt, 'ii', $direction_for_query, $id);
        if(mysqli_stmt_execute($stmt)){
            echo ("Update successful!");
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        };
        mysqli_stmt_close($stmt);

    break;

    case "add_plant":
        // Check for submit
        if(
            isset($_POST['name']) &&
            isset($_POST['water_date']) &&
            isset($_POST['water_frequency'])
        ){
            // var_dump($_POST);
            // since this is a prepared statement, mysqli_real_escape_string is not needed.
            $name = $_POST['name'];
            $date = $_POST['water_date'];
            $frequency = $_POST['water_frequency'];

            $stmt = mysqli_prepare($conn,
            "INSERT INTO plants(name, water_date, water_frequency) VALUES(?,?,?)");

            mysqli_stmt_bind_param($stmt, 'ssi', $name, $date, $frequency);
            if(mysqli_stmt_execute($stmt)){
                // echo "INSERT successful";
                // We want to echo the new ID of the record so that it can be built on success.
                echo ($conn->insert_id);
            } else {
                echo "Error INSERTING record: " . mysqli_error($conn);
            };

            mysqli_stmt_close($stmt);
        } else {
            echo "ERROR: not all fields contain info!";
        }

    break;

    case "remove_plant":

        $id = $_GET['id'];

        $query = "DELETE FROM plants WHERE id = ?";

        $stmt = $conn->prepare($query);
        mysqli_stmt_bind_param($stmt, 'i', $id);

        if ($stmt->execute()){
            echo "Delete success";
        } else {
             echo "Error deleting record: (" . $conn->errno . ") " . $conn->error;
        };

    break;

    case "water":
        $id = $_GET['id'];

        $stmt = mysqli_prepare($conn,
        "UPDATE plants
        SET water_date = CURRENT_TIMESTAMP()
        WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        if(mysqli_stmt_execute($stmt)){
            echo "Update successful";
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        };
        mysqli_stmt_close($stmt);

    break;

    case "build_new_plant_form": ?>
            <td>
                <input name="name" type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" placeholder="(Sansevieria, English Ivy)">
            </td>
            <td>
                <input name="water_date" class="form-control mb-2 mr-sm-2 mb-sm-0" type="date"  value="<?= date("Y-m-d") ?>" id="example-date-input">
            </td>
            <td></td>
            <td>
                <input name="water_frequency" type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" placeholder="Every (#) days">
            </td>
            <td>
                <button name="submit" type="submit"  class="btn btn-block" style="width:100%">
                <i class="fa fa-plus" aria-hidden="true"></i>
            </button>
            </td>
    <?
    break;


    case "build":

    // all, row, all_edit, row_edit
    $scope = $_GET['scope'];

    $sort_name = isset($_GET['sort_name']) ? $_GET['sort_name'] : "water_frequency" ;
    $sort_name = strtolower($sort_name);
    // whitelisted values for this variable as well as $sort_order because they can not be bound like the other parameters of the query.
    if ($sort_name != "id" && $sort_name != "name" && $sort_name != "water_date" && $sort_name != "water_frequency"){
        $sort_name = "water_frequency";
    }

    $sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : "ASC" ;
    $sort_order = strtoupper($sort_order);
    if ($sort_order != "ASC" && $sort_order != "DESC") {
        $sort_order = "ASC";
    }

    // die(print_r($sort_order));


    if ($scope == "all" || $scope == "all_edit"){

        // Create Query grabbing all rows
        $query = "SELECT id, name, DATE_FORMAT(water_date, '%Y-%m-%d') as water_date, water_frequency
        FROM plants ORDER BY $sort_name $sort_order";

        $stmt = $conn->prepare($query);

        if ($stmt->execute()){
            $result = $stmt->get_result();
            $i = 0;
            while ($row = $result->fetch_assoc()){
                $flowers[$i] = $row;
                $i++;
            }
        } else {
             echo "Execute failed: (" . $conn->errno . ") " . $conn->error;
        };

    } elseif ($scope == "row" || $scope == "row_edit"){

        $flowers = array();

        $query = "SELECT id, name, DATE_FORMAT(water_date, '%Y-%m-%d') as water_date, water_frequency
        FROM plants
        WHERE id = ?";
        $id = $_GET['id'];

        $stmt = $conn->prepare($query);
        mysqli_stmt_bind_param($stmt, 'i', $id);

        if ($stmt->execute()){
            $result = $stmt->get_result();
            // Although there is only one row that should be returned by this query it needs to be the same $flowers variable. We throw it into $flowers[0] to match the layout of what the other "build all" query returns.
            $flowers[0] = $result->fetch_assoc();
            // Show a data dump of everything that was returned from the query in key => value array format.
            // die(var_dump($flowers));
        } else {
             echo "Execute failed: (" . $conn->errno . ") " . $conn->error;
        };

    }

    if(isset($flowers)){
        foreach($flowers as $flower) : ?>
            <tr <?= get_days_left($flower['water_date'],$flower['water_frequency'],"class")  ?> id="plant<?= $flower['id'] ?>">
                <td><?= $flower['name'] ?>
                    <? if ($scope == "all_edit" || $scope == "row_edit"):?>
                        <button name="remove-plant" type="submit"  data-id="<?= $flower['id'] ?>" class="btn pull-right remove-plant-button" style="background-color: inherit">
                        <?= i('trash-o') ?>
                        </button>
                    <? endif ?>
                </td>
                <td><?= nice_date($flower['water_date']) ?></td>
                <td><?= nice_date(get_next_date($flower['water_date'],$flower['water_frequency'])) ?></td>
                <td><?= nice_days(intval($flower['water_frequency'])) ?>
                    <? if ($scope == "all_edit" || $scope == "row_edit"):?>
                        <button name="add-day" type="submit"  data-id="<?= $flower['id'] ?>" data-direction="up" class="btn pull-right add-day-button" style="background-color: inherit">
                        <?= i('plus') ?>
                        </button>
                        <? if (intval($flower['water_frequency']) > 1) : ?>
                            <button name="remove-day" type="submit"  data-id="<?= $flower['id'] ?>" data-direction="down" class="btn pull-right remove-day-button" style="background-color: inherit">
                            <?= i('minus') ?>
                            </button>
                        <? endif ?>
                    <? endif ?>
                </td>
                <td><button type="button" name="water-button" class="btn btn-block water-button" data-id="<?= $flower['id'] ?>" style="opacity: 0.5;"><i class="fa fa-check" aria-hidden="true"></i></button></td>
            </tr>
        <?
        endforeach;
    } else {
        //if there are no flowers yet, display some text with instruction.
        ?> <tr id="no-plants-placeholder"><td colspan="5"><h3 style="text-align: center;">Add a plant below!</h3></td><tr><?
    }



    //free result
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    break;
// end of switch
}

//Close connection
mysqli_close($conn);
