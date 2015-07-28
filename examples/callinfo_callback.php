<?php

/*
 * Information about incoming calls
 * Callback link for your site
 */

if (isset($_GET['zd_echo'])) exit($_GET['zd_echo']);

if ($_POST) {
    $callerId = $_POST['caller_id']; // number of calling party;
    $calledDid = $_POST['called_did']; // number of called party;
    $callstart = $_POST['callstart']; // start time of call

    /*
    // For each request you can easily change work scenario for current call by sending in response on information:
    echo json_encode(array(
        'redirect' => 1,
        'caller_name' => 'TestName'
    ));
    exit();
    */
}