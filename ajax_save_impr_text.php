<?php


/**************************************************************
Call: ajax_save_impr_text.php
Save Improved Annotation
***************************************************************/

require_once 'inc/session_utility.php';

$textid = $_POST['id'] + 0;
$elem = $_POST['elem'];
$stringdata = stripTheSlashesIfNeeded($_POST['data']);
$data = json_decode($stringdata);

$val = $data->{$elem};
if(substr($elem, 0, 2) == "rg") {
    if($val == "") { $val = $data->{'tx' . substr($elem, 2)}; 
    } 
}
$line = substr($elem, 2) + 0;

// Save data
$success = "NOTOK";
$ann = get_first_value("select TxAnnotatedText as value from " . $tbpref . "texts where TxID = " . $textid);
$items = preg_split('/[\n]/u', $ann);
if (count($items) >= $line) {
    $vals = preg_split('/[\t]/u', $items[$line-1]);
    if ($vals[0] > -1 && count($vals) == 4) {
        $vals[3] = $val;
        $items[$line-1] = implode("\t", $vals);
        $dummy = runsql(
            'update ' . $tbpref . 'texts set ' .
            'TxAnnotatedText = ' . convert_string_to_sqlsyntax(implode("\n", $items)) . ' where TxID = ' . $textid, ""
        );
        $success = "OK";
    }
}

// error_log ("ajax_save_impr_text / " . $success . " / " . $stringdata);

echo $success;

?>
