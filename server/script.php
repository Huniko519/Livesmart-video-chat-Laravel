<?php

/**
 * REST API for managing agents, users, rooms and chats in LiveSmart Video Chat
 *
 * @author  LiveSmart <info@new-dev.com>
 *
 * @since 1.0
 *
 */
session_start();
include_once 'connect.php';

/**
 * Method to check login of an user. Returns 200 code for successful login.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $username
 * @param type $pass
 * @return boolean|int
 */
function checkLogin($username, $pass) {
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users WHERE username = ? AND password = ? AND is_blocked = 0');
        $stmt->execute([$username, md5($pass)]);
        $user = $stmt->fetch();

        if ($user) {
            return 200;
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Method to check the token in a broadcasting session.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $token
 * @param type $roomId
 * @param type $isAdmin
 * @return boolean
 */
function checkLoginToken($token, $roomId, $isAdmin = false) {
    global $dbPrefix, $pdo;
    try {
        if ($isAdmin) {
            $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents WHERE token = ? AND roomId = ?');
            $stmt->execute([$token, $roomId]);
        } else {
            $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users WHERE token = ? AND roomId = ? AND is_blocked = 0');
            $stmt->execute([$token, $roomId]);
        }

        $user = $stmt->fetch();

        if ($user) {
            return json_encode($user);
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Method to add a room. 
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $agent
 * @param type $visitor
 * @param type $agenturl
 * @param type $visitorurl
 * @param type $pass
 * @param type $session
 * @param type $datetime
 * @param type $duration
 * @param type $shortagenturl
 * @param type $shortvisitorurl
 * @param type $agentId
 * @param type $agenturl_broadcast
 * @param type $visitorurl_broadcast
 * @param type $shortagenturl_broadcast
 * @param type $shortvisitorurl_broadcast
 * @param type $is_active
 * @return string|int
 */
function insertScheduling($agent, $visitor, $agenturl, $visitorurl, $pass, $session, $datetime, $duration, $shortagenturl, $shortvisitorurl, $agentId = null, $agenturl_broadcast = null, $visitorurl_broadcast = null, $shortagenturl_broadcast = null, $shortvisitorurl_broadcast = null, $is_active = true) {
    global $dbPrefix, $pdo;

    $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'rooms WHERE roomId = ? or shortagenturl = ? or shortvisitorurl = ?');
    $stmt->execute([$session, $shortagenturl, $shortvisitorurl]);
    $userName = $stmt->fetch();
    if ($userName) {
        return false;
    }
    $is_active = ($is_active == 'true') ? 1 : 0;

    try {
        $sql = "INSERT INTO " . $dbPrefix . "rooms (agent, visitor, agenturl, visitorurl, password, roomId, datetime, duration, shortagenturl, shortvisitorurl, agent_id, agenturl_broadcast, visitorurl_broadcast, shortagenturl_broadcast, shortvisitorurl_broadcast, is_active) "
                . "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$agent, $visitor, $agenturl, $visitorurl, md5($pass), $session, $datetime, $duration, $shortagenturl, $shortvisitorurl, $agentId, $agenturl_broadcast, $visitorurl_broadcast, $shortagenturl_broadcast, $shortvisitorurl_broadcast, (int) $is_active]);
        return 200;
    } catch (Exception $e) {
        return 'Error';
    }
}

/**
 * Add a room and generate URLs from PHP directly. 
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $lsRepUrl
 * @param type $agentId
 * @param type $roomId
 * @param type $agentName
 * @param type $visitorName
 * @param type $agentShortUrl
 * @param type $visitorShortUrl
 * @param type $password
 * @param type $config
 * @param type $dateTime
 * @param type $duration
 * @param type $disableVideo
 * @param type $disableAudio
 * @param type $disableScreenShare
 * @param type $disableWhiteboard
 * @param type $disableTransfer
 * @param type $is_active
 * @return boolean|string|int
 */
function addRoom($lsRepUrl, $agentId = null, $roomId = null, $agentName = null, $visitorName = null, $agentShortUrl = null, $visitorShortUrl = null, $password = null, $config = 'config.json', $dateTime = null, $duration = null, $disableVideo = false, $disableAudio = false, $disableScreenShare = false, $disableWhiteboard = false, $disableTransfer = false, $is_active = true) {
    global $dbPrefix, $pdo;

    $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'rooms WHERE roomId = ? or shortagenturl = ? or shortvisitorurl = ?');
    $stmt->execute([$roomId, $agentShortUrl, $visitorShortUrl]);
    $userName = $stmt->fetch();
    if ($userName) {
        return false;
    }
    $is_active = ($is_active == 'true') ? 1 : 0;

    try {

        function generateRand($length) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }

            return $randomString;
        }

        $roomId = ($roomId) ? $roomId : generateRand(10);
        $str = [];
        $str['lsRepUrl'] = $lsRepUrl;

        if ($agentName) {
            $str['names'] = $agentName;
        }
        if ($visitorName) {
            $str['visitorName'] = $visitorName;
        }
        if ($config) {
            $str['config'] = $config;
        }
        if ($agentId) {
            $str['agentId'] = $agentId;
        }
        if ($agentId) {
            $str['agentId'] = $agentId;
        }


        if ($agentShortUrl) {
            $agentShortUrl = $agentShortUrl;
            $agentShortUrl_b = $agentShortUrl . '_b';
        } else {
            $agentShortUrl = generateRand(6);
            $agentShortUrl_b = generateRand(6);
        }
        if ($visitorShortUrl) {
            $visitorShortUrl = $visitorShortUrl;
            $visitorShortUrl_b = $visitorShortUrl . '_b';
        } else {
            $visitorShortUrl = generateRand(6);
            $visitorShortUrl_b = generateRand(6);
        }
        if ($dateTime) {
            $str['datetime'] = $dateTime;
        }
        if ($duration) {
            $str['duration'] = $duration;
        }
        if ($disableVideo) {
            $str['disableVideo'] = $disableVideo;
        }
        if ($disableAudio) {
            $str['disableAudio'] = $disableAudio;
        }
        if ($disableWhiteboard) {
            $str['disableWhiteboard'] = $disableWhiteboard;
        }
        if ($disableScreenShare) {
            $str['disableScreenShare'] = $disableScreenShare;
        }
        if ($disableTransfer) {
            $str['disableTransfer'] = $disableTransfer;
        }
        $encodedString = base64_encode(json_encode($str));


        $visitorUrl = $lsRepUrl . 'pages/r.html?room=' . $roomId . '&p=' . $encodedString;
        $viewerBroadcastLink = $lsRepUrl . 'pages/r.html?room=' . $roomId . '&p=' . $encodedString . '&broadcast=1';

        if ($password) {
            $str['pass'] = $password;
        }
        if (isset($str['vistorName'])) {
            unset($str['vistorName']);
        }
        $str['isAdmin'] = 1;
        $encodedString = base64_encode(json_encode($str));
        $agentUrl = $lsRepUrl . 'pages/r.html?room=' . $roomId . '&p=' . $encodedString . '&isAdmin=1';
        $agentBroadcastUrl = $lsRepUrl . 'pages/r.html?room=' . $roomId . '&p=' . $encodedString . '&isAdmin=1&broadcast=1';


        $sql = "INSERT INTO " . $dbPrefix . "rooms (agent, visitor, agenturl, visitorurl, password, roomId, datetime, duration, shortagenturl, shortvisitorurl, agent_id, agenturl_broadcast, visitorurl_broadcast, shortagenturl_broadcast, shortvisitorurl_broadcast, is_active) "
                . "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$agentName, $visitorName, $agentUrl, $visitorUrl, md5($password), $roomId, $dateTime, $duration, $agentShortUrl, $visitorShortUrl, $agentId, $agentBroadcastUrl, $viewerBroadcastLink, $agentShortUrl_b, $visitorShortUrl_b, (int) $is_active]);
        $id = $pdo->lastInsertId();
        return $id;
    } catch (Exception $e) {
        return 'Error';
    }
}

/**
 * Method to edit a room.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $roomId
 * @param type $agent
 * @param type $visitor
 * @param type $agenturl
 * @param type $visitorurl
 * @param type $pass
 * @param type $session
 * @param type $datetime
 * @param type $duration
 * @param type $shortagenturl
 * @param type $shortvisitorurl
 * @param type $agentId
 * @param type $agenturl_broadcast
 * @param type $visitorurl_broadcast
 * @param type $shortagenturl_broadcast
 * @param type $shortvisitorurl_broadcast
 * @param type $is_active
 * @return int
 */
function editRoom($roomId, $agent, $visitor, $agenturl, $visitorurl, $pass, $session, $datetime, $duration, $shortagenturl, $shortvisitorurl, $agentId = null, $agenturl_broadcast = null, $visitorurl_broadcast = null, $shortagenturl_broadcast = null, $shortvisitorurl_broadcast = null, $is_active = 1) {
    global $dbPrefix, $pdo;
    try {
        $is_active = ($is_active == 'true') ? 1 : 0;
        $sql = "UPDATE " . $dbPrefix . "rooms set agent=?, visitor=?, agenturl=?, visitorurl=?, password=?, roomId=?, datetime=?, duration=?, shortagenturl=?, shortvisitorurl=?, agent_id=?, agenturl_broadcast=?, visitorurl_broadcast=?, shortagenturl_broadcast=?, shortvisitorurl_broadcast=?, is_active=?"
                . " WHERE room_id = ?;";
        $pdo->prepare($sql)->execute([$agent, $visitor, $agenturl, $visitorurl, md5($pass), $session, $datetime, $duration, $shortagenturl, $shortvisitorurl, $agentId, $agenturl_broadcast, $visitorurl_broadcast, $shortagenturl_broadcast, $shortvisitorurl_broadcast, (int) $is_active, $roomId]);
        return 200;
    } catch (Exception $e) {
        return 'Error ' . $e->getMessage();
    }
}

/**
 * Update room state
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $roomId
 * @param type $is_active
 * @return int
 */
function updateRoomState($roomId, $is_active) {
    global $dbPrefix, $pdo;
    try {
        $is_active = ($is_active == 'true') ? 1 : 0;
        $sql = "UPDATE " . $dbPrefix . "rooms set is_active=?"
                . " WHERE room_id = ?;";
        $pdo->prepare($sql)->execute([(int) $is_active, $roomId]);
        return 200;
    } catch (Exception $e) {
        return 'Error ' . $e->getMessage();
    }
}

/**
 * Method to add a recording after video session ends.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $roomId
 * @param type $file
 * @param type $agentId
 * @return int
 */
function insertRecording($roomId, $file, $agentId) {
    global $dbPrefix, $pdo;
    try {

        $sql = "INSERT INTO " . $dbPrefix . "recordings (`room_id`, `filename`, `agent_id`, `date_created`) "
                . "VALUES (?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$roomId, $file, $agentId, date("Y-m-d H:i:s")]);
        return 200;
    } catch (Exception $e) {
        return 'Error ' . $e->getMessage();
    }
}

/**
 * Method to delete a recording from the database and delete the file.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $recordingId
 * @return boolean
 */
function deleteRecording($recordingId) {
    global $dbPrefix, $pdo;
    try {

        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'recordings WHERE recording_id = ?');
        $stmt->execute([$recordingId]);
        $rec = $stmt->fetch();

        if ($rec) {
            unlink('../server/recordings/' . $rec['filename']);
        }

        $array = [$recordingId];
        $sql = 'DELETE FROM ' . $dbPrefix . 'recordings WHERE recording_id = ?';
        $pdo->prepare($sql)->execute($array);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Returns all recordings.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @return type
 */
function getRecordings() {
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'recordings order by date_created desc');
        $stmt->execute();
        $rows = array();
        while ($r = $stmt->fetch()) {
            if ($r['filename']) {
                if (file_exists('recordings/' . $r['filename'])) {
                    $rows[] = $r;
                }
                if (file_exists('recordings/' . $r['filename'] . '.mp4')) {
                    $r['filename'] = $r['filename'] . '.mp4';
                    $rows[] = $r;
                }
            }
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

/**
 * Adds a chat message.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $roomId
 * @param type $message
 * @param type $agent
 * @param type $from
 * @param type $participants
 * @param type $agentId
 * @param type $system
 * @param type $avatar
 * @param type $datetime
 * @return string|int
 */
function insertChat($roomId, $message, $agent, $from, $participants, $agentId = null, $system = null, $avatar = null, $datetime = null) {
    global $dbPrefix, $pdo;
    try {

        $sql = "INSERT INTO " . $dbPrefix . "chats (`room_id`, `message`, `agent`, `agent_id`, `from`, `date_created`, `participants`, `system`, `avatar`) "
                . "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$roomId, $message, $agent, $agentId, $from, date("Y-m-d H:i:s", strtotime($datetime)), $participants, $system, $avatar]);
        return 200;
    } catch (Exception $e) {
        return 'Error';
    }
}

/**
 * Return chat messages by roomId and participants.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $roomId
 * @param type $sessionId
 * @param type $agentId
 * @return boolean
 */
function getChat($roomId, $sessionId, $agentId = null) {

    global $dbPrefix, $pdo;
    try {

        $additional = '';
        $array = [$roomId, "%$sessionId%"];
        if ($agentId && $agentId != 'false') {
            $additional = ' AND agent_id = ?';
            $array = [$roomId, $agentId, "%$sessionId%"];
        }
        $stmt = $pdo->prepare("SELECT * FROM " . $dbPrefix . "chats WHERE (`room_id`= ? or `room_id` = 'dashboard') $additional and participants like ? order by date_created asc");
        $stmt->execute($array);
        $rows = array();
        while ($r = $stmt->fetch()) {
            $r['date_created'] = strtotime($r['date_created']);
            $rows[] = $r;
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Returns all information about agent by tenant
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $tenant
 * @return boolean
 */
function getAgent($tenant) {

    global $dbPrefix, $pdo;
    try {
        $array = [$tenant];
        $stmt = $pdo->prepare("SELECT * FROM " . $dbPrefix . "agents WHERE `tenant`= ?");
        $stmt->execute($array);
        $user = $stmt->fetch();

        if ($user) {
            return json_encode($user);
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Returns agent info by agent_id.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $id
 * @return boolean|type
 */
function getAdmin($id) {

    global $dbPrefix, $pdo;
    try {
        $array = [$id];
        $stmt = $pdo->prepare("SELECT * FROM " . $dbPrefix . "agents WHERE `agent_id`= ?");
        $stmt->execute($array);
        $user = $stmt->fetch();

        if ($user) {
            return json_encode($user);
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Returns user info by user_id
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $id
 * @return boolean|type
 */
function getUser($id) {

    global $dbPrefix, $pdo;
    try {
        $array = [$id];
        $stmt = $pdo->prepare("SELECT * FROM " . $dbPrefix . "users WHERE `user_id`= ?");
        $stmt->execute($array);
        $user = $stmt->fetch();

        if ($user) {
            return json_encode($user);
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Returns room information by room identifier
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $roomId
 * @return boolean|type
 */
function getRoom($roomId) {

    global $dbPrefix, $pdo;
    try {
        $array = [$roomId];
        $stmt = $pdo->prepare("SELECT * FROM " . $dbPrefix . "rooms WHERE `roomId`= ? AND `is_active` = 1");
        $stmt->execute($array);
        $room = $stmt->fetch();
        if ($room) {
            return json_encode($room);
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Returns room information by room_id
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $roomId
 * @return boolean|type
 */
function getRoomById($roomId) {

    global $dbPrefix, $pdo;
    try {
        $array = [$roomId];
        $stmt = $pdo->prepare("SELECT * FROM " . $dbPrefix . "rooms WHERE `room_id`= ? AND `is_active` = 1");
        $stmt->execute($array);
        $room = $stmt->fetch();
        if ($room) {
            return json_encode($room);
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Returns all rooms by agent_id
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $agentId
 * @return boolean|type
 */
function getRooms($agentId = false) {

    global $dbPrefix, $pdo;
    try {
        $additional = '';
        $array = [];
        if ($agentId && $agentId != 'false') {
            $additional = ' WHERE agent_id = ? ';
            $array = [$agentId];
        }
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'rooms ' . $additional . ' order by room_id desc');
        $stmt->execute($array);
        $rows = array();
        while ($r = $stmt->fetch()) {
            $rows[] = $r;
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Deletes a room by room_id and agent_id
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $roomId
 * @param type $agentId
 * @return boolean
 */
function deleteRoom($roomId, $agentId = false) {
    global $dbPrefix, $pdo;
    try {
        $additional = '';
        $array = [$roomId];
        if ($agentId && $agentId != 'false') {
            $additional = ' AND agent_id = ?';
            $array = [$roomId, $agentId];
        }
        $sql = 'DELETE FROM ' . $dbPrefix . 'rooms WHERE room_id = ?' . $additional;
        $pdo->prepare($sql)->execute($array);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Deletes rooms by agent ID
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $roomId
 * @param type $agentId
 * @return boolean
 */
function deleteRoomByAgent($agentId) {
    global $dbPrefix, $pdo;
    try {
        $additional = '';
        $array = [$agentId];
        $sql = 'DELETE FROM ' . $dbPrefix . 'rooms WHERE agent_id = ?';
        $pdo->prepare($sql)->execute($array);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Returns all agents
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @return boolean|type
 */
function getAgents() {
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents order by agent_id desc');
        $stmt->execute();
        $rows = array();
        while ($r = $stmt->fetch()) {
            $rows[] = $r;
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Deletes an agent
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $agentId
 * @return boolean
 */
function deleteAgent($agentId) {
    global $dbPrefix, $pdo;
    try {

        $sql = 'DELETE FROM ' . $dbPrefix . 'agents WHERE agent_id = ?';
        $pdo->prepare($sql)->execute([$agentId]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Deletes an agent by username
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $userna,e
 * @return boolean
 */
function deleteAgentByUsername($username) {
    global $dbPrefix, $pdo;
    try {

        $sql = 'DELETE FROM ' . $dbPrefix . 'agents WHERE username = ?';
        $pdo->prepare($sql)->execute([$username]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Updates an agent
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $agentId
 * @param type $firstName
 * @param type $lastName
 * @param type $email
 * @param type $tenant
 * @param type $pass
 * @param type $usernamehidden
 * @return boolean
 */
function editAgent($agentId, $firstName, $lastName, $email, $tenant, $pass = null, $usernamehidden = null) {
    global $dbPrefix, $pdo;
    try {

        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents WHERE email = ? and agent_id <> ?');
        $stmt->execute([$email, $agentId]);
        $userName = $stmt->fetch();
        if ($userName) {
            return false;
        }

        $array = [$firstName, $lastName, $email, $tenant, $agentId];
        $additional = '';
        if ($pass) {
            $additional = ', password = ?';
            $array = [$firstName, $lastName, $email, $tenant, md5($pass), $agentId];
        }

        $sql = 'UPDATE ' . $dbPrefix . 'agents SET first_name=?, last_name=?, email=?, tenant=? ' . $additional . ' WHERE agent_id = ?';

        if ($_SESSION["username"] == $usernamehidden) {
            $_SESSION["agent"] = array('agent_id' => $agentId, 'first_name' => $firstName, 'last_name' => $lastName, 'tenant' => $tenant, 'email' => $email);
        }

        $pdo->prepare($sql)->execute($array);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Method to end the meeting and set it inactive.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $roomId
 * @param type $agentId
 * @return boolean
 */
function endMeeting($roomId, $agentId = null) {
    global $dbPrefix, $pdo;
    try {
        $additional = '';
        $array = [$roomId];
        if ($agentId) {
            $additional = ' AND agent_id = ?';
            $array = [$roomId, $agentId];
        }
//        $sql = 'UPDATE ' . $dbPrefix . 'rooms SET is_active=0 WHERE roomId = ? ' . $additional;
//        $pdo->prepare($sql)->execute($array);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Blocks an user by username. 
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $username
 * @return boolean
 */
function blockUser($username) {
    global $dbPrefix, $pdo;
    try {
        $sql = 'UPDATE ' . $dbPrefix . 'users SET is_blocked=1 WHERE username = ?';

        $pdo->prepare($sql)->execute(array($username));
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Updates an admin agent.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $agentId
 * @param type $firstName
 * @param type $lastName
 * @param type $email
 * @param type $tenant
 * @param type $pass
 * @return boolean
 */
function editAdmin($agentId, $firstName, $lastName, $email, $tenant, $pass = null) {
    global $dbPrefix, $pdo;
    try {

        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents WHERE email = ? and agent_id <> ?');
        $stmt->execute([$email, $agentId]);
        $userName = $stmt->fetch();
        if ($userName) {
            return false;
        }

        $array = [$firstName, $lastName, $email, $tenant, $agentId];
        $additional = '';
        if ($pass) {
            $additional = ', password = ?';
            $array = [$firstName, $lastName, $email, $tenant, md5($pass), $agentId];
        }

        $sql = 'UPDATE ' . $dbPrefix . 'agents SET first_name=?, last_name=?, email=?, tenant=? ' . $additional . ' WHERE agent_id = ?';
        $_SESSION["agent"] = array('agent_id' => $agentId, 'first_name' => $firstName, 'last_name' => $lastName, 'tenant' => $tenant, 'email' => $email);
        $pdo->prepare($sql)->execute($array);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Adds an agent.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $user
 * @param type $pass
 * @param type $firstName
 * @param type $lastName
 * @param type $email
 * @param type $tenant
 * @return boolean
 */
function addAgent($user, $pass, $firstName, $lastName, $email, $tenant) {
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents WHERE username = ? or email = ?');
        $stmt->execute([$user, $email]);
        $userName = $stmt->fetch();
        if ($userName) {
            return false;
        }

        $sql = 'INSERT INTO ' . $dbPrefix . 'agents (username, password, first_name, last_name, email, tenant) VALUES (?, ?, ?, ?, ?, ?)';
        $pdo->prepare($sql)->execute([$user, md5($pass), $firstName, $lastName, $email, $tenant]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Adds a feedback.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $sessionId
 * @param type $roomId
 * @param type $rate
 * @param type $text
 * @param type $userId
 * @return boolean
 */
function addFeedback($sessionId, $roomId, $rate, $text = '', $userId = '') {
    global $dbPrefix, $pdo;
    try {

        $sql = 'INSERT INTO ' . $dbPrefix . 'feedbacks (session_id, room_id, rate, text, user_id, date_added) VALUES (?, ?, ?, ?, ?, ?)';
        $pdo->prepare($sql)->execute([$sessionId, $roomId, $rate, $text, $userId, date("Y-m-d H:i:s")]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Returns all users.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @return boolean
 */
function getUsers() {

    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users order by user_id desc');
        $stmt->execute();
        $rows = array();
        while ($r = $stmt->fetch()) {
            $rows[] = $r;
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Deletes an user by user_id
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $userId
 * @return boolean
 */
function deleteUser($userId) {
    global $dbPrefix, $pdo;
    try {
        $sql = 'DELETE FROM ' . $dbPrefix . 'users WHERE user_id = ?';
        $pdo->prepare($sql)->execute([$userId]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Updates an user by user_id
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $userId
 * @param type $name
 * @param type $user
 * @param type $pass
 * @param type $blocked
 * @return boolean
 */
function editUser($userId, $name, $user, $pass, $blocked) {
    global $dbPrefix, $pdo;
    $additional = '';
    $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users WHERE username = ? and user_id <> ?');
    $stmt->execute([$user, $userId]);
    $userName = $stmt->fetch();
    if ($userName) {
        return false;
    }

    $array = [$user, $name, $blocked, $userId];
    if ($pass) {
        $additional = ', password = ?';
        $array = [$user, $name, $blocked, md5($pass), $userId];
    }
    try {
        $sql = 'UPDATE ' . $dbPrefix . 'users SET username=?, name=?, is_blocked=? ' . $additional . ' WHERE user_id = ?';
        $pdo->prepare($sql)->execute($array);
        return true;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

/**
 * Updates a configuration file properties.
 * 
 * @param type $postData
 * @param type $file
 * @return boolean
 */
function updateConfig($postData, $file) {

    try {

        $jsonString = file_get_contents('../config/' . $file . '.json');
        $data = json_decode($jsonString, true);

        foreach ($postData as $key => $value) {
            $val = explode('.', $key);
            if (isset($val[1]) && $value == 'true') {
                $data[$val[0]][$val[1]] = true;
            } else if (isset($val[1]) && $value == 'false') {
                $data[$val[0]][$val[1]] = false;
            } else if (isset($val[1]) && $value) {
                $data[$val[0]][$val[1]] = $value;
            } else if (isset($val[1])) {
                unset($data[$val[0]][$val[1]]);
            } else {
                $data[$key] = $value;
            }
        }
        $newJsonString = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        file_put_contents('../config/' . $file . '.json', $newJsonString);


        $currentVersion = file_get_contents('../pages/version.txt');
        $curNumber = explode('.', $currentVersion);
        if (count($curNumber) == 3) {
            $currentVersion = $currentVersion . '.1';
        } else {
            $currentVersion = $curNumber[0] . '.' . $curNumber[1] . '.' . $curNumber[2] . '.' . ((int) $curNumber[3] + 1);
        }
        file_put_contents('../pages/version.txt', $currentVersion);


        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Add a configuration file
 * 
 * @param type $fileName
 * @return boolean
 */
function addConfig($fileName) {

    try {

        $jsonString = file_get_contents('../config/config.json');
        file_put_contents('../config/' . $fileName . '.json', $jsonString);

        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Updates a locale file.
 * 
 * @param type $postData
 * @param type $file
 * @return boolean
 */
function updateLocale($postData, $file) {

    try {

        $jsonString = file_get_contents('../locales/' . $file . '.json');
        $data = json_decode($jsonString, true);

        foreach ($postData as $key => $value) {
            $val = explode('.', $key);
            if (isset($val[1]) && $value == 'true') {
                $data[$val[0]][$val[1]] = true;
            } else if (isset($val[1]) && $value == 'false') {
                $data[$val[0]][$val[1]] = false;
            } else if (isset($val[1]) && $value) {
                $data[$val[0]][$val[1]] = $value;
            } else if (isset($val[1])) {
                unset($data[$val[0]][$val[1]]);
            } else {
                $data[$key] = $value;
            }
        }
        $newJsonString = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        file_put_contents('../locales/' . $file . '.json', $newJsonString);


        $currentVersion = file_get_contents('../pages/version.txt');
        $curNumber = explode('.', $currentVersion);
        if (count($curNumber) == 3) {
            $currentVersion = $currentVersion . '.1';
        } else {
            $currentVersion = $curNumber[0] . '.' . $curNumber[1] . '.' . $curNumber[2] . '.' . ((int) $curNumber[3] + 1);
        }
        file_put_contents('../pages/version.txt', $currentVersion);


        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Adds a locale file.
 * 
 * @param type $fileName
 * @return boolean
 */
function addLocale($fileName) {

    try {

        $jsonString = file_get_contents('../locales/en_US.json');
        file_put_contents('../locales/' . $fileName . '.json', $jsonString);

        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Adds an user.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $user
 * @param type $name
 * @param type $pass
 * @param type $firstName
 * @param type $lastName
 * @return boolean
 */
function addUser($user, $name, $pass, $firstName = null, $lastName = null) {

    global $dbPrefix, $pdo;
    try {

        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'users WHERE username = ?');
        $stmt->execute([$user]);
        $userName = $stmt->fetch();
        if ($userName) {
            return false;
        }


        $sql = 'INSERT INTO ' . $dbPrefix . 'users (username, name, password, first_name, last_name) VALUES (?, ?, ?, ?, ?)';
        $pdo->prepare($sql)->execute([$user, $name, md5($pass), $firstName, $lastName]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Login method for an agent.
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $username
 * @param type $pass
 * @return boolean
 */
function loginAgent($username, $pass) {
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents WHERE username = ? AND password=?');
        $stmt->execute([$username, md5($pass)]);
        $user = $stmt->fetch();

        if ($user) {
            $_SESSION["tenant"] = ($user['is_master']) ? 'lsv_mastertenant' : $user['tenant'];
            $_SESSION["username"] = $user['username'];
            $_SESSION["agent"] = array('agent_id' => $user['agent_id'], 'first_name' => $user['first_name'], 'last_name' => $user['last_name'], 'tenant' => $user['tenant'], 'email' => $user['email']);
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Login method for admin agent
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $email
 * @param type $pass
 * @return boolean|int
 */
function loginAdmin($email, $pass) {
    global $dbPrefix, $pdo;
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents WHERE email = ? AND password = ?');
        $stmt->execute([$email, md5($pass)]);
        $user = $stmt->fetch();

        if ($user) {
            return 200;
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Returns all the chats for agent_id
 * 
 * @global type $dbPrefix
 * @global type $pdo
 * @param type $agentId
 * @return type
 */
function getChats($agentId = false) {
    global $dbPrefix, $pdo;
    try {
        $additional = '';
        $array = [];
        if ($agentId && $agentId != 'false') {
            $additional = ' WHERE agent_id = ? ';
            $array = [$agentId];
        }
        $stmt = $pdo->prepare('SELECT max(room_id) as room_id, max(date_created) as date_created, max(agent) as agent FROM ' . $dbPrefix . 'chats ' . $additional . ' group by room_id order by date_created desc');
        $stmt->execute($array);
        $rows = array();
        while ($r = $stmt->fetch()) {
            $stmt1 = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'chats where room_id=? order by date_created asc ');
            $stmt1->execute([$r['room_id']]);
            $rows1 = '<table>';
            while ($r1 = $stmt1->fetch()) {
                $rows1 .= '<tr><td><small>' . $r1['date_created'] . '</small></td><td>' . $r1['from'] . ': ' . $r1['message'] . '</td></tr>';
            }
            $rows1 .= '</table>';
            $r['messages'] = $rows1;
            $rows[] = $r;
        }
        return json_encode($rows);
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function getPassphrase() {
    global $pasPhrase;
    return $pasPhrase;
}

function getPk() {
    global $setVal;
    if (isset($setVal)) {
        return $setVal;
    } else {
        return '';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['type']) && $_POST['type'] == 'login') {
        echo checkLogin($_POST['email'], $_POST['password']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'logintoken') {
        echo checkLoginToken($_POST['token'], $_POST['roomId'], @$_POST['isAdmin']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'scheduling') {
        echo insertScheduling($_POST['agent'], $_POST['visitor'], $_POST['agenturl'], $_POST['visitorurl'], $_POST['password'], $_POST['session'], $_POST['datetime'], $_POST['duration'], $_POST['shortAgentUrl'], $_POST['shortVisitorUrl'], $_POST['agentId'], @$_POST['agenturl_broadcast'], @$_POST['visitorurl_broadcast'], @$_POST['shortAgentUrl_broadcast'], @$_POST['shortVisitorUrl_broadcast'], @$_POST['is_active']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'addroom') {
        echo addRoom($_POST['lsRepUrl'], @$_POST['agentId'], @$_POST['roomId'], @$_POST['agentName'], @$_POST['visitorName'], @$_POST['agentShortUrl'], @$_POST['visitorShortUrl'], @$_POST['password'], @$_POST['config'], @$_POST['dateTime'], @$_POST['duration'], @$_POST['disableVideo'], @$_POST['disableAudio'], @$_POST['disableScreenShare'], @$_POST['disableWhiteboard'], @$_POST['disableTransfer'], @$_POST['is_active']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'editroom') {
        echo editRoom($_POST['room_id'], $_POST['agent'], $_POST['visitor'], $_POST['agenturl'], $_POST['visitorurl'], $_POST['password'], $_POST['session'], $_POST['datetime'], $_POST['duration'], $_POST['shortAgentUrl'], $_POST['shortVisitorUrl'], $_POST['agentId'], @$_POST['agenturl_broadcast'], @$_POST['visitorurl_broadcast'], @$_POST['shortAgentUrl_broadcast'], @$_POST['shortVisitorUrl_broadcast'], @$_POST['is_active']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'changeroomstate') {
        echo updateRoomState($_POST['room_id'], $_POST['is_active']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'addchat') {
        echo insertChat($_POST['roomId'], $_POST['message'], $_POST['agent'], $_POST['from'], $_POST['participants'], @$_POST['agentId'], @$_POST['system'], @$_POST['avatar'], @$_POST['datetime']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getchat') {
        echo getChat($_POST['roomId'], $_POST['sessionId'], @$_POST['agentId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getrooms') {
        echo getRooms(@$_POST['agentId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getchats') {
        echo getChats(@$_POST['agentId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'deleteroom') {
        echo deleteRoom($_POST['roomId'], $_POST['agentId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'deleteroombyagent') {
        echo deleteRoomByAgent($_POST['agentId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getagents') {
        echo getAgents();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'deleteagent') {
        echo deleteAgent($_POST['agentId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'deleteagentbyusername') {
        echo deleteAgentByUsername($_POST['username']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'editagent') {
        echo editAgent($_POST['agentId'], $_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['tenant'], $_POST['password'], @$_POST['usernamehidden']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'editadmin') {
        echo editAdmin($_POST['agentId'], $_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['tenant'], $_POST['password']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'loginagent') {
        echo loginAgent($_POST['username'], $_POST['password']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'loginadmin') {
        echo loginAdmin($_POST['email'], $_POST['password']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'addagent') {
        echo addAgent($_POST['username'], $_POST['password'], $_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['tenant']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'addrecording') {
        echo insertRecording($_POST['roomId'], $_POST['filename'], $_POST['agentId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getrecordings') {
        echo getRecordings();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'deleterecording') {
        echo deleteRecording($_POST['recordingId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getusers') {
        echo getUsers();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'deleteuser') {
        echo deleteUser($_POST['userId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'edituser') {
        echo editUser($_POST['userId'], $_POST['name'], $_POST['username'], @$_POST['password'], @$_POST['isBlocked']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'adduser') {
        echo addUser($_POST['username'], $_POST['name'], $_POST['password'], @$_POST['firstName'], @$_POST['lastName']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'updateconfig') {
        echo updateConfig($_POST['data'], $_POST['fileName']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'addconfig') {
        echo addConfig($_POST['fileName']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'updatelocale') {
        echo updateLocale($_POST['data'], $_POST['fileName']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'addlocale') {
        echo addLocale($_POST['fileName']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getagent') {
        echo getAgent($_POST['tenant']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getuser') {
        echo getUser($_POST['id']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getadmin') {
        echo getAdmin($_POST['id']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'blockuser') {
        echo blockUser($_POST['username']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'feedback') {
        echo addFeedback($_POST['sessionId'], $_POST['roomId'], $_POST['rate'], @$_POST['text'], @$_POST['userId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getroom') {
        echo getRoom($_POST['roomId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getroombyid') {
        echo getRoomById($_POST['room_id']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'endmeeting') {
        echo endMeeting($_POST['roomId'], @$_POST['agentId']);
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getpassphrase') {
        echo getPassphrase();
    }
    if (isset($_POST['type']) && $_POST['type'] == 'getpk') {
        echo getPk();
    }
}
