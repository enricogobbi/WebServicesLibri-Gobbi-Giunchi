<?php
// process client request (via URL)
	header ("Content-Type_application/json");

	if(!empty($_GET['name'])){
	
			$name=$_GET['name'];
			$price=get_price($name);
	
			if(empty($price))
		//book not found
			deliver_response(200,"book not found", NULL);
			else
			//respond book price
			deliver_response(200,"book found", $price);
				}
	else
	{
		//throw invalid request
		deliver_response(400,"Invalid request", NULL);
	}
	
	function deliver_response($status, $status_message, $data)
	{
		header("HTTP/1.1 $status $status_message");
		
		$response ['status']=$status;
		$response['status_message']=$status_message;
		$response['data']=$data;
		
		$json_response=json_encode($response);
		echo $json_response;
	}
	
	function get_price($find){
	/* $books=array(
	 "java"=>299,
	 "c"=>348,
	 "php"=>267
	 );*/
	$str = file_get_contents('books.json');
	$books = json_decode($str, true); 
	// echo '<pre>' . print_r($books, true) . '</pre>';
	/* foreach($books as $book=>$price)
	 {
		 if($book==$find)
		 {
			 return $price;
			 break;
		 }
	 }*/
	 
	 foreach($books['book'] as $book)
	 {
		 if($book['name']==$find)
		 {
			 return $book['price'];
			 break;
		 }
	 }
 }

?>