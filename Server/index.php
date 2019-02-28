<?php
	//process client request (via URL)
	header ("Content-Type_application/json");
	
	//Verifica che sia stata passata un numero di funzione da eseguire
	// if(empty($_GET['funzione']))
	// 	$funzione = null;
	// else
		$funzione = $_GET['funzione'];
	
	switch($funzione)
	{
		case '0':
			//Conversione file JSON in array associativo
			$catalogo = conversioneDati('../FileJSON/Libri.json');
			
			//inizializzazione variabili
			$arr = array();
			$i = 0;
			
			//Caricamento di un array con tutti i titoli dei libri per visualizzazione dell'intero catalogo di libri
			foreach($catalogo['libro'] as $book)
			{
				$arr[$i] = $book['titolo'];
				$i = $i + 1;
			}
			
			deliver_response(200,"all books", $arr);
			break;
			
		case '1':
			//Conversione file JSON in array associativo
			$dati = conversioneDati('../FileJSON/Libri.json');
			$reparti = conversioneDati('../FileJSON/Reparti.json');
			
			//inizializzazione variabili
			$arr = array();
			$i = 0;
			$idRep="";
			
			//Ricerca id corrispondente al reparto richiesto
			foreach($reparti['reparto'] as $rep)
			{
				if(strtoupper($rep['tipo']) == strtoupper(/*$_GET['reparto']*/"fumetti"))
					$idRep=$rep['id'];
			}
			
			//Ricerca dei libri del reparto richiesto e appartenenti alla categoria de "I più venduti"
			foreach($dati['libro'] as $book)
			{
				if($book['reparto'] == $idRep && strtoupper($book['categoria']) == strtoupper('I più venduti'))
				{
					$arr[$i] = $book['titolo'];
					$i = $i + 1;
				}
			}
			
			deliver_response(200,"fumetti ", $arr);
			break;

		case '2':
			//Conversione file JSON in array associativo
			$dati = conversioneDati('../FileJSON/Libri.json');
			$categorie = conversioneDati('../FileJSON/Categorie.json');
			$libriCategorie = conversioneDati('../FileJSON/CategorieLibri.json');

			//inizializzazione variabili
			$tit=array();
			$arr = array();

			//Ricerca dei libri appartenenti alle categorie categorie che presentano uno sconto
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
			
			//Popolamento array da inviare con tutti i titoli dei libri in sconto
			foreach($arr as $book)
			{
				array_push($tit, $book['titolo']);
			}

			deliver_response(200,"scontati", $tit);
			break;

		case '3':
			//Conversione file JSON in array associativo
			$dati = conversioneDati('../FileJSON/Libri.json');

			//inizializzazione variabili
			$arr = array();

			//split date inserite in input
			$tmpInizio = explode('/', $_GET['data1']);;
			$tmpFine = explode('/', $_GET['data2']);

			//Timestamp data inizio ricerca
			$dataInizio = mktime(0,0,0,$tmpInizio[1],$tmpInizio[0],$tmpInizio[2]);

			//Timestamp data inizio ricerca
			$dataFine = mktime(0,0,0,$tmpFine[1],$tmpFine[0],$tmpFine[2]);

			//Effettuata la ricerca dei libri inseriti in un intervallo di date calcolando il timestamp delle date di inizio e fine intervallo 
			//e quello della data di inserimento del libro.
			//Il timestamp (mktime) calcola in secondi il tempo trascorso dal 1 gennaio 1970 
			//quindi se la data è compresa tra i timestamp di inzio e fine intervallo allora la data sarà all'interno dell'intervallo
			foreach($dati['libro'] as $book)
			{
				$tmp = explode("/", $book['dataArchiviazione']);
				$dataArch = mktime(0,0,0,$tmp[1], $tmp[0], $tmp[2]);

				if($dataArch <= $dataFine && $dataArch >= $dataInizio)
					array_push($arr, $book['titolo']);
			}
			
			deliver_response(200,"date    ", $arr);
			break;

		case '4':
			//Conversione file JSON in array associativo
			$dati = conversioneDati('../FileJSON/Libri.json');
			$user = conversioneDati('../FileJSON/Utenti.json');
			$carrelli = conversioneDati('../FileJSON/Carrelli.json');
			$libriCarr = conversioneDati('../FileJSON/LibriCarrello.json');

			$idCarr = $_GET['codice'];
			$utente="";
			$tit = array();
			$nCopie = "";

			foreach($carrelli['carrello'] as $carr)
			{
				if($idCarr ==  $carr["id"])
					$utente = $carr["utente"];
			}

			foreach($libriCarr['librocarrello'] as $associazione)
			{
				if($associazione['carrello'] === $idCarr);
				{
					foreach($dati['libro'] as $book)
					{
						if($book['id'] === $associazione['libro'])
							array_push($tit, array('titolo'=>$book['titolo'], 'nCopie' => $associazione['nCopie']));
					}

					//$nCopie = $associazione['nCopie'];
				}
			}

			$arr = array();
			array_push($arr, array('utente'=>$utente, 'libri' => $tit));

			deliver_response(200,"carrello", $arr);
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
	//Funzione per invio della risposta al client
	function deliver_response($status, $status_message, $data)
	{
		header("HTTP/1.1 $status $status_message");
		
		$response ['status']=$status;
		$response['status_message']=$status_message;
		$response['data']=$data;
		
		$json_response=json_encode($response);
		echo $json_response;
	}
	
	//Funzione per la conversione da file JSON a array associativo
	function conversioneDati($json)
	{
		$str = file_get_contents($json);
		$dati = json_decode($str, true); 
		
		return $dati;
	}
?>