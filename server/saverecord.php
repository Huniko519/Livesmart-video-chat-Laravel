<?php
$logFile = 'recordings/log.txt';
if (isset($_POST['isFfmpeg']) && $_POST['isFfmpeg'] == 'true') {

    $OSList = array
        (
        'Windows 3.11' => 'Win16',
        'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)',
        'Windows 98' => '(Windows 98)|(Win98)',
        'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
        'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
        'Windows Server 2003' => '(Windows NT 5.2)',
        'Windows Vista' => '(Windows NT 6.0)',
        'Windows 7' => '(Windows NT 7.0)',
        'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
        'Windows ME' => 'Windows ME',
        'Open BSD' => 'OpenBSD',
        'Sun OS' => 'SunOS',
        'Linux' => '(Linux)|(X11)',
        'Mac OS' => '(Mac_PowerPC)|(Macintosh)',
        'QNX' => 'QNX',
        'BeOS' => 'BeOS',
        'OS/2' => 'OS/2',
        'Search Bot' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp)|(MSNBot)|(Ask Jeeves/Teoma)|(ia_archiver)'
    );
    // Loop through the array of user agents and matching operating systems
    foreach ($OSList as $CurrOS => $Match) {
        // Find a match
        if (preg_match("/" . $Match . "/i", $_SERVER['HTTP_USER_AGENT'])) {
            // We found the correct match
            break;
        }
    }

    foreach (array('video', 'audio') as $type) {
        if (isset($_FILES["${type}-blob"])) {
            $fileName = $_POST["${type}-filename"];
            $uploadDirectory = 'recordings/' . $fileName;
            if (!move_uploaded_file($_FILES["${type}-blob"]["tmp_name"], $uploadDirectory)) {
                file_put_contents($logFile, "Problem writing video file to disk!\n" . PHP_EOL, FILE_APPEND | LOCK_EX);
            } else {
                $videoFile = 'recordings/' . $fileName;

                $mergedFile = 'recordings/' . $fileName . '.mp4';

                if (!strrpos($CurrOS, "Windows")) {
                    $cmd = '-i ' . $videoFile . ' -vcodec libx264 -f mp4 -vb 128k ' . $mergedFile;
//                    $cmd = '-i ' . $videoFile . ' -map 0:0 -map 1:0 ' . $mergedFile;
                } else {
                    $cmd = ' -i ' . $videoFile . ' -c:v mpeg4 -c:a vorbis -b:v 64k -b:a 12k -strict experimental ' . $mergedFile;
                }

                exec('ffmpeg ' . $cmd . ' 2>&1', $out, $ret);
                if ($ret) {
                    file_put_contents($logFile, "There was a problem3 " . json_encode($out) . PHP_EOL, FILE_APPEND | LOCK_EX);
                } else {
//                    file_put_contents($logFile, "Ffmpeg successfully merged audi/video files into single WebM container!\n" . PHP_EOL, FILE_APPEND | LOCK_EX);
                    unlink($videoFile);
                }
            }
        }
    }
} else {
    foreach (array('video', 'audio') as $type) {
        if (isset($_FILES["${type}-blob"])) {

            $fileName = $_POST["${type}-filename"];
            $uploadDirectory = 'recordings/' . $fileName;
            if (!move_uploaded_file($_FILES["${type}-blob"]["tmp_name"], $uploadDirectory)) {
                echo(" problem moving uploaded file");
            }

            echo($fileName);
        }
    }
}