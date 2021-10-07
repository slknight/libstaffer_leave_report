<?php

//uncomment for error reporting
//ini_set('error_reporting', E_ALL);

//Print title and todays date

echo "<h3>On Leave today, ";
	$today= date('l F j, Y');
	echo $today;
echo "</h3>";
	


$token_url = "https://eiu.libstaffer.com/api/1.0/oauth/token";

$test_api_url = "https://eiu.libstaffer.com/api/1.0/users";


//	client (application) credentials. Replace Xs with your information from LibCal admin 
$client_id = "XX";
$client_secret = "XXXXXXXXXXXXXXXXXXXXXXXXXXXX";



function getAccessToken() {
	global $token_url, $client_id, $client_secret;

	$content = "grant_type=client_credentials";
	$authorization = base64_encode("$client_id:$client_secret");
	$header = array("Authorization: Basic {$authorization}","Content-Type: application/x-www-form-urlencoded");

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $token_url,
		CURLOPT_HTTPHEADER => $header,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $content
	));
	$response = curl_exec($curl);
	curl_close($curl);

	return json_decode($response)->access_token;
}

	$access_token = getAccessToken();





	$header = array("Authorization: Bearer {$access_token}");

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $test_api_url,
		CURLOPT_HTTPHEADER => $header,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_RETURNTRANSFER => true
	));
	$response = curl_exec($curl);
	if(!$response){die("Connection Failure");}
	

	

	
	
   curl_close($curl);
   //echo $response;
   
	
    $array = json_decode($response, true);
 
 //for testing
 // print_r($array);      // Dump all data of the Object
  


//these variables run the counters for the the loops
	
$i=0;
$j=0;

//more testing. nested arrays are not my favorite thing.
//var_dump($array["data"]);

//loop through array to get user IDs

foreach($array["data"] as $key2 => $value2){
		
		
    //for testing. echos user IDs
    //echo $value2["userId"];
		
	

//the API only releases by staff ID. Creating a URL for each staff member. We only have 30. May break for big libraries.

$leaveID="https://eiu.libstaffer.com/api/1.0/users/timeoff/" . $value2["userId"];

//API call for leave data 

	$header = array("Authorization: Bearer {$access_token}");

	$curl2 = curl_init();
	curl_setopt_array($curl2, array(
		CURLOPT_URL => $leaveID,
		CURLOPT_HTTPHEADER => $header,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_RETURNTRANSFER => true
	));
	$response2 = curl_exec($curl2);
	if(!$response2){die("Connection Failure");}
	

	
	
	
   curl_close($curl2);
  // echo $response;
   
	
    $array2 = json_decode($response2, true);
//print_r($array2);      // Dump all data of the Object
  

		


//loop to get leave dates
	
	foreach($array2 as $key3 => $value3){
	
		
	
	
		
		foreach($value3['timeOff'] as $key4 => $value4){
			
			
			if(!empty($value4)){
		
				
				$from=$value4['from'];
					
				$to=$value4['to'];
				
			
				
				$today= date('Y-m-d');
$today=date('Y-m-d', strtotime($today));
			

$from= date('Y-m-d', strtotime($from));
$to= date('Y-m-d', strtotime($to));
   
//check if the leave is today

if (($today >= $to) && ($today <= $from)){
   
	echo "<p><strong>";
	echo $value3['userName'];
	echo "</strong><br>";
		echo date('g:i a', strtotime($value4['from']));
		echo " - ";
			echo date('g:i a', strtotime($value4['to']));
	echo "</p>";
	
		$i++;
   
}
				
	
			
				
				
				
				
				
				
				
				
				
			}
				
			}
}
}

if($i==0)
	{echo "<p>No one is on leave today</p>";}

//start section for leave tomorrow

echo "<h3>On Leave Tomorrow</h3>";
	
  foreach($array["data"] as $key2 => $value2){
		
		//echo $value2["userId"];
		
	


$leaveID="https://eiu.libstaffer.com/api/1.0/users/timeoff/" . $value2["userId"];

 

	$header = array("Authorization: Bearer {$access_token}");

	$curl2 = curl_init();
	curl_setopt_array($curl2, array(
		CURLOPT_URL => $leaveID,
		CURLOPT_HTTPHEADER => $header,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_RETURNTRANSFER => true
	));
	$response2 = curl_exec($curl2);
	if(!$response2){die("Connection Failure");}
	

	
	
	
   curl_close($curl2);
  // echo $response;
   
	
    $array2 = json_decode($response2, true);
//print_r($array2);      // Dump all data of the Object
  

		



	
	foreach($array2 as $key3 => $value3){
	
		
	
	
		
		foreach($value3['timeOff'] as $key4 => $value4){
			
			
			if(!empty($value4)){
		
				
				$from=$value4['from'];
					
				$to=$value4['to'];
				
			
				

$tomorrow = strtotime("tomorrow");	
				$tomorrow=date('Y-m-d', $tomorrow);

$from= date('Y-m-d', strtotime($from));
$to= date('Y-m-d', strtotime($to));
    
if (($tomorrow >= $to) && ($tomorrow <= $from)){
   
	echo "<p><strong>";
	echo $value3['userName'];
	echo "</strong><br>";
		echo date('g:i a', strtotime($value4['from']));
		echo " - ";
			echo date('g:i a', strtotime($value4['to']));
	echo "</p>";
	
	$j++;
   
}
	

				
				
				
				
				
				
				
				
				
				
			}
				
			}
}
}
if($j==0)
	echo "<p>No one is on leave today</p>";

?>
	
