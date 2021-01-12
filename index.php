<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="vehicle-property.css" media="screen"/>
	<title>Fahrzeugübersicht</title>
</head>
<body>

<?php
if(isset($_GET["accesskey"]))
{
	echo "<h2>Fahrzeuge:</h2><table><thead><tr><th>FuRn</th><th>Name (kurz)</th><th>Name (lang)</th><th>Status</th><th align=\"right\">Aktion</th></tr></thead><tbody>";

	$IN_accesskey = htmlspecialchars($_GET["accesskey"]);
	
	// Get JSON data from API
	$IN_jsonData = file_get_contents("https://www.divera247.com/api/v2/pull/vehicle-status?accesskey=$IN_accesskey");

	// Decode JSON data to PHP associative array
	$decodedJsonData = json_decode($IN_jsonData, true);
	 
	// Loop through "data" with an array for each vehicle
	foreach($decodedJsonData["data"] as $vehicleArray)
	{
		// echo "<a class=\"btn blue\" href=\"vehicle-property.php?id=$vehicleArray[id]&accesskey=$IN_accesskey\">$vehicleArray[name] - $vehicleArray[fullname]</a></br>";
		echo "<tr>
				<th>$vehicleArray[name]</th>
				<td>$vehicleArray[shortname]</td>
				<td>$vehicleArray[fullname]</td>
				<td>$vehicleArray[fmsstatus]</td>
				<td><a class=\"btn blue\" style=\"float:right\" href=\"vehicle-property.php?id=$vehicleArray[id]&shortname=$vehicleArray[shortname]&furn=$vehicleArray[name]&accesskey=$IN_accesskey\">Eigenschaften</a></td>
			</tr>";
	}
	echo "</tbody></table>";
} else {
	echo "<p><b>Fehler: Es wurde kein ACCESSKEY Übergeben! </b></p>";
	echo "<p> index.php?accesskey=HIER-MUSS-DEIN-ACCESSKEY-HIN </b></p>";
}
echo "<a class=\"btn blue\" href=\"https://app.divera247.com/status/fahrzeuge.html\">zum Statusgeber</a>";
?>
</body>
</html>