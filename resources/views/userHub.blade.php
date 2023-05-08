<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Session;

function displayFunctions()
{
	$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	if (strpos($url,'searchFor=CS') !== false) {
	$userID = getUserID();
	$infoColumns = getInfoColumns();
	$numFamily = getVarFromUser($userID, 'numFamily');
	$serviceColumns = getServiceColumns(getVendType());
	$max_index = count($serviceColumns);

	$sugHotSpot='NO';
	if ($numFamily > 2)
	{
		$sugHotSpot='YES';
	}

	$sugRoaming=$numFamily * 2;
	$sugPrice = $numFamily * 15 + 5;

	$serviceID = $serviceColumns[0];
	$roaming = $serviceColumns[4];
	$hotspot = $serviceColumns[5];
	$textnTalk = $serviceColumns[6];
	$price = $serviceColumns[7];
	$provider = $serviceColumns[2];

	echo "<p style = 'font-size: 16px'> Recommended Values: <br>HotSpot: $sugHotSpot; Roaming Data >= $sugRoaming Gigs;" . "<br>" . "text_n_talk = Unlimited; Price <= $$sugPrice </p> <br>";
	$sqlRec = "SELECT * FROM cell_services WHERE roamingData >= '" . $sugRoaming . "' AND price <= '" . $sugPrice . "' AND text_n_talk='unlimited' AND hotspot='" . $sugHotSpot . "'";

	$resultRec = DB::select($sqlRec);
	echo "<table border='2'  margin-left='1px 1px' style='border-style:solid; font-size:11px;'>";
	echo "<p style = 'font-size: 16px'> Plans Matching Recommended Values: </p>";
	echo "<tr> <th style='padding:5px'>Business</th><th style='padding:5px'>Roaming Data</th><th style='padding:5px'>Hot Spot</th><th style='padding:5px'>Price</th><th style='padding:5px'>Text & Talk</th><th style='padding:5px'>Details</th></tr>";
	foreach($resultRec as $row)
	{
		echo "<tr>";
		for ($i=2; $i < $max_index - 1; $i++)
		{
			if($i != 3)
			{
				echo "<td>";
				$temp = $serviceColumns[$i];
				echo $row->$temp . " ";
				echo "</td>";
			}
		}
		echo "<td data-bs-toggle='modal' data-bs-target='#userModal'> ";
		echo "<p style='text-decoration:none; color:#e1ad01; font-size: 10px;'> <br>More<br>Details </p>";
		echo "<div class='modal fade' id='userModal'>";
		echo "<div class='modal-dialog'>";
			echo "<div class='modal-content'>";
				// Modal Header
				echo "<div class='modal-header'>";
					echo "<h4 style='text-align:center; color:black;' class='modal-title'>" . $row->$provider . "'s Plan </h4>";
					echo "<button type='button' class='btn-close' data-bs-dismiss='modal'></button>";
				echo "</div>";
				// Modal body 
					echo "<div class='modal-body'>";
					echo "<p style='font-size:16px; color:black;'> Gigabytes of Roaming Data: " . $row->$roaming . " </p>";
				echo "<p style='font-size:16px; color:black;'> Does this plan come with a mobile hotspot? " . $row->$hotspot . " </p>";
				echo "<p style='font-size:16px; color:black;'> Gigabytes of Text and Talk Data: " . $row->$textnTalk . " </p>";
				echo "<p style='font-size:16px; color:black;'> Price per month: " . $row->$price . " </p>";
				
				
				echo "<form method = 'post'>";
				?>
					@csrf
				<?php
					echo "<input type='submit' value='Add Service' name='button1' style='text-decoration:none; color:#e1ad01;'></button>";
				echo "</form>";
			
					echo "</div>";
				echo "</div>";
				echo "</div>";
		echo "</td>";
		echo "</tr>";
	} 
	
	echo "</table>";
	}
	
	if (strpos($url,'searchFor=IS') !== false) {
	$userID = getUserID();
	$infoColumns = getInfoColumns();
	$serviceColumns = getServiceColumns(getVendType());
	$remote = getVarFromUser($userID, 'remoteArea');
	$deviceNum = getVarFromUser($userID, 'simulInt');
	$speedVAff = getVarFromUser($userID, 'speedVAff');
	$intense = getVarFromUser($userID, 'videoGaming');

	if($remote='Yes')
	{
		$ISPType='Satellite';
		$priceMod = 1.7;
		$speedMod = .5;
	}
	else if($speedVAff='Spe')
	{
		$ISPType='Fiber';
		$priceMod = 1.25;
		$speedMod = 3;
	}
	else
	{
		$ISPType='Cable';
		$priceMod = 1;
		$speedMod = 1;
	}

	if($intense='Yes')
	{
		$intenseMod = 1;
	}
	else
	{
		$intenseMod = .3;
	}

	$download=$deviceNum * 30 * $intenseMod * $speedMod;
	$upload=$deviceNum * 5 * $intenseMod * $speedMod;

	$sugPrice=(51 * $intenseMod * $priceMod) + (3 * $deviceNum * $intenseMod);

	$table = getVendType();
	$max_index = count($serviceColumns);


	$roaming = $serviceColumns[4];
	$hotspot = $serviceColumns[5];
	$price = $serviceColumns[6];
	$provider = $serviceColumns[2];



	echo "<p style = 'font-size: 16px'> Recommended Values: <br>Download >= $download mbps; Upload >= $upload mbps;" . "<br>" . "Price <= $$sugPrice; Type = $ISPType</p> <br>";
	$sqlRec2 = "SELECT * FROM $table WHERE downloadSpeed >= '" . $download . "' AND price <= " . $price . " AND type='" . $ISPType . "' AND uploadSpeed='" . $upload . "'";

	$resultRec2 = DB::select($sqlRec2);
	echo "<table border='2'  margin-left='1px 1px' align=center style='padding-right:20px; border-style:solid; font-size:13px;'>";
	echo "<p style = 'font-size: 16px'> Plans Matching Recommended Values: </p>";
	echo "<tr> <th style='padding:5px'>Type</th><th style='padding:5px'>Download</th><th style='padding:5px'>Upload</th><th style='padding:5px'>Price</th></th><th style='padding:5px'>Details</th></tr>";
	foreach($resultRec2 as $row)
	{
		echo "<tr>";
		for ($i=2; $i < $max_index - 1; $i++)
		{
			if($i != 3)
			{
				echo "<td>";
				$temp = $serviceColumns[$i];
				echo $row->$temp . " ";
				echo "</td>";
			}
		}
		echo "<td data-bs-toggle='modal' data-bs-target='#userModal'> ";
		echo "<p style='text-decoration:none; color:#e1ad01; font-size: 10px;'> <br>More<br>Details </p>";
		echo "<div class='modal fade' id='userModal'>";
		echo "<div class='modal-dialog'>";
			echo "<div class='modal-content'>";
				// Modal Header
				echo "<div class='modal-header'>";
					echo "<h4 style='text-align:center; color:black;' class='modal-title'>" . $row->$provider . "'s Plan </h4>";
					echo "<button type='button' class='btn-close' data-bs-dismiss='modal'></button>";
				echo "</div>";
				// Modal body 
					echo "<div class='modal-body'>";
					echo "<p style='font-size:16px; color:black;'> Upload Speed: " . $row->$roaming . " </p>";
				echo "<p style='font-size:16px; color:black;'> Download Speed? " . $row->$hotspot . " </p>";
				echo "<p style='font-size:16px; color:black;'> Price per month: " . $row->$price . " </p>";
				echo "<form method = 'post'>";
				?>
					@csrf
				<?php
					echo "<input type='submit' value='Add Service' name='button1' style='text-decoration:none; color:#e1ad01;'></button>";
				echo "</form>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
		echo "</td>";
		echo "</tr>";
	} 

	//}
	echo "</table>";
	}
	
	if (strpos($url,'searchFor=LC') !== false) {
	$userID = getUserID();
	$infoColumns = getInfoColumns();
	$lawnFootage = getVarFromUser($userID, 'lawnFootage');
	$onlyMowed = getVarFromUser($userID, 'onlyMowed');
	$needAerate = 'No';
	$needWeed = 'No';
	$needCleanPool = 'No';
	$needPestsGone = 'No';
	$aerate = getVarFromUser($userID, 'aerate');
	$weeded = getVarFromUser($userID, 'weeded');	
	$deadGrass = getVarFromUser($userID, 'deadGrass');
	$pool = getVarFromUser($userID, 'pool');
	$pests = getVarFromUser($userID, 'pests');
	$serviceColumns = getServiceColumns(getVendType());
	$max_index = count($serviceColumns);
	$priceMod = 1.0;

	
	if ($onlyMowed == 'Yes')
	{
		if ($deadGrass == 'No'){
			$sugPrice = 0.03 * $lawnFootage;
		}
		else{
			$sugPrice = 0.06 * $lawnFootage;
		}
	}
	else
	{
		if($aerate == 'Yes')
		{
			$priceMod = $priceMod + 0.05;
			$needAerate = 'Yes';
		}
		if($weeded == 'Yes')
		{
			$priceMod = $priceMod + 0.02;
			$needWeed = 'Yes';
		}
		if($pool == 'Yes')
		{
			$priceMod = $priceMod + 0.03;
			$needCleanPool = 'Yes';
		}	
		if($pests == 'Yes')
		{
			$priceMod = $priceMod + 0.06;
			$needPestsGone = 'Yes';
		}
		if ($deadGrass == 'No'){
			$priceMod = $priceMod + 0.03;
		}
		else{
			$priceMod = $priceMod + 0.06;
		}
		$sugPrice=(($lawnFootage * $priceMod));
	}

	$table = getVendType();
	$serviceColumns = getServiceColumns(getVendType());
	$max_index = count($serviceColumns);


	$willAerate = $serviceColumns[4];
	$willWeed = $serviceColumns[5];
	$willCleanPool = $serviceColumns[6];
	$willKillPests = $serviceColumns[7];
	$price = $serviceColumns[8];
	$provider = $serviceColumns[2];



	echo "<p style = 'font-size: 16px'> Recommended Values Based on Your Lawn: Price <= $".$sugPrice." <br>";
	
	$sqlRec = "SELECT * FROM $table WHERE willAerate = '" . $needAerate ."' AND willWeed = '" . $needWeed ."' AND willCleanPool = '" . $needCleanPool ."' AND willKillPests = '" . $needPestsGone ."' AND price <= '" . $sugPrice . "'";

	$resultRec = DB::select($sqlRec);
	echo "<table border='2'  margin-left='1px 1px' align=center style='padding-right:20px; border-style:solid; font-size:13px;'>";
	echo "<p style = 'font-size: 16px'> Plans Matching Recommended Values: </p>";
	echo "<tr> <th style='padding:5px'>Type</th><th style='padding:5px'>Aeration</th><th style='padding:5px'>Weeding</th><th style='padding:5px'>Pool Cleaning</th><th style='padding:5px'>Pest Control</th></th><th style='padding:5px'>Details</th></tr>";
	foreach($resultRec as $row)
	{
		echo "<tr>";
		for ($i=2; $i < $max_index - 1; $i++)
		{
			if($i != 3)
			{
				echo "<td>";
				$temp = $serviceColumns[$i];
				echo $row->$temp . " ";
				echo "</td>";
			}
		}
		echo "<td data-bs-toggle='modal' data-bs-target='#userModal'> ";
		echo "<p style='text-decoration:none; color:#e1ad01; font-size: 10px;'> <br>More<br>Details </p>";
		echo "<div class='modal fade' id='userModal'>";
		echo "<div class='modal-dialog'>";
			echo "<div class='modal-content'>";
				// Modal Header
				echo "<div class='modal-header'>";
					echo "<h4 style='text-align:center; color:black;' class='modal-title'>" . $row->$provider . "'s Plan </h4>";
					echo "<button type='button' class='btn-close' data-bs-dismiss='modal'></button>";
				echo "</div>";
				// Modal body 
					echo "<div class='modal-body'>";
					echo "<p style='font-size:16px; color:black;'> Lawncare Will Aerate? " . $row->$willAerate . " </p>";
				echo "<p style='font-size:16px; color:black;'> Lawncare Will Weed? " . $row->$willWeed . " </p>";
				echo "<p style='font-size:16px; color:black;'> Lawncare Will Clean You Pool? " . $row->$willCleanPool . " </p>";
				echo "<p style='font-size:16px; color:black;'> Lawncare Will Eliminate Pests? " . $row->$willKillPests . " </p>";
				echo "<p style='font-size:16px; color:black;'> Price per month: " . $row->$price . " </p>";
				echo "<form method = 'post'>";
				?>
					@csrf
				<?php
					echo "<input type='submit' value='Add Service' name='button1' style='text-decoration:none; color:#e1ad01;'></button>";
				echo "</form>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
		echo "</td>";
		echo "</tr>";
	} 
	//}
	echo "</table>";
	}
	if (strpos($url,'searchFor=HS') !== false) {
	$userID = getUserID();
	$infoColumns = getInfoColumns();
	$squareFootage = getVarFromUser($userID, 'squareFootage');
	$numBedrooms = getVarFromUser($userID, 'numBedrooms');
	$numBathrooms = getVarFromUser($userID, 'numBathrooms');
	$needMeals = 'No';
	$needWindowPolish = 'No';
	$needLaundry = 'No';
	$needDeepClean = 'No';
	$meals = getVarFromUser($userID, 'meals');
	$windowPolish = getVarFromUser($userID, 'windowPolish');	
	$laundry = getVarFromUser($userID, 'laundry');
	$deepClean = getVarFromUser($userID, 'deepClean');
	$serviceColumns = getServiceColumns(getVendType());
	$max_index = count($serviceColumns);
	$priceMod = 1.0;
	$totalRooms = $numBedrooms + $numBathrooms;
	$pricePerRoom = 25;
		if($meals == 'Yes')
		{
			$priceMod = $priceMod + 0.75;
			$needMeals = 'Yes';
		}
		if($windowPolish == 'Yes')
		{
			$priceMod = $priceMod + 0.5;
			$needWindowPolish = 'Yes';
		}
		if($laundry == 'Yes')
		{
			$priceMod = $priceMod + 0.5;
			$needLaundry = 'Yes';
		}	
		if($deepClean == 'Yes')
		{
			$priceMod = $priceMod + 1.0;
			$needDeepClean = 'Yes';
		}
		$sugPrice=(($pricePerRoom * $totalRooms * $priceMod));

	$table = getVendType();
	$serviceColumns = getServiceColumns(getVendType());
	$max_index = count($serviceColumns);


	$willMeals = $serviceColumns[4];
	$willWindowPolish = $serviceColumns[5];
	$willLaundry = $serviceColumns[6];
	$willDeepClean = $serviceColumns[7];
	$price = $serviceColumns[8];
	$provider = $serviceColumns[2];



	echo "<p style = 'font-size: 16px'> Recommended Values Based on Your House Layout: Price <= $".$sugPrice." <br>";
	
	//$sqlRec = "SELECT * FROM $table WHERE willMeals = '" . $needMeals ."' AND willWindowPolish = '" . $needWindowPolish ."' AND willLaundry = '" . $needLaundry ."' AND willDeepClean = '" . $needDeepClean ."' AND price <= '" . $sugPrice . "'";
	$sqlRec = "SELECT * FROM housekeeping WHERE willMeals = 'Yes' AND willWindowPolish = 'Yes' AND willLaundry = 'Yes' AND willDeepClean = 'Yes' AND price <= 563";
	$resultRec = DB::select($sqlRec);
	echo "<table border='2'  margin-left='1px 1px' align=center style='padding-right:20px; border-style:solid; font-size:13px;'>";
	echo "<p style = 'font-size: 16px'> Plans Matching Recommended Values: </p>";
	echo "<tr> <th style='padding:5px'>Type</th><th style='padding:5px'>Meals</th><th style='padding:5px'>Window Polish</th><th style='padding:5px'>Laundry</th><th style='padding:5px'>Deep Clean</th></th><th style='padding:5px'>Details</th></tr>";
	foreach($resultRec as $row)
	{
		echo "<tr>";
		for ($i=2; $i < $max_index - 1; $i++)
		{
			if($i != 3)
			{
				echo "<td>";
				$temp = $serviceColumns[$i];
				echo $row->$temp . " ";
				echo "</td>";
			}
		}
		echo "<td data-bs-toggle='modal' data-bs-target='#userModal'> ";
		echo "<p style='text-decoration:none; color:#e1ad01; font-size: 10px;'> <br>More<br>Details </p>";
		echo "<div class='modal fade' id='userModal'>";
		echo "<div class='modal-dialog'>";
			echo "<div class='modal-content'>";
				// Modal Header
				echo "<div class='modal-header'>";
					echo "<h4 style='text-align:center; color:black;' class='modal-title'>" . $row->$provider . "'s Plan </h4>";
					echo "<button type='button' class='btn-close' data-bs-dismiss='modal'></button>";
				echo "</div>";
				// Modal body 
					echo "<div class='modal-body'>";
					echo "<p style='font-size:16px; color:black;'> Housekeeper Will Cook Meals? " . $row->$willMeals . " </p>";
				echo "<p style='font-size:16px; color:black;'> Housekeeper Will Polish Interior Windows? " . $row->$willWindowPolish . " </p>";
				echo "<p style='font-size:16px; color:black;'> Housekeeper Will do Laundry? " . $row->$willLaundry . " </p>";
				echo "<p style='font-size:16px; color:black;'> Housekeeper Will Deep Clean? " . $row->$willDeepClean . " </p>";
				echo "<p style='font-size:16px; color:black;'> Price per month: " . $row->$price . " </p>";
				echo "<form method = 'post'>";
				?>
					@csrf
				<?php
					echo "<input type='submit' value='Add Service' name='button1' style='text-decoration:none; color:#e1ad01;'></button>";
				echo "</form>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
		echo "</td>";
		echo "</tr>";
	} 
	//}
	echo "</table>";
	}
}

function getVarFromUser($userID, $var)
{
	$sqlVar = "SELECT " . $var . " FROM userInfo WHERE userID='" . $userID . "'";
	$result2 = DB::select($sqlVar);
	foreach($result2 as $row)
	{
		$resultVar = $row->$var;
	}
	return $resultVar;
}

function getServiceIDName($vendType)
{
	switch ($vendType) {
    	case 'cell_services':
        	return 'csID';
		break;
    	case 'health_insurance':
       	 	return 'hiID';
		break;
    	case 'home_insurance':
       	 	return 'hoiID';
		break;
	case 'auto_insurance':
       	 	return 'aiID';
		break;
	case 'internet_services':
       	 	return 'isID';
		break;
	case 'lawncare':
       	 	return 'lcID';
		break;
	case 'housekeeping':
       	 	return 'hsID';
		break;
}
}

function getVarFromServices($userID, $vendType)
{
	$var = "SELECT serviceID FROM services WHERE userID='" . $userID . "' AND vendType='" . $vendType . "' ";
	$result2 = DB::select($var);
	foreach($result2 as $row)
	{
		$resultVar = $row->serviceID;
	}
	return $resultVar;
}

function getPriceFromServices($userID, $vendType)
{
	$serviceID = getVarFromServices($userID, $vendType);
	$serviceName = getServiceIDName($vendType);
	$var = "SELECT price FROM " . $vendType . " WHERE ". $serviceName . "='" . $serviceID . "' ";
	$result2 = DB::select($var);
	foreach($result2 as $row)
	{
		$resultVar = $row->price;
	}
	return $resultVar;
}

function getInfoColumns()
{
	$columns = array();
	$sql2 = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='myhome' AND `TABLE_NAME`='userInfo'";
	$result2 = DB::select($sql2);
	foreach($result2 as $row)
	{
		array_push($columns, $row->COLUMN_NAME);
	}
	return $columns;
}

function getServiceColumns($vendType)
{
	$columns = array();
	$sql2 = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='myhome' AND `TABLE_NAME`='$vendType'";
	$result2 = DB::select($sql2);
	foreach($result2 as $row)
	{
		array_push($columns, $row->COLUMN_NAME);
	}
	return $columns;
}

function getUserID()
	{
		$sqlID = "SELECT userID FROM users WHERE firstname='" . session('firstname') . "'";
		$resultID = DB::select($sqlID);
		foreach($resultID as $row)
		{
			//echo $row->userID . "<br>";
			$userID = $row->userID;
		} 
		return $userID;
	}


function getVendType()
{
		$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		if (strpos($url,'searchFor=CS') !== false) {
			return 'cell_services';
		}
		else if(strpos($url,'searchFor=HI') !== false){
			return 'health_insurance';
		}
		else if(strpos($url,'searchFor=HOI') !== false){
			return 'home_insurance';
		}
		else if(strpos($url,'searchFor=AI') !== false){
			return 'auto_insurance';
		}
		else if(strpos($url,'searchFor=IS') !== false){
			return 'internet_services';
		}
		else if(strpos($url,'searchFor=LC') !== false){
			return 'lawncare';
		}
		else if(strpos($url,'searchFor=HS') !== false){
			return 'housekeeping';
		}
}

function addService()
	{
		$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		if (strpos($url,'searchFor=CS') !== false) {
		
		$userID = getUserID();
		$serviceColumns = getServiceColumns(getVendType());
		$numFamily = getVarFromUser($userID, 'numFamily');
		$userID = getUserID();
		$vendType = getVendType();
		$sugRoaming=$numFamily * 2;
		$sugPrice = $numFamily * 15 + 5;
		$sugHotSpot='NO';
		if ($numFamily > 2)
		{
		$sugHotSpot='YES';
		}

		$serviceName = $serviceColumns[0];
		$bName = $serviceColumns[2];
		$roaming = $serviceColumns[4];
		$hotspot = $serviceColumns[5];
		$textnTalk = $serviceColumns[6];
		$price = $serviceColumns[7];
		$provider = $serviceColumns[2];
		$sqlRec = "SELECT * FROM " . $vendType . " WHERE roamingData >= '" . $sugRoaming . "' AND price <= '" . $sugPrice . "' AND text_n_talk='unlimited' AND hotspot='" . $sugHotSpot . "'";

		$resultRec = DB::select($sqlRec);
		foreach($resultRec as $row)
		{
		
		$serviceID = $row -> $serviceName;
		$vendName = $row -> $bName;	
		$create_datetime = date("Y-m-d H:i:s");

		$query    = "INSERT into services (vendName, userID, vendType, serviceID, create_datetime) VALUES ('$vendName', '$userID', '$vendType', '$serviceID', '$create_datetime')";
		$resultService   = DB::insert($query);
		}
		}
		else if(strpos($url,'searchFor=LC') !== false){
			$userID = getUserID();
			$vendType = getVendType();
			$infoColumns = getInfoColumns();
			$lawnFootage = getVarFromUser($userID, 'lawnFootage');
			$onlyMowed = getVarFromUser($userID, 'onlyMowed');
			$needAerate = 'No';
			$needWeed = 'No';
			$needCleanPool = 'No';
			$needPestsGone = 'No';
			$aerate = getVarFromUser($userID, 'aerate');
			$weeded = getVarFromUser($userID, 'weeded');	
			$deadGrass = getVarFromUser($userID, 'deadGrass');
			$pool = getVarFromUser($userID, 'pool');
			$pests = getVarFromUser($userID, 'pests');
			$serviceColumns = getServiceColumns(getVendType());
			$max_index = count($serviceColumns);
			$priceMod = 1.0;

	
			if ($onlyMowed == 'Yes')
			{
				if ($deadGrass == 'No'){
					$sugPrice = 0.03 * $lawnFootage;
				}
				else{
					$sugPrice = 0.06 * $lawnFootage;
				}
			}
			else
			{
				if($aerate == 'Yes')
				{
				$priceMod = priceMod + 0.05;
				$needAerate = 'Yes';
				}
				if($weeded == 'Yes')
				{
					$priceMod = priceMod + 0.02;
					$needWeed = 'Yes';
				}
				if($pool == 'Yes')
				{
					$priceMod = priceMod + 0.03;
					$needCleanPool = 'Yes';
				}	
				if($pests == 'Yes')
				{
					$priceMod = priceMod + 0.06;
					$needPestsGone = 'Yes';
				}
				if ($deadGrass == 'No'){
					$priceMod = priceMod + 0.03;
				}
				else{
					$priceMod = priceMod + 0.06;
				}
				$sugPrice=((lawnFootage * $priceMod));
			}

			$table = getVendType();
			$serviceColumns = getServiceColumns(getVendType());
			$max_index = count($serviceColumns);

			$serviceName = $serviceColumns[0];
			$bName = $serviceColumns[2];
			$willAerate = $serviceColumns[4];
			$willWeed = $serviceColumns[5];
			$willCleanPool = $serviceColumns[6];
			$willKillPests = $serviceColumns[7];
			$price = $serviceColumns[8];
			$provider = $serviceColumns[2];

			$sqlRec = "SELECT * FROM $table WHERE willAerate = '" . $needAerate ."' AND willWeed = '" . $needWeed ."' AND willCleanPool = '" . $needCleanPool ."' AND willKillPests = '" . $needPestsGone ."' AND price <= '" . $sugPrice . "'";

			$resultRec = DB::select($sqlRec);
			foreach($resultRec as $row)
			{
				$serviceID = $row -> $serviceName;
				$vendName = $row -> $bName;	
				$create_datetime = date("Y-m-d H:i:s");

				$query    = "INSERT into services (vendName, userID, vendType, serviceID, create_datetime) VALUES ('$vendName', '$userID', '$vendType', '$serviceID', '$create_datetime')";
				$resultService   = DB::insert($query);
			}
		}
		else if (strpos($url,'searchFor=IS') !== false) {
			$userID = getUserID();
			$vendType = getVendType();
			$infoColumns = getInfoColumns();
			$serviceColumns = getServiceColumns(getVendType());
			$remote = getVarFromUser($userID, 'remoteArea');
			$deviceNum = getVarFromUser($userID, 'simulInt');
			$speedVAff = getVarFromUser($userID, 'speedVAff');
			$intense = getVarFromUser($userID, 'videoGaming');
	
			if($remote='Yes')
			{
			$ISPType='Satellite';
				$priceMod = 1.7;
			$speedMod = .5;
			}
			else if($speedVAff='Spe')
			{
				$ISPType='Fiber';
				$priceMod = 1.25;
				$speedMod = 3;
			}
			else
			{
				$ISPType='Cable';
				$priceMod = 1;
				$speedMod = 1;
			}

			if($intense='Yes')
			{
				$intenseMod = 1;
			}
			else
			{
				$intenseMod = .3;
			}

			$download=$deviceNum * 30 * $intenseMod * $speedMod;
			$upload=$deviceNum * 5 * $intenseMod * $speedMod;

			$sugPrice=(51 * $intenseMod * $priceMod) + (3 * $deviceNum * $intenseMod);

			$table = getVendType();
			$serviceColumns = getServiceColumns(getVendType());
			$max_index = count($serviceColumns);

			$serviceName = $serviceColumns[0];
			$bName = $serviceColumns[2];
			$roaming = $serviceColumns[4];
			$hotspot = $serviceColumns[5];
			$price2 = $serviceColumns[6];
			$provider = $serviceColumns[2];


	$sqlRec = "SELECT * FROM internet_services WHERE downloadSpeed >= " . $download . " AND price <= " . $sugPrice + 1 . " AND type='" . $ISPType . "' AND uploadSpeed=" . $upload . "";

	$resultRec = DB::select($sqlRec);
	
	foreach($resultRec as $row)
	{
		$serviceID = $row -> $serviceName;
		$vendName = $row -> $bName;	
		$create_datetime = date("Y-m-d H:i:s");

		$query    = "INSERT into services (vendName, userID, vendType, serviceID, create_datetime) VALUES ('$vendName', '$userID', '$vendType', '$serviceID', '$create_datetime')";
		$resultService   = DB::insert($query);
	} 
	}
	else if(strpos($url,'searchFor=HS') !== false){
			$userID = getUserID();
	$vendType = getVendType();
	$infoColumns = getInfoColumns();
	$squareFootage = getVarFromUser($userID, 'squareFootage');
	$numBedrooms = getVarFromUser($userID, 'numBedrooms');
	$numBathrooms = getVarFromUser($userID, 'numBathrooms');
	$needMeals = 'No';
	$needWindowPolish = 'No';
	$needLaundry = 'No';
	$needDeepClean = 'No';
	$meals = getVarFromUser($userID, 'meals');
	$windowPolish = getVarFromUser($userID, 'windowPolish');	
	$laundry = getVarFromUser($userID, 'laundry');
	$deepClean = getVarFromUser($userID, 'deepClean');
	$serviceColumns = getServiceColumns(getVendType());
	$max_index = count($serviceColumns);
	$priceMod = 1.0;
	$totalRooms = $numBedrooms + $numBathrooms;
	$pricePerRoom = 25;
		if($meals == 'Yes')
		{
			$priceMod = $priceMod + 0.75;
			$needMeals = 'Yes';
		}
		if($windowPolish == 'Yes')
		{
			$priceMod = $priceMod + 0.5;
			$needWindowPolish = 'Yes';
		}
		if($laundry == 'Yes')
		{
			$priceMod = $priceMod + 0.5;
			$needLaundry = 'Yes';
		}	
		if($deepClean == 'Yes')
		{
			$priceMod = $priceMod + 1.0;
			$needDeepClean = 'Yes';
		}
		$sugPrice=(($pricePerRoom * $totalRooms * $priceMod));

	$table = getVendType();
	$serviceColumns = getServiceColumns(getVendType());
	$max_index = count($serviceColumns);

	$serviceName = $serviceColumns[0];
	$bName = $serviceColumns[2];
	$willMeals = $serviceColumns[4];
	$willWindowPolish = $serviceColumns[5];
	$willLaundry = $serviceColumns[6];
	$willDeepClean = $serviceColumns[7];
	$price = $serviceColumns[8];
	$provider = $serviceColumns[2];


	$sqlRec = "SELECT * FROM $table WHERE willMeals = '" . $needMeals ."' AND willWindowPolish = '" . $needWindowPolish ."' AND willLaundry = '" . $needLaundry ."' AND willDeepClean = '" . $needDeepClean ."' AND price <= '" . $sugPrice . "'";

		$resultRec = DB::select($sqlRec);
			foreach($resultRec as $row)
			{
				$serviceID = $row -> $serviceName;
				$vendName = $row -> $bName;	
				$create_datetime = date("Y-m-d H:i:s");

				$query    = "INSERT into services (vendName, userID, vendType, serviceID, create_datetime) VALUES ('$vendName', '$userID', '$vendType', '$serviceID', '$create_datetime')";
				$resultService   = DB::insert($query);
			}
		}
}
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>MyHome - User Hub</title>
	<script type ="text/javascript">

function search_service() {

let input = document.getElementById('searchbar').value;

if(input == "Cell"){
window.location.href = "../searchHub";
}

}

function SelectRedirectCS()
{
    window.location.href = "../userHub?searchFor=CS";
}

function SelectRedirectIS(){
window.location.href = "../userHub?searchFor=IS";
}

function SelectRedirectHS(){
window.location.href = "../userHub?searchFor=HS";
}

function SelectRedirectAI(){
window.location.href = "../userHub?searchFor=AI";
}

function SelectRedirectHI(){
window.location.href = "../userHub?searchFor=HI";
}

function SelectRedirectHOI(){
window.location.href = "../userHub?searchFor=HOI";
}

function SelectRedirectLC(){
window.location.href = "../userHub?searchFor=LC";
}

    </script>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
	<script>var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {  return new bootstrap.Tooltip(tooltipTriggerEl)})</script>
</head>
<style>
p {
  font-size: 21px;
  font-weight: bold;
}
td {
  margin: 0px 5px;
  padding: 0px auto;
}
th {
  margin: 0px 5px;
  padding: 0px auto;
}
input[type=text]:focus {
  border-color: #8c5020;
}
input[type=password]:focus {
  border-color: #8c5020;
}
label {
  font-size: 18px;
  padding: 7px 0;
}
.form { margin: 50px auto;
    width: 100px;
    text-align: center;
    padding: 55px 40px;
    background: white;
    border-style: solid; 
    border-radius: 15px; 
    border-color: black;
}
</style>
<?php

	if(isset($_POST['submit']))
	{
		$userID = getUserID();
		$table_choice=$_POST['vendType'];
		$priceFloor=stripslashes($_REQUEST['priceFloor']);
		$priceFloor=mysqli_real_escape_string($con, $priceFloor);
		$priceCeiling=stripslashes($_REQUEST['priceCeiling']);
		$priceCeiling=mysqli_real_escape_string($con, $priceCeiling);
		$create_datetime = date("Y-m-d H:i:s");
        $query    = "INSERT into needs (userID, service_type, priceFloor, priceCeiling, time_created)
                     	VALUES ('$userID', '$table_choice', '$priceFloor', '$priceCeiling', ' $create_datetime')";
		$resultID   = mysqli_query($con, $query);
	}
    //{{ ucfirst(Auth()-> user()->firstname) }} 
?>
<body style="margin-top: 12px; background-image:url({{url('images/wood.jpeg')}})">
<div class="container-fluid">
<?php 
if ((DB::table('UserInfo')->where('address')->doesntExist()))
{
	header("Location: update");
}
if (session()->missing('firstname'))
{
    header("Location: login");
	include(app_path().'/includes/headerLoggedOut.php');
}
else
{
	include(app_path().'/includes/headerLoggedIn.php');
}
?>
		<div class = "container-fluid" style="">
		<div class = "row" style="margin: 15px 0; border-radius:15px; padding: 15px 10px; border-style: solid; border-color: black; text-align:center; background-color:white;">
		<h3>Welcome to MyHome 
        {{ session('firstname')}}
		What would you like to do? </h3>
		</div>
		<?php
		//if (session()->missing('users')) {
		//	echo "Oh no";
		//}
		?>
		<div class = "row" style="">
		<div class = "col form" style="margin:0px 10px;">
			<p style="">Your Current Services:</p><br>
			<?php
			if(isset($_POST['button1'])) {
          			  addService();
   			}	
			$columns = array();
			$columnsName = array();
			$sql2 = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='myhome' AND `TABLE_NAME`='services'";
			$result2 = DB::select($sql2);
			foreach($result2 as $row)
			{
				array_push($columns, $row->COLUMN_NAME);	
				
			}
			$sqlRec = "SELECT * FROM services WHERE userID >= " . getUserID() . "";
			$resultRec = DB::select($sqlRec);
			$vendType = getVendType();
			$vendBiz = getServiceIDName($vendType);
			echo "<table border='2'  margin-left='1px 1px' style='border-style:solid; font-size:11px; width:100%;'>";
			echo "<tr> <th style='padding:5px'>Company Name</th><th style='padding:5px'>Type</th><th style='padding:5px'>Service ID:</th><tr>";
			foreach($resultRec as $row)
			{
				echo "<tr>";
				for ($i=0; $i < 4; $i++)
				{
					if($i !== 1)
					{
						echo "<td>";
						$temp = $columns[$i];
						echo $row->$temp . " ";
						echo "</td>";
					}
				}
					
				echo "</td>";
			}
			echo "</tr>";
			echo "</table>";
			?>
		</div>
		<div class = "col form" style="text-align: left; margin:0px 10px">
		<form method="post" name="vendType">
			<p style="text-align:center;">Request Different Utilities</p>
        	<input type="radio" id="healthVendor" name="vendType" value="health_insurance" onClick ="SelectRedirectHI();">
          	@csrf
			<label for="healthVendor" style = "font-size: 1.1vw;"> Health Insurance</label><br>
			<input type="radio" id="homeVendor" name="vendType" value="home_insurance" onClick ="SelectRedirectHOI();">
			<label for="homeVendor" style = "font-size: 1.1vw;"> Homeowner's Insurance</label><br>
			<input type="radio" id="autoVendor" name="vendType" value="auto_insurance" onClick ="SelectRedirectAI();">
			<label for="autoVendor" style = "font-size: 1.1vw;"> Automobile Insurance</label><br>
			<input type="radio" id="intVendor" name="vendType" value="internet_services" onClick ="SelectRedirectIS();">
			<label for="intVendor" style = "font-size: 1.1vw;"> Internet Service</label><br>
			<input type="radio" id="cellVendor" name="vendType" value="cell_services" onClick ="SelectRedirectCS()">
			<label for="cellVendor" style = "font-size: 1.1vw;"> Cell Service</label><br>
			<input type="radio" id="lawnVendor" name="vendType" value="lawncare" onClick ="SelectRedirectLC();">
			<label for="lawnVendor" style = "font-size: 1.1vw;"> Lawncare</label><br>
			<input type="radio" id="houseVendor" name="vendType" value="housekeeping" onClick ="SelectRedirectHS();">
			<label for="houseVendor" style = "font-size: 1.1vw;"> Housekeeping</label><br>
			<!--<label for="priceFloor" text="Price Floor"> Price Floor </label><br>
			<input type="text" id="priceFloor" name="priceFloor" placeholder="Price Floor" > <br>
			<label for="priceCeiling" text="Price Ceiling">Price Ceiling </label><br>
			<input type="text" id="priceCeiling" name="priceCeiling" placeholder="Price Ceiling"> <br> <br>-->
			
		</form>
		</div>
		<div class = "col form" style="margin:0px 10px">
		@csrf
			<p style="">Recommended Services:</p><br>
			<?php
			displayFunctions();
		?>
  		</div>	
		</div>
		<div class = "row" style="margin: 15px 0; border-radius:15px; padding: 15px 10px; border-style: solid; border-color: black; text-align:center; background-color:white;">
		<div class = "col">
		<p> Affordability Calculator </p>
		<div class = "row">
		<div class = "col" style = "font-size: 17px;">
		<p> Monthly Service Prices: </p>
	
		<?php
		$userID = getUserID();
		$vendType = getVendType();
		$price = 0;
		$cellPrice = 0;
		$healthPrice = 0;
		$homePrice = 0;
		$autoPrice = 0;
		$intPrice = 0;
		$lawnPrice = 0;
		$housekeepingPrice = 0;
		$spending = getVarFromUser($userID, 'monIncome') - getVarFromUser($userID, 'mortgage');
		$price = $price + getVarFromUser($userID, 'mortgage');	
		if ((DB::table('services')->where('userID', $userID)->exists()) && (DB::table('services')->where('vendType', 'cell_services')->exists())) {
		$cellPrice = getPriceFromServices($userID, 'cell_services');
		$price = $price + getPriceFromServices($userID, 'cell_services');
		echo "Cell Service Price: $<u>" . $cellPrice . "</u><br> <br>";
		$spending = $spending - $cellPrice;
		}
		if ((DB::table('services')->where('userID', $userID)->exists()) && (DB::table('services')->where('vendType', 'health_insurance')->exists())) {
		$healthPrice = getPriceFromServices($userID, 'health_insurance');
		$price = $price + getPriceFromServices($userID, 'health_insurance');
		echo "Health Insurance Price: $<u>" . $healthPrice . "</u><br><br> ";
		$spending = $spending - $healthPrice;
		}
		if ((DB::table('services')->where('userID', $userID)->exists()) && (DB::table('services')->where('vendType', 'home_insurance')->exists())) {
		$homePrice = getPriceFromServices($userID, 'home_insurance');
		$price = $price + getPriceFromServices($userID, 'home_insurance');
		echo "Home Insurance Price: $<u>" . $homePrice . "</u><br><br> ";
		$spending = $spending - $homePrice;
		}
		if ((DB::table('services')->where('userID', $userID)->exists()) && (DB::table('services')->where('vendType', 'auto_insurance')->exists())) {
		$autoPrice = getPriceFromServices($userID, 'auto_insurance');
		$price = $price + getPriceFromServices($userID, 'auto_insurance');
		echo "Auto Insurance Price: $<u>" . $autoPrice . "</u><br><br> ";
		$spending = $spending - $autoPrice;
		}
		if ((DB::table('services')->where('userID', $userID)->exists()) && (DB::table('services')->where('vendType', 'internet_services')->exists())) {
		$intPrice = getPriceFromServices($userID, 'internet_services');
		$price = $price + getPriceFromServices($userID, 'internet_services');
		echo "Internet Service Price: $<u>" . $intPrice . "</u><br><br> ";
		$spending = $spending - $intPrice;
		}
		if ((DB::table('services')->where('userID', $userID)->exists()) && (DB::table('services')->where('vendType', 'lawncare')->exists())) {
		$lawnPrice = getPriceFromServices($userID, 'lawncare');
		$price = $price + getPriceFromServices($userID, 'lawncare');
		echo "Lawncare Price: $<u>" . $lawnPrice . "</u><br><br> ";
		$spending = $spending - $lawnPrice;
		}
		if ((DB::table('services')->where('userID', $userID)->exists()) && (DB::table('services')->where('vendType', 'housekeeping')->exists())) {
		$housekeepingPrice = getPriceFromServices($userID, 'housekeeping');
		$price = $price + getPriceFromServices($userID, 'housekeeping');
		echo "Housekeeping Price: $<u>" . $housekeepingPrice . "</u><br><br> ";
		$spending = $spending - $housekeepingPrice;
		}
		echo "Mortgage: $<u>" . getVarFromUser($userID, 'mortgage') . "</u><br><br>";
		echo "Total Spending: $<u>";
		echo $price;
		echo "</u></div>";
		echo "<div class = 'col' style = 'font-size: 17px;'>";
		echo "<p> Money Remaining </p>";
		echo "You Have $<u>" . $spending . "</u> to spend each month";
		echo "</div>";
		echo "<div class = 'col' style = 'font-size: 17px;'>";
		echo "<p> Money Available for Services </p>";
		echo "You Can Spend $<u>" . $spending + $cellPrice. "</u> on a New Cell Service Plan<br><br>";
		echo "You Can Spend $<u>" . $spending + $healthPrice. "</u> on a New Health Insurance Plan<br><br>";
		echo "You Can Spend $<u>" . $spending + $homePrice. "</u> on a New Home Insurance Plan<br><br>";
		echo "You Can Spend $<u>" . $spending + $autoPrice. "</u> on a New Auto Insurance Plan<br><br>";
		echo "You Can Spend $<u>" . $spending + $intPrice. "</u> on a New Internet Service Plan<br><br>";
		echo "You Can Spend $<u>" . $spending + $lawnPrice. "</u> on a New Lawncare Service<br><br>";
		echo "You Can Spend $<u>" . $spending + $housekeepingPrice. "</u> on a Housekeeping Service Plan<br><br>";
		?>
		</div>
		</div>
		</div>	
		</div>
		<!--<div class="row" style="text-align:center; background-color: white; border-radius:10px; border-style:solid; margin: 10px auto; width: 99%;">
			<a style="text-decoration:none; color:black;" href="{{url('logout') }}">Logout</a>
		</div> -->
</body>
</html>