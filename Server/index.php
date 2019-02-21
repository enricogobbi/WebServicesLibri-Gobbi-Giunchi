<?php
	//process client request (via URL)
	header ("Content-Type_application/json");
	
	$funzione = $_GET['funzione'];
	
	switch($funzione)
	{
		case '0':
			$catalogo = conversioneDati('../FileJSON/Libri.json');
			//$dati = conversioneDati('prova.json');
			$arr = array();
			
			$i = 0;
			
			foreach($catalogo['libro'] as $book)
			{
				$arr[$i] = $book['titolo'];
				$i = $i + 1;
			}
			
			deliver_response(200,"all books", $arr);
			break;
			
		case '1':
			$dati = conversioneDati('../FileJSON/Libri.json');
			$reparti = conversioneDati('../FileJSON/Reparti.json');
			$arr = array();
			$i = 0;
			$idFumetti="";
			
			foreach($reparti['reparto'] as $rep)
			{
				if(strtoupper($rep['tipo']) == strtoupper('Fumetti'))
					$idFumetti=$rep['id'];
			}
			
			foreach($dati['libro'] as $book)
			{
				if($book['reparto'] == $idFumetti && strtoupper($book['categoria']) == strtoupper('I più venduti'))
				{
					$arr[$i] = $book['titolo'];
					$i = $i + 1;
				}
			}
			
			deliver_response(200,"fumetti ", $arr);
			break;

		case '2':
			$dati = conversioneDati('../FileJSON/Libri.json');
			$categorie = conversioneDati('../FileJSON/Categorie.json');
			$libriCategorie = conversioneDati('../FileJSON/CategorieLibri.json');
			$tit=array();
			$arr = array();

			//Ricerca delle categorie che presentano uno sconto
			foreach($categorie['categoria'] as $cat)
			{
				if($cat['sconto'] != 0)
				{
					foreach($libriCategorie['categorieLibri'] as $libCat)
					{
						if($libCat['categoria'] == $cat['tipo'])
						{
							foreach($dati['libro'] as $book)
							{
								if($book['id'] == $libCat['libro'])
									array_push($arr, array("sconto"=>$cat['sconto'], "titolo"=>$book["titolo"]));
							}
						}
					}
				}
			}

			asort($arr);

			foreach($arr as $book)
			{
				array_push($tit, $book['titolo']);
			}

			deliver_response(200,"sconti  ", $tit);
			break;

		case '3':
			$tmp = mktime(0,0,0,$_GET['giorno'],$_GET['mese'],$_GET['anno']);
			$data = date($tmp);
			break;

		
		

		default:
			deliver_response(400,"Invalid request", NULL);
			break;
	}
	
	
	/*if(!empty($_GET['funzione']))
	{
	
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
	}*/
	
	function deliver_response($status, $status_message, $data)
	{
		header("HTTP/1.1 $status $status_message");
		
		$response ['status']=$status;
		$response['status_message']=$status_message;
		$response['data']=$data;
		
		$json_response=json_encode($response);
		echo $json_response;
	}
	
	function conversioneDati($json)
	{
		$str = file_get_contents($json);
		$dati = json_decode($str, true); 
		
		return $dati;
	}
	
	function get_price($find){
	/* $books=array(
	 "java"=>299,
	 "c"=>348,
	 "php"=>267
	 );*/
		
		$books = conversioneDati('libri.json');
	
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