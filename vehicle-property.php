<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="vehicle-property.css" media="screen"/>
	<title>Fahrzeugeigenschaften</title>
</head>
<body>

<?php
if(isset($_GET['accesskey']) && !empty($_GET['accesskey'])){
	if(isset($_GET['id']) && !empty($_GET['id'])){
		$IN_accesskey = isset($_GET['accesskey']) && !empty($_GET['accesskey']) ? htmlspecialchars($_GET['accesskey']) : '';
		$IN_vehicleID = isset($_GET['id']) && !empty($_GET['id']) ? htmlspecialchars($_GET['id']) : '';
		$IN_vehicleShortName = isset($_GET['shortname']) && !empty($_GET['shortname']) ? htmlspecialchars($_GET['shortname']) : '';
		$IN_vehicleFuRn = isset($_GET['furn']) && !empty($_GET['furn']) ? htmlspecialchars($_GET['furn']) : '';

		// Get JSON data from API
		$IN_jsonData = file_get_contents("https://www.divera247.com/api/v2/using-vehicle-property/get/$IN_vehicleID?accesskey=$IN_accesskey");

		// Decode JSON data to PHP associative array
		$decodedJsonData = json_decode($IN_jsonData, true);

		// Filter all vehicle properties starting with "dash_" (and dump LATLNG)
		$arrfiltered = array_filter($decodedJsonData["data"], function ($key) {
		return strpos($key, 'dash_') === 0;
		}, ARRAY_FILTER_USE_KEY);
		
		echo "<h2>Eigenschaften von $IN_vehicleShortName ($IN_vehicleFuRn):</h2>";

		// Are there any vehicle properties starting with "dash_"  ?
		if(sizeof($arrfiltered)>0){
			// echo "<form action=\"#\" method=\"POST\">";
			 echo "<form action=\"https://www.divera247.com/api/v2/using-vehicle-property/set/$IN_vehicleID?accesskey=$IN_accesskey\" method=\"POST\">";
			
			$field_helper = 0;
			
			foreach($arrfiltered as $key => $value)
			{
				$pos1 = strpos($key, '_');
				$pos2 = strpos($key, '_', $pos1+1);
				
				$ftype = substr($key, $pos1+1, $pos2-$pos1-1);
				$fname = substr($key, $pos2+1);

				
				// Replace ("-") with (" ") for label
				// $fname = str_replace("-", " ", $fname);
				
				echo "<div class=\"input-fieldset\">";
				
				// handle "range(min-max)"
				if (str_starts_with($ftype, 'range(')) {
					$posmin = strpos($ftype, '(');
					$posmax1 = strpos($ftype, '-');
					$posmax2 = strpos($ftype, ')');
					
					$min_val = substr($ftype, $posmin+1, $posmax1-$posmin-1);
					$max_val = substr($ftype, $posmax1+1,$posmax2-$posmax1-1);
					
					if(!is_numeric($min_val))
						$min_val = 0;
					
					if(!is_numeric($max_val))
						$max_val = 9999999;
					echo "<label for=\"$key\">" . str_replace("-", " ", $fname) . " ($min_val-$max_val):</label>";
					
					$ftype = "range";
				} else {
					// min value for form input (no negative values allowed for divera247 database)
					$min_val = 0;	
					// max value for form input (9999999.9999999 max value for divera247 database)
					$max_val = 9999999;	
					echo "<label for=\"$key\">" . str_replace("-", " ", $fname) . ":</label>";
				}

				switch ($ftype) {
				case "check":
					if($value){ // hidden checkbox for posting "0" if unchecked
						echo "<input type=\"hidden\" id=\"$key\" name=\"$key\" value=\"0\" checked>";
						echo "<input type=\"checkbox\" id=\"$key\" name=\"$key\" value=\"1\" checked>";
					} else {
						echo "<input type=\"hidden\" id=\"$key\" name=\"$key\" value=\"0\">";
						echo "<input type=\"checkbox\" id=\"$key\" name=\"$key\" value=\"1\">";
					}
					break;
				case "radio":
					if($value)
						echo "<input type=\"radio\" id=\"$key\" name=\"$key\" checked>";
					else
						echo "<input type=\"radio\" id=\"$key\" name=\"$key\">";
					break;
				case "range":
					echo "<script>function change_slider_$field_helper(val) {document.getElementById(\"slider_$field_helper\").value = val;} function change_number_$field_helper(val) {document.getElementById(\"number_$field_helper\").value = val;}</script>";
					echo "<input type=\"number\" id=\"number_$field_helper\" name=\"$key\" min=\"$min_val\" max=\"$max_val\" value=\"$value\" oninput=\"change_slider_$field_helper(this.value)\" >";
					echo "<input type=\"range\"  id=\"slider_$field_helper\" name=\"$key\" min=\"$min_val\" max=\"$max_val\" value=\"$value\" oninput=\"change_number_$field_helper(this.value)\" >";
					break;
				case "date":
					echo "<input type=\"date\" id=\"$key\" name=\"$key\" value=\"$value\">";
					break;
				case "text":
					echo "<p><b>Typ \"TEXT\" wird z.Zt von DIVERA247 nicht unterstützt.</b></p>";
					echo "<input type=\"text\" id=\"$key\" name=\"$key\" value=\"$value\">";
					break;
				case "number":
					echo "<input type=\"number\" id=\"$key\" name=\"$key\" value=\"$value\">";
					break;
				case "month":
					// DIVERA kann nur Zahlen speichern. Umwandlung Float zu Monat: 2021.03 >> 2021-03
					$value2 = str_replace(".", "-", $value);
					echo "<input type=\"month\" id=\"$key\" name=\"$key\" value=\"$value2\" oninput=\"change_month_$field_helper(this.value)\">";
					// DIVERA kann nur Zahlen speichern. Umwandlung Monat zu Float:  2021-03 >> 2021.03
					echo "<script> function change_month_$field_helper(val) {document.getElementById(\"month_$field_helper\").value = val.replace(\"-\",\".\");} </script>";
					echo "<input type=\"hidden\" id=\"month_$field_helper\" name=\"$key\" value=\"$value\">";
					break;
				case "week":
					// DIVERA kann nur Zahlen speichern. Umwandlung Float zu Woche: 2021.52 >> 2021-W52
					$value2 = str_replace(".", "-W", $value);
					echo "<input type=\"week\" id=\"$key\" name=\"$key\" value=\"$value2\" oninput=\"change_week_$field_helper(this.value)\">";
					// DIVERA kann nur Zahlen speichern. Umwandlung Woche zu Float:  2021-W52 >> 2021.52
					echo "<script> function change_week_$field_helper(val) {document.getElementById(\"week_$field_helper\").value = val.replace(\"-W\",\".\");} </script>";
					echo "<input type=\"hidden\" id=\"week_$field_helper\" name=\"$key\" value=\"$value\">";
					break;
				default:
					echo "/!\ Hier ging was schief:  \"$ftype\" ist falsch oder unbekannt! /!\ ";
					break;
				}
				
				echo "</div>";
				
				$field_helper++;
			}
			
			echo "<div><input type=\"submit\" class=\"btn red\" value=\"Absenden\"><input type=\"reset\" class=\"btn grey\" value=\"Zurücksetzen\"></div></form>";

		} else {
			echo "<p>Fehler: Zu diesem Fahrzeug wurden keine Eigenschaften (dash_typ_Beschreibung) angelegt! <p>";
			echo "<a class=\"btn blue\" href=\"https://app.divera247.com/using-vehicle/update.html?id=$IN_vehicleID\">zur DIVERA247.com Fahrzeugverwaltung (Reiter: Eigenschaften)</a>";
		}
	} else {
		echo "<p><b>Fehler: Es wurde keine (Fahrzeug) ID Übergeben! </b></p>";
	}
} else {
	echo "<p><b>Fehler: Es wurde kein ACCESSKEY Übergeben! </b></p>";
}
?>
</body>
</html>