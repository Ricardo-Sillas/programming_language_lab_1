<?php // index.php

define('STRATEGY', 'strategy');

$board = array(
    array(0, 0, 0, 0, 0, 0, 0),
    array(0, 0, 0, 0, 0, 0, 0),
    array(0, 0, 0, 0, 0, 0, 0),
    array(0, 0, 0, 0, 0, 0, 0),
    array(0, 0, 0, 0, 0, 0, 0),
    array(0, 0, 0, 0, 0, 0, 0)
);

$strategies = array("Smart", "Random"); // supported strategies

if(!isset($_GET[STRATEGY])) {
    echo json_encode(array("response"=>false, "reason"=>"Strategy Not Specified"));
    exit;
}

$strategy = $_GET['strategy'];
if(!in_array($strategy,$strategies)) {
    echo json_encode(array("response"=>false, "reason"=>"Strategy Unknown"));
    exit;
}

createFile();

// Creates files for the game and for the strategy.
function createFile() {
    global $pid;
    global $board;
    global $strategy;
    $pid = uniqid();
    // For the board
    $fp = fopen("../data/$pid.txt", 'w');
    fputs($fp,json_encode($board));
    fclose($fp);
    // For the strategy
    $fs = fopen("../strategy/strategy.txt", 'w');
    fputs($fs,$strategy);
    fclose($fs);
    echo json_encode(array("response"=>true, "pid"=>$pid));
}