<?php

class OsLiveSmartHelper {

    private static function convertToHoursMins($time) {
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return array($hours, $minutes);
    }

    public static function insertRoom($booking, $agentUrl = null, $visitorUrl = null) {

        $selected_agent = new OsAgentModel($booking->agent_id);
        $selected_customer = new OsCustomerModel($booking->customer_id);
        $start_date = $booking->start_date;
        $end_date = $booking->end_date;
        $datetimeStart = OsLiveSmartHelper::convertToHoursMins($booking->start_time);
        $datetimeEnd = OsLiveSmartHelper::convertToHoursMins($booking->end_time);
        
        $dateStart = get_gmt_from_date($start_date . ' ' . $datetimeStart[0] . ':' .$datetimeStart[1], 'Y-m-d\TH:i:s\Z');
        $duration = (strtotime($end_date . ' ' . $datetimeEnd[0] . ':' .$datetimeEnd[1]) - strtotime($start_date . ' ' . $datetimeStart[0] . ':' .$datetimeStart[1])) / 60;
        
        $lsRepUrl = get_option('livesmart_server_url');

        $posts = http_build_query(array('type' => 'addroom', 'lsRepUrl' => $lsRepUrl, 'agentShortUrl' => $agentUrl, 'visitorShortUrl' => $visitorUrl, 'agentId' => $selected_agent->email, 'dateTime' => $dateStart, 'duration' => $duration, 'agentName' => $selected_agent->first_name . ' ' . $selected_agent->last_name, 'visitorName' => $selected_customer->first_name . ' ' . $selected_customer->last_name, 'is_active' => true));
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $lsRepUrl . 'server/script.php',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $posts,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
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
