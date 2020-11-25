<?php

function liveSmartInsertUser($username, $password, $email, $lsRepUrl) {

    $posts = http_build_query(array('type' => 'addagent', 'username' => $username, 'password' => $password, 'firstName' => $username, 'lastName' => $username, 'email' => $email, 'tenant' => $username));
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $lsRepUrl . 'server/script.php',
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => $posts,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST=> false,
        CURLOPT_TIMEOUT => 2
    ));

    $response = @curl_exec($ch);

    if (curl_errno($ch) > 0) {
        curl_close($ch);
        return false;
    } else {

        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($responseCode !== 200) {
            curl_close($ch);
            return false;
        }
        curl_close($ch);
        $posts = http_build_query(array('type' => 'addroom', 'lsRepUrl' => $lsRepUrl, 'agentId' => $username, 'agentName' => $username, 'visitorName' => '', 'agentShortUrl' => $username . '_a', 'visitorShortUrl' => $username, 'is_active' => true));
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $lsRepUrl . 'server/script.php',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $posts,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST=> false,
            CURLOPT_TIMEOUT => 2
        ));

        $response = @curl_exec($ch);

        if (curl_errno($ch) > 0) {
            curl_close($ch);
            return false;
        } else {

            $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($responseCode !== 200) {
                curl_close($ch);
                return false;
            }
            curl_close($ch);
            return true;
        }
    }
}

function liveSmartCheckUser($username, $password, $email, $lsRepUrl) {

    $posts = http_build_query(array('type' => 'loginagent', 'username' => $username, 'password' => $password));
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $lsRepUrl . 'server/script.php',
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => $posts,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST=> false,
        CURLOPT_TIMEOUT => 2
    ));

    $response = curl_exec($ch);
    curl_close($ch);
    if (!$response) {
        liveSmartInsertUser($username, $password, $email, $lsRepUrl);
    }
}

function liveSmartDeleteUser($username, $lsRepUrl) {

    $posts = http_build_query(array('type' => 'deleteagentbyusername', 'username' => $username));
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $lsRepUrl . 'server/script.php',
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => $posts,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST=> false,
        CURLOPT_TIMEOUT => 2
    ));

    curl_exec($ch);
    curl_close($ch);
    $posts = http_build_query(array('type' => 'deleteroombyagent', 'agentId' => $username));
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $lsRepUrl . 'server/script.php',
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => $posts,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST=> false,
        CURLOPT_TIMEOUT => 2
    ));

    curl_exec($ch);
    curl_close($ch);
}
