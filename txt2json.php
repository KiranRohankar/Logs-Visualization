<?php

function prettyPrint( $json )
{
    $result = '';
    $level = 0;
    $in_quotes = false;
    $in_escape = false;
    $ends_line_level = NULL;
    $json_length = strlen( $json );

    for( $i = 0; $i < $json_length; $i++ ) {
        $char = $json[$i];
        $new_line_level = NULL;
        $post = "";
        if( $ends_line_level !== NULL ) {
            $new_line_level = $ends_line_level;
            $ends_line_level = NULL;
        }
        if ( $in_escape ) {
            $in_escape = false;
        } else if( $char === '"' ) {
            $in_quotes = !$in_quotes;
        } else if( ! $in_quotes ) {
            switch( $char ) {
                case '}': case ']':
                    $level--;
                    $ends_line_level = NULL;
                    $new_line_level = $level;
                    break;

                case '{': case '[':
                    $level++;
                case ',':
                    $ends_line_level = $level;
                    break;

                case ':':
                    $post = " ";
                    break;

                case " ": case "\t": case "\n": case "\r":
                    $char = "";
                    $ends_line_level = $new_line_level;
                    $new_line_level = NULL;
                    break;
            }
        } else if ( $char === '\\' ) {
            $in_escape = true;
        }
        if( $new_line_level !== NULL ) {
            $result .= "\n".str_repeat( "\t", $new_line_level );
        }
        $result .= $char.$post;
    }

    return $result;
}

date_default_timezone_set('America/New_York');

$dbname='apache_log';
$dte=date('Y-m-d_g a');
 $backupfile = $dbname.$dte.'.json';



$txt_file    = file_get_contents('indivisual');
$rows        = explode("\n", $txt_file);
array_shift($rows);

foreach($rows as $row => $data)
{

 preg_match("([\w+-?]*)", $data, $match1);
 preg_match("(\d{2}\/\w+\/\d{4})", $data, $match2);


$samp["Machine"]=$match1[0];
$samp["Date"]=$match2[0];
$text2json[]=$samp;
}

 
 $json2=json_encode($text2json);

$jsons =prettyPrint($json2);

$fp1 = fopen($backupfile,"wb");
 //1Write the json info
  fwrite($fp1,$jsons);
 
    //Close the database connection
 fclose($fp1);


?>