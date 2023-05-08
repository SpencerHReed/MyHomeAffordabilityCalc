<?php
    if (session()->missing('firstname'))
		{
			header("Location: login");
    }
    $sqlID = "SELECT userID FROM users WHERE firstname='" . session('firstname') . "'";
		$resultID = DB::select($sqlID);
    foreach($resultID as $row)
		{
			//echo $row['userID']. "<br>";
			$userID = $row ->userID;
		} 
    $serviceID = $_GET['id'];
    $userID = $_GET['user'];
    $vendType = $_GET['vend'];
            
    $values = "'" . $userID . "', '" . $vendType . "', '" . $serviceID . "',";
    $create_datetime = date("Y-m-d H:i:s");
    $values = " (" . $values . " '" . $create_datetime . "')";
    $fields = "(userID, vendType, serviceID, create_datetime)";
    $query    = "INSERT into services $fields VALUES $values";

    if (mysqli_query($con, $query)) {
        mysqli_close($con);
        header('Location: userHub.php');
        exit;
    } else {
        echo "Error deleting record";
    }
?>