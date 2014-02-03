<?php

class tag
{

	public static function replace($tag, $replace, $subject)
	{

		return str_replace("[". $tag ."]", $replace, $subject);

	}
	public static function replaceLanguage($locale, $subject)
	{

		$file = BASE . "variables/languages/".$locale.".php";

		if(file_exists($file))
		{
			$tags = require $file;
			foreach ($tags as $key => $value) 
			{
				$subject = self::replace($key , $value, $subject);
			}
		}
		return $subject;

	}
	public static function loop($looptag, $array, $subject)
	{

		$looptag_begin = "[". $looptag ."]";
		$looptag_end   = "[/". $looptag ."]";

		//Get context length start and end
		$context_start = strpos($subject, $looptag_begin) + strlen($looptag_begin);
		$context_end   = strpos($subject, $looptag_end);

		$context = substr($subject, $context_start, $context_end - $context_start);
		

		$add = "";
		//loop items
		foreach ($array as $item) 
		{
			$tmp = $context;
			//loop tag according to the array indexes
			foreach ($item as $tag => $replace)
			{
				$tmp = self::replace($tag, $replace, $tmp);
			}
			//concat
			$add .= $tmp;
		}


		//Set the replacement positions
		$tag_start = strpos($subject, $looptag_begin);
		$tag_end   = strpos($subject, $looptag_end) + strlen($looptag_end);
		//finaly replace the new code in the subject
		return substr_replace($subject, $add, $tag_start, $tag_end - $tag_start);

	}
	public static function replaceByRegex($regex, $replace, $subject)
	{

		preg_match_all("^\[" . $regex . "\]^", $subject, $out);
		foreach ($out[0] as $tag) 
		{
			//Strip brackets and replace
			$subject = self::replace(substr($tag, 1, -1), $replace, $subject);
		}
		return $subject;

	}
	public static function loopLogicalStatements($subject)
	{
		//var_dump($subject);
		preg_match_all("^\[if\((.*?)\)\]^", $subject, $out);
		foreach ($out[0] as $key => $ifstatement) 
		{
			$var = $out[1][$key];
			//Get statement blog
			$statement_start = strpos($subject ,$ifstatement);
			$statement_end   = strpos($subject, "[/if]", $statement_start);
			
			$statement_block   = substr($subject, $statement_start + strlen($ifstatement), $statement_end - $statement_start - strlen($ifstatement));
			$outputs = explode("[else]", $statement_block);
			$r = @constant(strtoupper($var)) ? $outputs[0] : $outputs[1];

			$subject = substr_replace($subject, $r, $statement_start, $statement_end - $statement_start + strlen("[/if]"));
		}	
		return $subject;
	}
}