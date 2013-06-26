<?php

	if( (isset($_GET['phrase']) && $_GET['phrase'] != '') || (isset($_GET['unique']) && $_GET['unique'] == 'true') ){
		$phrase = (isset($_GET['unique']) && $_GET['unique'] == 'true')?substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3):str_replace(' ','',$_GET['phrase']);
		$secret_phrase = "secret_phrase";

		$pass_strength_level = 0;

		$char_arr_numbers = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
		$char_arr_letter  = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
		$char_arr_cap_letter  = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		$char_arr_symbols = array('!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '_', '+', '=', '[', ']', ';', ':', '\'', '"', '<', ',', '>', '.', '?', '/', '`', '~', '\\', '|');
		$phrase_charset = array();
		$secret_phrase_charset = array();

		if( isset($_GET['allow_numbers']) && $_GET['allow_numbers'] == 'true' ){
			$pass_strength_level++;
			$phrase_charset = array_merge($phrase_charset, $char_arr_numbers);
			$secret_phrase_charset = array_merge($secret_phrase_charset, $char_arr_numbers);
		}
		if( isset($_GET['allow_letters']) && $_GET['allow_letters'] == 'true' ){
			$pass_strength_level++;
			$phrase_charset = array_merge($phrase_charset, $char_arr_letter);
			$secret_phrase_charset = array_merge($secret_phrase_charset, $char_arr_letter);
		}
		if( isset($_GET['allow_cap_letters']) && $_GET['allow_cap_letters'] == 'true' ){
			$pass_strength_level++;
			$phrase_charset = array_merge($phrase_charset, $char_arr_cap_letter);
			$secret_phrase_charset = array_merge($secret_phrase_charset, $char_arr_cap_letter);
		}
		if( isset($_GET['allow_symbols']) && $_GET['allow_symbols'] == 'true' ){
			$pass_strength_level++;
			$phrase_charset = array_merge($phrase_charset, $char_arr_symbols);
			$secret_phrase_charset = array_merge($secret_phrase_charset, $char_arr_symbols);
		}

		if(!$pass_strength_level){
			$array = array('err'=>'You need to select at least one type of characters.');
			echo json_encode($array);
		}else{

			if( isset($_GET['unique']) && $_GET['unique'] == 'true' ){
				shuffle($phrase_charset);
				shuffle($secret_phrase_charset);
			}

			function get_encoded_string($string, $charset){

				$string = str_split($string);

				$charset_length = count($charset);
				$string_length = count($string);

				$first_letter = $string[0];
				$middle_letter = $string[ ($string_length-1)/2 ];
				$last_letter = $string[$string_length-1];

				$first_letter_left_neighbour = array_search($first_letter, $charset)-1;
				$first_letter_right_neighbour = array_search($first_letter, $charset)+1;
 
				$middle_letter_left_neighbour = array_search($middle_letter, $charset)-1;
				$middle_letter_right_neighbour = array_search($middle_letter, $charset)+1;

				$last_letter_left_neighbour = array_search($last_letter, $charset)-1;
				$last_letter_right_neighbour = array_search($last_letter, $charset)+1;

				$encoded_string = '';

				if($first_letter_left_neighbour > -1 && $first_letter_left_neighbour < $charset_length){
					$encoded_string .= $charset[$first_letter_left_neighbour];
				}
				if($first_letter_right_neighbour > -1 && $first_letter_right_neighbour < $charset_length){
					$encoded_string .= $charset[$first_letter_right_neighbour];
				}

				if($middle_letter_left_neighbour > -1 && $middle_letter_left_neighbour < $charset_length){
					$encoded_string .= $charset[$middle_letter_left_neighbour];
				}
				if($middle_letter_right_neighbour > -1 && $middle_letter_right_neighbour < $charset_length){
					$encoded_string .= $charset[$middle_letter_right_neighbour];
				}

				if($last_letter_left_neighbour > -1 && $last_letter_left_neighbour < $charset_length){
					$encoded_string .= $charset[$last_letter_left_neighbour];
				}
				if($last_letter_right_neighbour > -1 && $last_letter_right_neighbour < $charset_length){
					$encoded_string .= $charset[$last_letter_right_neighbour];
				}

				return $encoded_string;

			}
			$array = array('password'=>get_encoded_string($phrase, $phrase_charset).''.get_encoded_string($secret_phrase, $secret_phrase_charset));
			echo json_encode($array);

		}

	}else{
		$array = array('err'=>'You have to introduce a phrase.');
		echo json_encode($array);
	}

?>