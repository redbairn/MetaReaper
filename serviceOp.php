<?php

//////////////////////////////////////////////////////////
// Turn off all error reporting by using the value zero:
// (-1 will show every possible error)
//////////////////////////////////////////////////////////
error_reporting(0);
//////////////////////////////////////////////////////////


// Get the selected service operation (Aggregation, Non-aggregation, Clustering, Bing-Only, Google-Only, Blekko-Only).
$serviceOp = $_GET['service_op'];
//e.g $serviceOp = '1';//Aggregation option

//////////////////////////////////////////////////////////////
//Bing function with API call and layout of displayed results:
//////////////////////////////////////////////////////////////
function Bing(){
				/////////////////////////////////////////////////////////////////////////////////////////////
				//This is the variables and operations for gathering the results from the Google Search API//
				//Included: -error messages																/////
				/////////////////////////////////////////////////////////////////////////////////////////////
				
				
				/********************************************************
				* Simple PHP application for using the Bing Search API
				*********************************************************/
			
				$acctKey = 'Aj/L2hjw3F+YoU+cj4uxIO6cHbAdzRYSifcjBALMD+o';
				$rootUri = 'https://api.datamarket.azure.com/Bing/Search';
				
				//Strip whitespace (or other characters) from the beginning and end of a string
				$search = trim($_GET['query1']);
				
				//When Reap! is clicked the following will happen
				if ($_GET['bt_search'])
				{
					// Here is where you'll process the query.
					// Encode the query and the single quotes that must surround it.
					
					$query = urlencode("'{$_GET['query1']}'");
				
					// Construct the full URI for the query.
					//EXAMPLE: https://api.datamarket.azure.com/Bing/Search/Web?$format=json&Query=%27Xbox%27&$skip=50
					//There is an Image choice as well but for the project I'll be only dealing with web results. ($rootUri/Image?)
					//First set of results
					$requestUri = "$rootUri/Web?\$format=json&Query=$query";
					
						
					// The rest of the code samples in this tutorial are inside this conditional block.
					// Encode the credentials and create the stream context.
				
					$auth = base64_encode("$acctKey:$acctKey");
					
					$data = array(
					
						'http' => array(
					
						'request_fulluri' => true,
					
					// ignore_errors can help debug – remove for production. This option added in PHP 5.2.10
					
						'ignore_errors' => true,
					
						'header' => "Authorization: Basic $auth")
					
					);
					
					$context = stream_context_create($data);
						
					// Get the response from Bing.
					$response = file_get_contents($requestUri, 0, $context);
				
					// Decode the response. 
					$js = json_decode($response);
					//var_dump($js); //Tests to confirm that you have the json data
					
					// Declaring Variables for the values from the results and displayed in string
					$link = '';
					$title = '';
					$snippet = '';
					$resultStr = '';
				
					// Array created to store the query results for Bing
					$results1 = array ();
					
					//Top score for Rank 1
					$score=100;
					
					//The variables will now be populated with values from the json(results)
					foreach($js->d->results as $item){
					  $link =  $item->Url;
					  $title = $item->Title;
					  $snippet = $item->Description;
					  //Decrements through score for each result
					  $scores = $score--;
					  $results1[] = array($link, $title, $snippet, $scores);
					}
					
					
					//Next 50 results
					$requestUri = "$rootUri/Web?\$format=json&Query=$query&\$skip=50";
					// Get the second response from Bing.
					$response = file_get_contents($requestUri, 0, $context);
					// Decode the response.
					$js = json_decode($response);
				 
					foreach($js->d->results as $item){
					   $link =  $item->Url;  
					   $title = $item->Title;
					   $snippet = $item->Description;
					   $scores = $score--;
					   //The json items are split up with three variables in the array
					   $results1[] = array($link, $title, $snippet, $scores);
					}
					
					
					//Bring in the html content from the header
					include("header.php");
					echo "<p><div class=\"EngineImg\"><img src=\"Images/bing.png\" alt=\"Bing\" /></div></p>";
					
					
					
					//Count the amount of results gathered
					$stats = count($results1);
					
					if(count($results1) > 0) {
					//Display amount in string below
						echo "<div class=\"stats2\"><p>There are {$stats} results from your Bing query!</p></div>";
					}
					
					//var_dump($results); //Tests to see if the results array has been populated with anything

					//Display results back that are stored in the array
					foreach($results1 as $key=>$value){
						$resultStr .=  '<a href="'. $value[0] . '">' . $value[1] .'</a>'
								. "<br />" . $value[0] 
								. "<br />" . $value[2] 
								. "<br /><br />";
								//. "<p>Score:". $value[3] ."</p>";
					}
						

					//When no value is entered in the search box
					if(strlen($search) == 0) {
						echo "<div id=\"results\"><p>>>><strong>Error: empty search!</strong><<<</p><p>Please enter at least one word in the search box.</p></div>";
					}else{
						echo '<div id="results">' . $resultStr . '</div>';
					}
					include("footer.php");
				}//End of if statement		
}//End of function

/////////////////////////////////////////////////////////////////
//Google function with API call and layout of displayed results:
/////////////////////////////////////////////////////////////////
function Google(){
				// Replace the key with your API key and the cx with the unique ID of your search engine
				$URL =  'https://www.googleapis.com/customsearch/v1?key=AIzaSyDuV7nJLG6LlzakpCXqEHTsSYmSf2zV1b4&cx=008138479418261465699:_hyosvplhio&q=';
				$largerURL = $URL . urlencode( '\'' .  $_GET['query1']  . '\'') . '&alt=json';

				
				$request = array();
				//Loop for result sets or lots of ten from Google API
				//$result_set < 2 for the first 10 results//testing
				for($result_set=1; $result_set < 4; $result_set = $result_set + 1) { 
				
					// Initiate cURL 
					$ch=curl_init();
					
					//The request has a combination of the largerURL, the query, the number of results per page and then what number to start from
					$request = $largerURL.'&num=10&start='.((($result_set-1)*10)+1);
					
					 // Set the URL
					curl_setopt($ch,CURLOPT_URL,$request);
				   
					// Return the transfer as a string
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				   
					// Get the web page source into $data
					$data=curl_exec($ch);
				   
				   	$js = array();
					// (Assign the value)
					// Decode the json code
					$js[$result_set] = json_decode($data);
					//var_dump($js); echo "<hr />";
				}
				
				//Strip whitespace (or other characters) from the beginning and end of a string
				$search = trim($_GET['query1']);
				

				// Declaring Variables for the values from the results and displayed in string
				$link = '';
				$title = '';
				$snippet = '';
				$resultStr = '';
				
				// Array created to store the query results
				$results = array ();
				
				//To retrieve the results from the array $js, need to put in another loop
				$resultcount = 0;
				//Top score for Rank 1
				$score=100;
				
				for($i = 0; $i < 10; $i++)
				{  	
					//The variables will now be populated with values from the json(results)
					foreach($js[$i]->items as $item){
					  	$results[$resultcount][0] =  $item->link;
					  	$results[$resultcount][1] = $item->title;
					  	$results[$resultcount][2] = $item->snippet;
  						$results[$resultcount++][3] = $score--; //Decrements through score for each result                                        
                    }
				}
				//var_dump($results);
									
				//Bring in the html content from the header
				include("header.php");
				echo "<p><div class=\"EngineImg\"><img src=\"Images/Google.png\" alt=\"Google\" /></div></p>";
				
				//Count the amount of results gathered
				$stats = count($results);
					
				if(count($results) > 0) {
					//Display amount in string below
					echo "<div class=\"stats2\"><p>There are {$stats} results from your Google query!</p></div>";
				}
				  
				//Display results back that are stored in the array
				for($i = 0; $i < $resultcount; $i++) { 
					//if ($i == 10)break;
					$resultStr .= '<a href="'. $results[$i][0] . '">' . $results[$i][1] . '</a>' 
						. "<br />" . $results[$i][0] 
						. "<br />" . $results[$i][2] 
						. "<br /><br />"; 
				}
				
				//When no value is entered in the search box
				if(strlen($search) == 0) {
					echo "<div id=\"results\"><p>>>><strong>Error: empty search!</strong><<<</p><p>Please enter at least one word in the search box.</p></div>";
				}else{
					echo '<div id="results">' . $resultStr . '</div>';
				}
				include("footer.php");
}

/////////////////////////////////////////////////////////////////
//Blekko function with API call and layout of displayed results:
/////////////////////////////////////////////////////////////////
function Blekko(){
				/////////////////////////////////////////////////////////////////////////////////////////////
				//This is the variables and operations for gathering the results from the Blekko Search API//
				//Included: error messages																/////
				/////////////////////////////////////////////////////////////////////////////////////////////
				
				//CGI argument for Blekko
				//http://blekko.com/ws/?q=<QUERY>+/json+/ps=100&auth=[API Key]
					$URL =  'http://blekko.com/ws/?q=';
					$format = '+/json+/ps=100';
					$auth = '&auth=f4c8acf3';
					
				//The above items added with the query got from Google_Test.html will be assigned to the request variable.
				$request = $URL . urlencode( '\'' .  $_GET['query1']  . '\'') . $format . $auth;
				
				
				//Strip whitespace (or other characters) from the beginning and end of a string
				$search = trim($_GET['query1']);
				
				// Initiate cURL 
				$ch=curl_init();
				
				// Set the URL
				curl_setopt($ch,CURLOPT_URL,$request);
				
				// Return the transfer as a string
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				
				// Get the web page source into $data
				$data=curl_exec($ch);
				
				// Decode the json code 
				$js = json_decode($data);
				//var_dump($js); //Tests to confirm that you have the json data
				
				// Declaring Variables for the values from the results and displayed in string
				$link = '';
				$title = '';
				$snippet = '';
				$resultStr = '';
				
				// Array created to store the query results
				$results = array ();
				
				//Top score for Rank 1
				$score=100;
				
				
				//The variables will now be populated with values from the json(results)
				foreach($js->{'RESULT'} as $item){
				  $link =  $item->short_host_url;
				  $title = $item->url_title;
				  $snippet = $item->snippet;
				  //Decrements through score for each result
				  $scores = $score--;
				  $results[] = array($link, $title, $snippet, $scores);
				}
				
				//Bring in the html content from the header
				include("header.php");
				echo "<p><div class=\"EngineImg\"><img src=\"Images/blekko.png\" alt=\"Blekko\" /></div></p>";
				
				//Count the amount of results gathered
				$stats = count($results);
					
				if(count($results) > 0) {
					//Display amount in string below
					echo "<div class=\"stats2\"><p>There are {$stats} results from your Blekko query!</p></div>";
				}
				
				//var_dump($results); //Tests to see if the results array has been populated with anything
				
				//Display results back that are stored in the array
				foreach($results as $key=>$value){
				  $resultStr .= '<a href="'. $value[0] . '">' . $value[1] .'</a>'
						. "<br />" . $value[0] 
						. "<br />" . $value[2] 
						. "<br /><br />";
				}	
				//When no value is entered in the search box
				if(strlen($search) == 0) {
					echo "<div id=\"results\"><p>>>><strong>Error: empty search!</strong><<<</p><p>Please enter at least one word in the search box.</p></div>";
				}else{
					echo '<div id="results">' . $resultStr . '</div>';
				}
				include("footer.php");
	
}
//////////////////////////////////////////////////////////////////////////////////////////
//Non-Aggregation function with every API call and layout of displayed results(columned):
//////////////////////////////////////////////////////////////////////////////////////////
function NonAgg(){
				//**********//
				//BING API //
				//********//
			
				$acctKey = 'Aj/L2hjw3F+YoU+cj4uxIO6cHbAdzRYSifcjBALMD+o';
				$rootUri = 'https://api.datamarket.azure.com/Bing/Search';
				
				//Strip whitespace (or other characters) from the beginning and end of a string
				$search = trim($_GET['query1']);
				
				//When Reap! is clicked the following will happen
				if ($_GET['bt_search'])
				{
					// Here is where you'll process the query.
					// Encode the query and the single quotes that must surround it.
					
					$query = urlencode("'{$_GET['query1']}'");

					// Construct the full URI for the query.
					//EXAMPLE: https://api.datamarket.azure.com/Bing/Search/Web?$format=json&Query=%27Xbox%27&$skip=50
					//There is an Image choice as well but for the project I'll be only dealing with web results. ($rootUri/Image?)
					//First set of results
					//&\$top=10 for the top 10. Remove for any evaluation testing
					$requestUri = "$rootUri/Web?\$format=json&Query=$query&\$top=10";
					
						
					// The rest of the code samples in this tutorial are inside this conditional block.
					// Encode the credentials and create the stream context.
				
					$auth = base64_encode("$acctKey:$acctKey");
					
					$data = array(
					
						'http' => array(
					
						'request_fulluri' => true,
					
					// ignore_errors can help debug – remove for production. This option added in PHP 5.2.10
					
						'ignore_errors' => true,
					
						'header' => "Authorization: Basic $auth")
					
					);
					
					$context = stream_context_create($data);
						
					// Get the response from Bing.
					
					$response = file_get_contents($requestUri, 0, $context);
				
					// Decode the response. 
					$js = json_decode($response); 
					

					// Declaring Variables for the values from the results and displayed in string
					$link = '';
					$title = '';
					$snippet = '';
					$resultStr = '';
				
					// Array created to store the query results
					$results = array ();
					
					//Top score for Rank 1
					$score=100;
					
					//The variables will now be populated with values from the json(results)
					foreach($js->d->results as $item){
					  $link =  $item->Url;
					  $title = $item->Title;
					  $snippet = $item->Description;
					  //Decrements through score for each result
					  $scores = $score--;
					  $results[] = array($link, $title, $snippet, $scores);
					}
					
					
					////Next 50 results
//					$requestUri = "$rootUri/Web?\$format=json&Query=$query&\$skip=50";
//					// Get the second response from Bing.
//					$response = file_get_contents($requestUri, 0, $context);
//					// Decode the response.
//					$js = json_decode($response);
//				 
//					foreach($js->d->results as $item){
//					   $link =  $item->Url;
//					   $title = $item->Title;
//					   $snippet = $item->Description;
//					   //Decrements through score for each result
//					   $scores = $score--;
//					   $results[] = array($link, $title, $snippet, $scores);
//					}
					
					//Bring in the html content from the header
					include("header.php");
				

					//Count the amount of results gathered
					$stats1 = count($results);
					
				
					//Display results back that are stored in the array
					foreach($results as $key=>$value){
					  $resultStr .= '<a href="'. $value[0] . '">' . $value[1] .'</a>'
							. "<br />" . $value[0] 
							. "<br />" . $value[2] 
							. "<br /><br />";
							//. "<p>Score:". $value[3] ."</p>";
					}
				}//End of if statement//End of Bing Api (NonAgg)
				
				
				//***********//
				//GOOGLE API//
				//*********//
				
				// Replace the key with your API key and the cx with the unique ID of your search engine
				$URL =  'https://www.googleapis.com/customsearch/v1?key=AIzaSyDuV7nJLG6LlzakpCXqEHTsSYmSf2zV1b4&cx=008138479418261465699:_hyosvplhio&q=';
				$largerURL = $URL . urlencode( '\'' .  $_GET['query1']  . '\'') . '&alt=json';
				
				
				$request = array();
				//Loop for result sets or lots of ten from Google API
				//$result_set1 < 2 first 10
				for($result_set1=1; $result_set1 < 2; $result_set1 = $result_set1 + 1) { 
				
					// Initiate cURL 
					$ch=curl_init();
					
					//The request has a combination of the largerURL, the query, the number of results per page and then what number to start from
					$request = $largerURL.'&num=10&start='.((($result_set1-1)*10)+1);
					
					 // Set the URL
					curl_setopt($ch,CURLOPT_URL,$request);
				   
					// Return the transfer as a string
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				   
					// Get the web page source into $data
					$data=curl_exec($ch);
				   
				   	$js = array();
					// (Assign the value)
					// Decode the json code
					$js[$result_set1] = json_decode($data);
					//var_dump($js); echo "<hr />";
				}
				
				//Strip whitespace (or other characters) from the beginning and end of a string
				$search = trim($_GET['query1']);
				
				// Declaring Variables for the values from the results and displayed in string
				$link = '';
				$title = '';
				$snippet = '';
				$resultStr2 = '';
				
				// Array created to store the query results
				$results = array ();
				
				//To retrieve the results from the array $js, need to put in another loop
				$resultcount = 0;
				//Top score for Rank 1
				$score=100;
				for($i = 0; $i < 10; $i++)
				{  	
					//The variables will now be populated with values from the json(results)
					foreach($js[$i]->items as $item){
					  $link =  $item->link;
					  $title = $item->title;
					  $snippet = $item->snippet;
					  //Decrements through score for each result
					  $scores = $score--;
					  $results[$resultcount++] = array($link, $title, $snippet, $scores);
					}
				}
				
				//Count the amount of results gathered
				$stats2 = count($results);
					 
				//Display results back that are stored in the array
				for($i = 0; $i < $resultcount; $i++) { 
					$resultStr2 .= '<a href="'. $results[$i][0] . '">' . $results[$i][1] . '</a>' 
						. "<br />" . $results[$i][0] 
						. "<br />" . $results[$i][2] 
						. "<br /><br />"; 
				}//End of Google Api (NonAgg)
				
				
				//***********//
				//Blekko API//
				//*********//
				
				//http://blekko.com/ws/?q=<QUERY>+/json+/ps=100&auth=[API Key]
					$URL =  'http://blekko.com/ws/?q=';
					$format = '+/json+/ps=10';
					$auth = '&auth=f4c8acf3';

				//The above items added with the query got from Google_Test.html will be assigned to the request variable.
				$request = $URL . urlencode( '\'' .  $_GET['query1']  . '\'') . $format . $auth;
				
				
				//Strip whitespace (or other characters) from the beginning and end of a string
				$search = trim($_GET['query1']);
				
				// Initiate cURL 
				$ch=curl_init();
				
				// Set the URL
				curl_setopt($ch,CURLOPT_URL,$request);
				
				// Return the transfer as a string
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				
				// Get the web page source into $data
				$data=curl_exec($ch);
				
				// Decode the json code 
				$js = json_decode($data);
				//var_dump($js); //Tests to confirm that you have the json data
				
				// Declaring Variables for the values from the results and displayed in string
				$link = '';
				$title = '';
				$snippet = '';
				$resultStr3 = '';
				
				// Array created to store the query results
				$results = array ();
				
				//Top score for Rank 1
				$score=100;
				
				//The variables will now be populated with values from the json(results)
				foreach($js->{'RESULT'} as $item){
				  $link =  $item->short_host_url;
				  $title = $item->url_title;
				  $snippet = $item->snippet;
				  $results[] = array($link, $title, $snippet);
				}
				
				//Count the amount of results gathered
				$stats3 = count($results);
				
				//var_dump($results); //Tests to see if the results array has been populated with anything
				
				//Display results back that are stored in the array
				foreach($results as $key=>$value){
				  $resultStr3 .= '<a href="'. $value[0] . '">' . $value[1] .'</a>'
						. "<br />" . $value[0] 
						. "<br />" . $value[2] 
						. "<br /><br />" ;
				}
					/////////////////////////////////////////////////
					//When no value is entered in the search box:
					/////////////////////////////////////////////////
					if(strlen($search) == 0) {
						echo "<div id=\"results\"><p>>>><strong>Error: empty search!</strong><<<</p><p>Please enter at least one word in the search box.</p></div>";
					}else{
						//Each result set inside their own column
						echo '<br /><div class="col1"><div class="stats"><p>There are ' .$stats1. ' results from your <strong>Bing</strong> query!</p></div><br />' . $resultStr . '</div><div class="col2"><div class="stats"><p>There are ' .$stats2. ' results from your <strong>Google</strong> query!</p></div><br />' . $resultStr2 . '</div><div class="col3"><div class="stats"><p>There are ' .$stats3. ' results from your <strong>Blekko</strong> query!</p></div><br />' . $resultStr3 . '</div>';

					}
					include("footer.php");
}//End of NonAgg()



////////////////////////////////////////////////////////////////////////////////////////////////////
//Aggregation function with every API call and layout of displayed results(single list/10Results):
///////////////////////////////////////////////////////////////////////////////////////////////////
function Agg10(){
				//////////////
				//BING API///
				////////////
			
				$acctKey = 'Aj/L2hjw3F+YoU+cj4uxIO6cHbAdzRYSifcjBALMD+o';
				$rootUri = 'https://api.datamarket.azure.com/Bing/Search';
				
				//Strip whitespace (or other characters) from the beginning and end of a string
				$search = trim($_GET['query1']);
				
				//When Reap! is clicked the following will happen
				if ($_GET['bt_search'])
				{
					// Here is where you'll process the query.
					// Encode the query and the single quotes that must surround it.
					
					$query = urlencode("'{$_GET['query1']}'");
				
					
					
					
					// Construct the full URI for the query.
					//EXAMPLE: https://api.datamarket.azure.com/Bing/Search/Web?$format=json&Query=%27Xbox%27&$skip=50
					//There is an Image choice as well but for the project I'll be only dealing with web results. ($rootUri/Image?)
					//First set of results
					$requestUri = "$rootUri/Web?\$format=json&Query=$query&\$top=10";
					
						
					// The rest of the code samples in this tutorial are inside this conditional block.
					// Encode the credentials and create the stream context.
				
					$auth = base64_encode("$acctKey:$acctKey");
					
					$data = array(
					
						'http' => array(
					
						'request_fulluri' => true,
					
					// ignore_errors can help debug – remove for production. This option added in PHP 5.2.10
					
						'ignore_errors' => true,
					
						'header' => "Authorization: Basic $auth")
					
					);
					
					$context = stream_context_create($data);
						
					// Get the response from Bing.
					
					$response = file_get_contents($requestUri, 0, $context);
				
					// Decode the response. 
					$js = json_decode($response); 
					
					
					// Declaring Variables for the values from the results and displayed in string
					$link = '';
					$title = '';
					$snippet = '';
					$resultStr = '';
					
				
					// Array created to store the query results
					$BingArray = array ();
					
					//Aggregation:Step1:Each Search engine array has their results scored 100 - 1
					//Top score for Rank 1
					$score=100;
					//Aggregation:Step2:Trim the URL and store it in a variable in the results array
					//Prefix/specifier to be replaced by space for comparing the URL
					$prefix = array ('http://','https://','www.');
					
					//The variables will now be populated with values from the json(results)
					foreach($js->d->results as $item){
					  $link2 =  str_replace ($prefix, '', $item->Url );
					  $link = $item->Url;
					  $title = $item->Title;
					  $snippet = $item->Description;
					  //Decrements through score for each result
					  $scores = $score--;
					  $BingArray[] = array($link, $title, $snippet, $scores, $link2);
					}
					

					//TEST TOP 10 FIRST!//
					
					//Next 50 results
					//$requestUri = "$rootUri/Web?\$format=json&Query=$query&\$skip=50";
					// Get the second response from Bing.
					//$response = file_get_contents($requestUri, 0, $context);
					// Decode the response.
					//$js = json_decode($response);
				 
					//foreach($js->d->results as $item){
						//$link2 =  str_replace ($prefix, '', $item->Url );
					   //$link =  $item->Url;
					  //$title = $item->Title;
					   //$snippet = $item->Description;
					   //Decrements through score for each result
					   //$scores = $score--;
					   //$BingArray[] = array($link, $title, $snippet, $scores, $link2);
					//}
					
					//Bring in the html content from the header
					include("header.php");
				
				}//End of if statement//End of Bing Api (Agg10)
				
				//////////////
				//GOOGLE API//
				/////////////
				
				// Replace the key with your API key and the cx with the unique ID of your search engine
				$URL =  'https://www.googleapis.com/customsearch/v1?key=AIzaSyDuV7nJLG6LlzakpCXqEHTsSYmSf2zV1b4&cx=008138479418261465699:_hyosvplhio&q=';
				$largerURL = $URL . urlencode( '\'' .  $_GET['query1']  . '\'') . '&alt=json';
				
				
				$request = array();
				
				//Loop for result sets or lots of ten from Google API
				for($result_set1=1; $result_set1 < 2; $result_set1 = $result_set1 + 1) { 
				
					// Initiate cURL 
					$ch=curl_init();
					
					//The request has a combination of the largerURL, the query, the number of results per page and then what number to start from
					$request = $largerURL.'&num=10&start='.((($result_set1-1)*10)+1);
					
					 // Set the URL
					curl_setopt($ch,CURLOPT_URL,$request);
				   
					// Return the transfer as a string
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				   
					// Get the web page source into $data
					$data=curl_exec($ch);
				   
				   	$js = array();
					// (Assign the value)
					// Decode the json code
					$js[$result_set1] = json_decode($data);
					//var_dump($js); echo "<hr />";
				}
				
				//Strip whitespace (or other characters) from the beginning and end of a string
				$search = trim($_GET['query1']);
				
				// Declaring Variables for the values from the results and displayed in string
				$link = '';
				$title = '';
				$snippet = '';
				$resultStr2 = '';
				
				// Array created to store the query results
				$GoogleArray = array ();
				
				//To retrieve the results from the array $js, need to put in another loop
				$resultcount = 0;
				//Top score for Rank 1
				$score=100;
				//Prefix/specifier to be replaced by space for comparing the URL
				$prefix = array ('http://','https://','www.');
				
				for($i = 0; $i < 10; $i++)
				{  	
					//The variables will now be populated with values from the json(results)
					foreach($js[$i]->items as $item){
					  $link =  $item->link;
					  $link2 =  str_replace ($prefix, '', $item->link );
					  $title = $item->title;
					  $snippet = $item->snippet;
					  //Decrements through score for each result
					  $scores = $score--;
					  $GoogleArray[$resultcount++] = array($link, $title, $snippet, $scores, $link2);
					}
				}
				
				//Count the amount of results gathered
				$stats2 = count($results);//End of Google Api (Agg10)
				
				///////////////
				//BLEKKO API//
				/////////////
				
				//http://blekko.com/ws/?q=<QUERY>+/json+/ps=100&auth=[API Key]
					$URL =  'http://blekko.com/ws/?q=';
					//ps=10 for testing top ten results
					$format = '+/json+/ps=10';
					$auth = '&auth=f4c8acf3';
					
				//The above items added with the query got from Google_Test.html will be assigned to the request variable.
				$request = $URL . urlencode( '\'' .  $_GET['query1']  . '\'') . $format . $auth;
				
				
				//Strip whitespace (or other characters) from the beginning and end of a string
				$search = trim($_GET['query1']);
				
				// Initiate cURL 
				$ch=curl_init();
				
				// Set the URL
				curl_setopt($ch,CURLOPT_URL,$request);
				
				// Return the transfer as a string
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				
				// Get the web page source into $data
				$data=curl_exec($ch);
				
				// Decode the json code 
				$js = json_decode($data);
				//var_dump($js); //Tests to confirm that you have the json data
				
				// Declaring Variables for the values from the results and displayed in string
				$link = '';
				$title = '';
				$snippet = '';
				
				// Array created to store the query results
				$BlekkoArray = array ();
				
				//Top score for Rank 1
				$score=100;
				//Prefix/specifier to be replaced by space for comparing the URL
				$prefix = array ('http://','https://','www.');
				
				//The variables will now be populated with values from the json(results)
				foreach($js->{'RESULT'} as $item){
				  $link2 =  str_replace ($prefix, '', $item->short_host_url );
				  $link = $item->short_host_url;
				  $title = $item->url_title;
				  $snippet = $item->snippet;
				  //Decrements through score for each result
				  $scores = $score--;
				  $BlekkoArray[] = array($link, $title, $snippet, $scores, $link2);
				}
				
				//Find duplicates:Step3:Merge Arrays (one long list)
				$MergedArr=array_merge($BingArray, $GoogleArray, $BlekkoArray);
				
				//Find duplicates:Step4:Find the duplicates and add them together (re-score), then Remove any duplicates in the new list
				$Dupes_version = reduceEntries($MergedArr, $final_list);
				
				//Find duplicates:Step5:Sort the results with the highest score at the top and lowest score at the bottom (sorting)
				$sorted_version = BubbleSort($Dupes_version,false);
				
				//Check the amount of results in the list
				for($i=0;$i <= count($sorted_version); $i++) { 
					if(!isset($sorted_version[$i])){//if NULL
					unset($sorted_version[$i]);// remove item
					}
				} 
				$null_version = array_values($sorted_version);//returns all the values from the input array and indexes the array numerically.
				
				
				//Still remaining NULL entries
				for($i=0;$i <= count($null_version); $i++) { 
					if(!isset($null_version[$i])){//if NULL
					unset($null_version[$i]);// remove item
					}
				} 
				//Removal on NULL entries and new sorting
				$null_version2 = array_values($null_version);
				$sorted_version2 = BubbleSort($null_version2,false);
				//var_dump($sorted_version2);
				

				$i=0;
				//Display results back that are stored in the array
				//Top 10 results
				foreach($sorted_version2 as $key=>$value){
					if ($i == 10)break; 
				  $resultStr4 .= '<a href="'. $value[0] . '">' . $value[1] .'</a>'
						. "<br />" . $value[0] 
						. "<br />" . $value[2] 
						. "<br /><br />";
						//. "<p>Score:". $value[3] ."</p>";
						$i++;
				}
				
				
				
				
				
					/////////////////////////////////////////////////
					//When no value is entered in the search box:
					/////////////////////////////////////////////////
					if(strlen($search) == 0) {
						echo "<div id=\"results\"><p>>>><strong>Error: empty search!</strong><<<</p><p>Please enter at least one word in the search box.</p></div>";
					}else{
						//Each result set inside their own column
						echo '<div id="results">' . $resultStr4 . '</div>';
					}
					include("footer.php");
}//End of Agg10

////////////////////////////////////////////////////////////////////////////////////////////////////
//Aggregation function with every API call and layout of displayed results(single list/50Results):
///////////////////////////////////////////////////////////////////////////////////////////////////
function Agg50(){
				//////////////
				//BING API///
				////////////
			
				$acctKey = 'Aj/L2hjw3F+YoU+cj4uxIO6cHbAdzRYSifcjBALMD+o';
				$rootUri = 'https://api.datamarket.azure.com/Bing/Search';
				
				//Strip whitespace (or other characters) from the beginning and end of a string
				$search = trim($_GET['query1']);
				
				//When Reap! is clicked the following will happen
				if ($_GET['bt_search'])
				{
					// Here is where you'll process the query.
					// Encode the query and the single quotes that must surround it.
					
					$query = urlencode("'{$_GET['query1']}'");
				
					
					
					
					// Construct the full URI for the query.
					//EXAMPLE: https://api.datamarket.azure.com/Bing/Search/Web?$format=json&Query=%27Xbox%27&$skip=50
					//There is an Image choice as well but for the project I'll be only dealing with web results. ($rootUri/Image?)
					//First set of results
					$requestUri = "$rootUri/Web?\$format=json&Query=$query&\$top=20";
					
						
					// The rest of the code samples in this tutorial are inside this conditional block.
					// Encode the credentials and create the stream context.
				
					$auth = base64_encode("$acctKey:$acctKey");
					
					$data = array(
					
						'http' => array(
					
						'request_fulluri' => true,
					
					// ignore_errors can help debug – remove for production. This option added in PHP 5.2.10
					
						'ignore_errors' => true,
					
						'header' => "Authorization: Basic $auth")
					
					);
					
					$context = stream_context_create($data);
						
					// Get the response from Bing.
					
					$response = file_get_contents($requestUri, 0, $context);
				
					// Decode the response. 
					$js = json_decode($response); 
					
					
					// Declaring Variables for the values from the results and displayed in string
					$link = '';
					$title = '';
					$snippet = '';
					$resultStr = '';
					
				
					// Array created to store the query results
					$BingArray = array ();
					
					//Aggregation:Step1:Each Search engine array has their results scored 100 - 1
					//Top score for Rank 1
					$score=100;
					//Aggregation:Step2:Trim the URL and store it in a variable in the results array
					//Prefix/specifier to be replaced by space for comparing the URL
					$prefix = array ('http://','https://','www.');
					
					//The variables will now be populated with values from the json(results)
					foreach($js->d->results as $item){
					  $link2 =  str_replace ($prefix, '', $item->Url );
					  $link = $item->Url;
					  $title = $item->Title;
					  $snippet = $item->Description;
					  //Decrements through score for each result
					  $scores = $score--;
					  $BingArray[] = array($link, $title, $snippet, $scores, $link2);
					}
					

					//TEST TOP 10 FIRST!//
					
					//Next 50 results
					//$requestUri = "$rootUri/Web?\$format=json&Query=$query&\$skip=50";
					// Get the second response from Bing.
					//$response = file_get_contents($requestUri, 0, $context);
					// Decode the response.
					//$js = json_decode($response);
				 
					//foreach($js->d->results as $item){
						//$link2 =  str_replace ($prefix, '', $item->Url );
					   //$link =  $item->Url;
					  //$title = $item->Title;
					   //$snippet = $item->Description;
					   //Decrements through score for each result
					   //$scores = $score--;
					   //$BingArray[] = array($link, $title, $snippet, $scores, $link2);
					//}
					
					//Bring in the html content from the header
					include("header.php");
				
				}//End of if statement//End of Bing Api (Agg50)
				
				//////////////
				//GOOGLE API//
				/////////////
				
				// Replace the key with your API key and the cx with the unique ID of your search engine
				$URL =  'https://www.googleapis.com/customsearch/v1?key=AIzaSyDuV7nJLG6LlzakpCXqEHTsSYmSf2zV1b4&cx=008138479418261465699:_hyosvplhio&q=';
				$largerURL = $URL . urlencode( '\'' .  $_GET['query1']  . '\'') . '&alt=json';
				
				
				$request = array();
				
				//Loop for result sets or lots of ten from Google API
				for($result_set1=1; $result_set1 < 6; $result_set1 = $result_set1 + 1) { 
				
					// Initiate cURL 
					$ch=curl_init();
					
					//The request has a combination of the largerURL, the query, the number of results per page and then what number to start from
					$request = $largerURL.'&num=10&start='.((($result_set1-1)*10)+1);
					
					 // Set the URL
					curl_setopt($ch,CURLOPT_URL,$request);
				   
					// Return the transfer as a string
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				   
					// Get the web page source into $data
					$data=curl_exec($ch);
				   
				   	$js = array();
					// (Assign the value)
					// Decode the json code
					$js[$result_set1] = json_decode($data);
					//var_dump($js); echo "<hr />";
				}
				
				//Strip whitespace (or other characters) from the beginning and end of a string
				$search = trim($_GET['query1']);
				
				// Declaring Variables for the values from the results and displayed in string
				$link = '';
				$title = '';
				$snippet = '';
				$resultStr2 = '';
				
				// Array created to store the query results
				$GoogleArray = array ();
				
				//To retrieve the results from the array $js, need to put in another loop
				$resultcount = 0;
				//Top score for Rank 1
				$score=100;
				//Prefix/specifier to be replaced by space for comparing the URL
				$prefix = array ('http://','https://','www.');
				
				for($i = 0; $i < 10; $i++)
				{  	
					//The variables will now be populated with values from the json(results)
					foreach($js[$i]->items as $item){
					  $link =  $item->link;
					  $link2 =  str_replace ($prefix, '', $item->link );
					  $title = $item->title;
					  $snippet = $item->snippet;
					  //Decrements through score for each result
					  $scores = $score--;
					  $GoogleArray[$resultcount++] = array($link, $title, $snippet, $scores, $link2);
					}
				}
				
				//Count the amount of results gathered
				$stats2 = count($results);
					 
				//Display results back that are stored in the array
				for($i = 0; $i < $resultcount; $i++) { 
					$resultStr2 .= '<a href="'. $results[$i][0] . '">' . $results[$i][1] . '</a>' 
						. "<br />" . $results[$i][0] 
						. "<br />" . $results[$i][2] 
						. "<br /><br />"; 
				}//End of Google Api (Agg50)
				
				///////////////
				//BLEKKO API//
				/////////////
				
				//http://blekko.com/ws/?q=<QUERY>+/json+/ps=100&auth=[API Key]
					$URL =  'http://blekko.com/ws/?q=';
					//ps=10 for testing top ten results
					$format = '+/json+/ps=20';
					$auth = '&auth=f4c8acf3';
					
				//The above items added with the query got from Google_Test.html will be assigned to the request variable.
				$request = $URL . urlencode( '\'' .  $_GET['query1']  . '\'') . $format . $auth;
				
				
				//Strip whitespace (or other characters) from the beginning and end of a string
				$search = trim($_GET['query1']);
				
				// Initiate cURL 
				$ch=curl_init();
				
				// Set the URL
				curl_setopt($ch,CURLOPT_URL,$request);
				
				// Return the transfer as a string
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				
				// Get the web page source into $data
				$data=curl_exec($ch);
				
				// Decode the json code 
				$js = json_decode($data);
				//var_dump($js); //Tests to confirm that you have the json data
				
				// Declaring Variables for the values from the results and displayed in string
				$link = '';
				$title = '';
				$snippet = '';
				
				// Array created to store the query results
				$BlekkoArray = array ();
				
				//Top score for Rank 1
				$score=100;
				//Prefix/specifier to be replaced by space for comparing the URL
				$prefix = array ('http://','https://','www.');
				
				//The variables will now be populated with values from the json(results)
				foreach($js->{'RESULT'} as $item){
				  $link2 =  str_replace ($prefix, '', $item->short_host_url );
				  $link = $item->short_host_url;
				  $title = $item->url_title;
				  $snippet = $item->snippet;
				  //Decrements through score for each result
				  $scores = $score--;
				  $BlekkoArray[] = array($link, $title, $snippet, $scores, $link2);
				}
				
				//Find duplicates:Step3:Merge Arrays (one long list)
				$MergedArr=array_merge($BingArray, $GoogleArray, $BlekkoArray);
				
				//Find duplicates:Step4:Find the duplicates and add them together (re-score), then Remove any duplicates in the new list
				$Dupes_version = reduceEntries($MergedArr, $final_list);
				
				//Find duplicates:Step5:Sort the results with the highest score at the top and lowest score at the bottom (sorting)
				$sorted_version = BubbleSort($Dupes_version,false);
				
				//Check the amount of results in the list
				for($i=0;$i <= count($sorted_version); $i++) { 
					if(!isset($sorted_version[$i])){//if NULL
					unset($sorted_version[$i]);// remove item
					}
				} 
				$null_version = array_values($sorted_version);//returns all the values from the input array and indexes the array numerically.
				
				
				//Still remaining NULL entries
				for($i=0;$i <= count($null_version); $i++) { 
					if(!isset($null_version[$i])){//if NULL
					unset($null_version[$i]);// remove item
					}
				} 
				//Removal on NULL entries and new sorting
				$null_version2 = array_values($null_version);
				$sorted_version2 = BubbleSort($null_version2,false);
				//var_dump($sorted_version2);
				

				$i=0;
				//Display results back that are stored in the array
				//Top 10 results
				foreach($sorted_version2 as $key=>$value){
					if ($i == 50)break; 
				  $resultStr4 .= '<a href="'. $value[0] . '">' . $value[1] .'</a>'
						. "<br />" . $value[0] 
						. "<br />" . $value[2] 
						. "<br /><br />";
						//. "<p>Score:". $value[3] ."</p>";
						$i++;
				}
				
				
				
				
				
					/////////////////////////////////////////////////
					//When no value is entered in the search box:
					/////////////////////////////////////////////////
					if(strlen($search) == 0) {
						echo "<div id=\"results\"><p>>>><strong>Error: empty search!</strong><<<</p><p>Please enter at least one word in the search box.</p></div>";
					}else{
						//Each result set inside their own column
						echo '<div id="results">' . $resultStr4 . '</div>';
					}
					include("footer.php");
}//End of Agg50


////////////////////////////////////////////////////////////////////////////////////////////////////
//Aggregation function with every API call and layout of displayed results(single list/100Results):
///////////////////////////////////////////////////////////////////////////////////////////////////
function Agg100(){
				//////////////
				//BING API///
				////////////
			
				$acctKey = 'Aj/L2hjw3F+YoU+cj4uxIO6cHbAdzRYSifcjBALMD+o';
				$rootUri = 'https://api.datamarket.azure.com/Bing/Search';
				
				//Strip whitespace (or other characters) from the beginning and end of a string
				$search = trim($_GET['query1']);
				
				//When Reap! is clicked the following will happen
				if ($_GET['bt_search'])
				{
					// Here is where you'll process the query.
					// Encode the query and the single quotes that must surround it.
					
					$query = urlencode("'{$_GET['query1']}'");
				
					
					
					
					// Construct the full URI for the query.
					//EXAMPLE: https://api.datamarket.azure.com/Bing/Search/Web?$format=json&Query=%27Xbox%27&$skip=50
					//There is an Image choice as well but for the project I'll be only dealing with web results. ($rootUri/Image?)
					//First set of results
					$requestUri = "$rootUri/Web?\$format=json&Query=$query";
					
						
					// The rest of the code samples in this tutorial are inside this conditional block.
					// Encode the credentials and create the stream context.
				
					$auth = base64_encode("$acctKey:$acctKey");
					
					$data = array(
					
						'http' => array(
					
						'request_fulluri' => true,
					
					// ignore_errors can help debug – remove for production. This option added in PHP 5.2.10
					
						'ignore_errors' => true,
					
						'header' => "Authorization: Basic $auth")
					
					);
					
					$context = stream_context_create($data);
						
					// Get the response from Bing.
					
					$response = file_get_contents($requestUri, 0, $context);
				
					// Decode the response. 
					$js = json_decode($response); 
					
					
					// Declaring Variables for the values from the results and displayed in string
					$link = '';
					$title = '';
					$snippet = '';
					$resultStr = '';
					
				
					// Array created to store the query results
					$BingArray = array ();
					
					//Aggregation:Step1:Each Search engine array has their results scored 100 - 1
					//Top score for Rank 1
					$score=100;
					//Aggregation:Step2:Trim the URL and store it in a variable in the results array
					//Prefix/specifier to be replaced by space for comparing the URL
					$prefix = array ('http://','https://','www.');
					
					//The variables will now be populated with values from the json(results)
					foreach($js->d->results as $item){
					  $link2 =  str_replace ($prefix, '', $item->Url );
					  $link = $item->Url;
					  $title = $item->Title;
					  $snippet = $item->Description;
					  //Decrements through score for each result
					  $scores = $score--;
					  $BingArray[] = array($link, $title, $snippet, $scores, $link2);
					}
					

					
					
					//Next 50 results
					$requestUri = "$rootUri/Web?\$format=json&Query=$query&\$skip=50";
					 //Get the second response from Bing.
					$response = file_get_contents($requestUri, 0, $context);
					 //Decode the response.
					$js = json_decode($response);
				 
					foreach($js->d->results as $item){
						$link2 =  str_replace ($prefix, '', $item->Url );
					   	$link =  $item->Url;
					  	$title = $item->Title;
					   	$snippet = $item->Description;
					   //Decrements through score for each result
					   	$scores = $score--;
					   	$BingArray[] = array($link, $title, $snippet, $scores, $link2);
					}
					
					//Bring in the html content from the header
					include("header.php");
				
				}//End of if statement//End of Bing Api (Agg100)
				
				//////////////
				//GOOGLE API//
				/////////////
				
				// Replace the key with your API key and the cx with the unique ID of your search engine
				$URL =  'https://www.googleapis.com/customsearch/v1?key=AIzaSyDuV7nJLG6LlzakpCXqEHTsSYmSf2zV1b4&cx=008138479418261465699:_hyosvplhio&q=';
				$largerURL = $URL . urlencode( '\'' .  $_GET['query1']  . '\'') . '&alt=json';
				
				
				$request = array();
				
				//Loop for result sets or lots of ten from Google API
				for($result_set1=1; $result_set1 < 11; $result_set1 = $result_set1 + 1) { 
				
					// Initiate cURL 
					$ch=curl_init();
					
					//The request has a combination of the largerURL, the query, the number of results per page and then what number to start from
					$request = $largerURL.'&num=10&start='.((($result_set1-1)*10)+1);
					
					 // Set the URL
					curl_setopt($ch,CURLOPT_URL,$request);
				   
					// Return the transfer as a string
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				   
					// Get the web page source into $data
					$data=curl_exec($ch);
				   
				   	$js = array();
					// (Assign the value)
					// Decode the json code
					$js[$result_set1] = json_decode($data);
					//var_dump($js); echo "<hr />";
				}
				
				//Strip whitespace (or other characters) from the beginning and end of a string
				$search = trim($_GET['query1']);
				
				// Declaring Variables for the values from the results and displayed in string
				$link = '';
				$title = '';
				$snippet = '';
				$resultStr2 = '';
				
				// Array created to store the query results
				$GoogleArray = array ();
				
				//To retrieve the results from the array $js, need to put in another loop
				$resultcount = 0;
				//Top score for Rank 1
				$score=100;
				//Prefix/specifier to be replaced by space for comparing the URL
				$prefix = array ('http://','https://','www.');
				
				for($i = 0; $i < 10; $i++)
				{  	
					//The variables will now be populated with values from the json(results)
					foreach($js[$i]->items as $item){
					  $link =  $item->link;
					  $link2 =  str_replace ($prefix, '', $item->link );
					  $title = $item->title;
					  $snippet = $item->snippet;
					  //Decrements through score for each result
					  $scores = $score--;
					  $GoogleArray[$resultcount++] = array($link, $title, $snippet, $scores, $link2);
					}
				}
				
				//Count the amount of results gathered
				$stats2 = count($results);
					 
				//Display results back that are stored in the array
				for($i = 0; $i < $resultcount; $i++) { 
					$resultStr2 .= '<a href="'. $results[$i][0] . '">' . $results[$i][1] . '</a>' 
						. "<br />" . $results[$i][0] 
						. "<br />" . $results[$i][2] 
						. "<br /><br />"; 
				}//End of Google Api (Agg100)
				
				///////////////
				//BLEKKO API//
				/////////////
				
				//http://blekko.com/ws/?q=<QUERY>+/json+/ps=100&auth=[API Key]
					$URL =  'http://blekko.com/ws/?q=';
					//ps=10 for testing top ten results
					$format = '+/json+/ps=100';
					$auth = '&auth=f4c8acf3';
					
				//The above items added with the query got from Google_Test.html will be assigned to the request variable.
				$request = $URL . urlencode( '\'' .  $_GET['query1']  . '\'') . $format . $auth;
				
				
				//Strip whitespace (or other characters) from the beginning and end of a string
				$search = trim($_GET['query1']);
				
				// Initiate cURL 
				$ch=curl_init();
				
				// Set the URL
				curl_setopt($ch,CURLOPT_URL,$request);
				
				// Return the transfer as a string
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				
				// Get the web page source into $data
				$data=curl_exec($ch);
				
				// Decode the json code 
				$js = json_decode($data);
				//var_dump($js); //Tests to confirm that you have the json data
				
				// Declaring Variables for the values from the results and displayed in string
				$link = '';
				$title = '';
				$snippet = '';
				
				// Array created to store the query results
				$BlekkoArray = array ();
				
				//Top score for Rank 1
				$score=100;
				//Prefix/specifier to be replaced by space for comparing the URL
				$prefix = array ('http://','https://','www.');
				
				//The variables will now be populated with values from the json(results)
				foreach($js->{'RESULT'} as $item){
				  $link2 =  str_replace ($prefix, '', $item->short_host_url );
				  $link = $item->short_host_url;
				  $title = $item->url_title;
				  $snippet = $item->snippet;
				  //Decrements through score for each result
				  $scores = $score--;
				  $BlekkoArray[] = array($link, $title, $snippet, $scores, $link2);
				}
				
				//Find duplicates:Step3:Merge Arrays (one long list)
				$MergedArr=array_merge($BingArray, $GoogleArray, $BlekkoArray);
				
				//Find duplicates:Step4:Find the duplicates and add them together (re-score), then Remove any duplicates in the new list
				$Dupes_version = reduceEntries($MergedArr, $final_list);
				
				//Find duplicates:Step5:Sort the results with the highest score at the top and lowest score at the bottom (sorting)
				$sorted_version = BubbleSort($Dupes_version,false);
				
				//Check the amount of results in the list
				for($i=0;$i <= count($sorted_version); $i++) { 
					if(!isset($sorted_version[$i])){//if NULL
					unset($sorted_version[$i]);// remove item
					}
				} 
				$null_version = array_values($sorted_version);//returns all the values from the input array and indexes the array numerically.
				
				
				//Still remaining NULL entries
				for($i=0;$i <= count($null_version); $i++) { 
					if(!isset($null_version[$i])){//if NULL
					unset($null_version[$i]);// remove item
					}
				} 
				//Removal on NULL entries and new sorting
				$null_version2 = array_values($null_version);
				$sorted_version2 = BubbleSort($null_version2,false);
				//var_dump($sorted_version2);
				

				$i=0;
				//Display results back that are stored in the array
				//Top 10 results
				foreach($sorted_version2 as $key=>$value){
					if ($i == 100)break; 
				  $resultStr4 .= '<a href="'. $value[0] . '">' . $value[1] .'</a>'
						. "<br />" . $value[0] 
						. "<br />" . $value[2] 
						. "<br /><br />";
						//. "<p>Score:". $value[3] ."</p>";
						$i++;
				}
				
				
				
				
				
					/////////////////////////////////////////////////
					//When no value is entered in the search box:
					/////////////////////////////////////////////////
					if(strlen($search) == 0) {
						echo "<div id=\"results\"><p>>>><strong>Error: empty search!</strong><<<</p><p>Please enter at least one word in the search box.</p></div>";
					}else{
						//Each result set inside their own column
						echo '<div id="results">' . $resultStr4 . '</div>';
					}
					include("footer.php");
}//End of Agg100

function BubbleSort($sort_array) //$revese is just a trigger for whether you wanted it to sort it or not
{ 
	for ($i = 0; $i < sizeof($sort_array); $i++)
	{ 
		
		for ($j = $i + 1; $j < sizeof($sort_array); $j++)
		{ 
			//echo "Called";
			//echo $i;
			//echo $j;
			if(true) //debug stuff
			{ 
				if (borda_fuse($sort_array[$i],$sort_array[$j])) //here is where you call the function which compares one result to another
				{ 
					//echo $i;//debug displays current result
//					echo $j; //debug displays current result
//
//					echo "array i "; print_r($sort_array[$i]); //debug
//					echo "array j "; print_r($sort_array[$j]); //debug
					$tmp = $sort_array[$i]; //store result temporarily
					$sort_array[$i] = $sort_array[$j]; //copy the winning result in its place
					$sort_array[$j] = $tmp;  //put the lose in the space the winner used to have
				} 

			}else{  //this is the revese of the code above, its what happens if borda_fuse returns false
					$tmp = $sort_array[$i]; 
					$sort_array[$i] = $sort_array[$j]; 
					$sort_array[$j] = $tmp; 

			} 
		} 
	} 
	return $sort_array; 
} 


//Used in conjunction with the bubblesort
function borda_fuse($Array_a,$Array_b)
{
//this code decides which result is better
//Array_a and b are two different results and should contain the url snippet etc and the score that result has recieved index 3

//so assuming the score is a single number and store at index 3 or each result array
if($Array_a[3] < $Array_b[3]){
	return true; //ie its true that array_a is the better result
}else{
	return false; //array_b has a higher score
	}
return false; //this is to break any ties where both results have same score
}

//This function adds the duplicate scores together (re-scores) and removes duplicates afterwards.
function reduceEntries($entries)
{
    $tmpList = array();
    $indices = array();

    foreach ($entries as $i => $entry) {
        if (isset($tmpList[$entry[4]])) {
            $tmpList[$entry[4]][3] += $entry[3];
        } else {
            $tmpList[$entry[4]] = $entry;
            $indices[$entry[4]] = $i;
        }
    }

    // rebuild final array with indices
    $finalList = array();
    foreach ($tmpList as $url => $entry) {
        $finalList[$indices[$url]] = $entry;
    }

    return $finalList;
}


/////////////////////////////////////
/*Start of (srevice) radio options*/
///////////////////////////////////
if ($serviceOp == '1'){
		////////////////////////////////////////////////////////////////////////////////////////////
		////Aggregation function ///////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////////
		
		Agg10();
	
}elseif ($serviceOp == '2'){
	
		Agg50();
		
}elseif ($serviceOp == '3'){
	
		Agg100();
	
	
}elseif ($serviceOp == '4'){	
		////////////////////////////////////////////////////////////////////////////////////////////
		////Non-aggregation function ///////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////////
		//This is the variables and operations for gathering the results from the 3 engines		////
		//Included: -Bing, Google and Blekko called												////
		//-error messages etc																	////
		//////////////////////////////////////////////////////////////////////////////////////////// 
		//////////////////////////////////////////////////////////////////////////////////////////// 
		//////////////////////////////////////////////////////////////////////////////////////////// 
	
		NonAgg();
		
}elseif ($serviceOp == '5'){
		//////////////////////////
		//Bing ONLY Search API///
		////////////////////////
		Bing();

}elseif ($serviceOp == '6'){
		///////////////////////////
		//Google ONLY Search API//
		/////////////////////////
		Google();
		
		
}else{
		///////////////////////////
		//Blekko ONLY Search API//
		/////////////////////////
		Blekko();
}

?>
