<?php

function remove_html_comments($text)
{

    $text = preg_replace('/<!--.*-->/Us','',$text);
    $text = preg_replace('/^.*-->/Us', '', $text);
    $text = preg_replace('/<!.*$/Us', '', $text);

    return $text;
}

function nouns_declension($num, $str_one, $str_two, $str_five)
{
    $num = $num%100;
    if ($num >= 5 && $num <= 20) {
        return $str_five;
    }

	$num = $num%10;
    if ($num == 1) {
        return $str_one;
    }

    if ($num >= 2 && $num <= 4){
        return $str_two;
	}

	return $str_five;
}

function make_links_clickable($text){
    return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1">$1</a>', $text);
}
