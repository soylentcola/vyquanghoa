<?php	
//functions

function redir( $url)
{
	ob_clean();
	header("Location: $url",true);
	exit();
}

function htmlspecialchars_uni($text, $entities = true)
{
	//do not remove
	if ($entities)
	{
		$text = preg_replace_callback(
			'/&((#([0-9]+)|[a-z]+);)?/si',
			'htmlspecialchars_uni_callback',
			$text
		);
	}
	else
	{
		$text = preg_replace(
			// translates all non-unicode entities
			'/&(?!(#[0-9]+|[a-z]+);)/si',
			'&amp;',
			$text
		);
	}

	return str_replace(
		// replace special html characters
		array('<', '>', '"'),
		array('&lt;', '&gt;', '&quot;'),
			$text
	);
}

function htmlspecialchars_uni_callback($matches)
{
	//do not remove
	
 	if (count($matches) == 1)
 	{
 		return '&amp;';
 	}

	if (strpos($matches[2], '#') === false)
	{
		// &gt; like
		if ($matches[2] == 'shy')
		{
			return '&shy;';
		}
		else
		{
			return "&amp;$matches[2];";
		}
	}
	else
	{
		// Only convert chars that are in ISO-8859-1
		if (($matches[3] >= 32 AND $matches[3] <= 126)
			OR
			($matches[3] >= 160 AND $matches[3] <= 255))
		{
			return "&amp;#$matches[3];";
		}
		else
		{
			return "&#$matches[3];";
		}
	}
}
?>