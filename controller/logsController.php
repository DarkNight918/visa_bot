<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Screen\Capture;

require_once "../vendor/autoload.php";
// Connect to MySQL
require_once('../db-config.php');
// Get all data from tbl_bots table
$sql = 'SELECT * FROM tbl_logs ORDER BY created_at DESC limit 20';
$result = $conn->query($sql);
// Create DOM using query result
$dom = '';

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host       = 'email-smtp.us-east-1.amazonaws.com';
$mail->SMTPAuth   = true;
$mail->Username   = 'AKIAZZIJHXFYRB2QZWOA';
$mail->Password   = 'BAODGh4BC9hSRI0NZRVVltDRDOXFZRGPRMsBrwSu8PoF';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
$mail->Port       = 2465;

$mail->setFrom("zubairthedeveloper@gmail.com", '');
$mail->addAddress("darknight.dev0918@gmail.com", '');
$mail->isHTML(true);
$mail->Subject = "New Event Happened.";
$mail->Body = "";

if ($result->num_rows > 0) {
    $index = 1;
    // Set timezone to EST 
    date_default_timezone_set('America/New_York');
    while ($row = $result->fetch_assoc()) {
        // $dateTime = new DateTime();
        // $dateTime->setTimestamp((int)$row['created_at'] / 1000);

        // format the date as "YYYY-mm-dd HH:mm:ss.u"
        // $dateTimeString = $dateTime->format('Y-m-d H:i:s.u');
        // $dateTimeString = substr($dateTimeString, 0, -3); // remove last 3 digits to get milliseconds only

        $totalMilliseconds = (int)$row['created_at'];
        $timestamp = round($totalMilliseconds / 1000);
        $dateTimeString = date('Y-m-d H:i:s', $timestamp) . '.' . sprintf('%03d', $totalMilliseconds % 1000);

        if ($row['log'] == "closed") {
            $mail->Body = "The bot is stopped now.";
            $mail->send();
        } else if ($row['log'] == 'no available appointements') {
            $mail->Body = "There is no available appointments.";
            $mail->send();
        } else if ($row['log'] == 'do reschedule->new rescheduling done') {
            $mail->Body = "Successfully rescheduled. Congratulations.";
            $mail->send();
        }

        $dom .= '<tr class="border border-solid border-gray-300">' .
            '<td class="py-[12px] px-[10px]">' . $row['email'] . '</td>' .
            '<td class="py-[12px] px-[10px]">' . $row['name'] . '</td>' .
            '<td class="py-[12px] px-[10px]">' . $row['passport'] . '</td>' .
            '<td class="py-[12px] px-[10px] relative">' . $row['log'] . '</td>' .
            '<td class="py-[12px] px-[10px] text-right">' . $dateTimeString . '</td>' .
        '</tr>';
        $index ++;
    }
} else {
    $dom = '<tr class="border border-solid border-gray-300 text-center"><td colspan="5" class="py-[12px] px-[10px]">No data to display.</td></tr>';
}
echo $dom;
?>