<?php
session_start();
if (!isset($_SESSION['email'])) {
    echo '<script>window.location.href="sign-in.php"</script>';
}
require_once('db-config.php');

$sql = 'SELECT id, priority FROM tbl_users ORDER BY priority';
$result = $conn->query($sql);
// Priority downdrop menu items
$arr_temp = range(1, 100);
$arr_exclusion = [];
while ($row = $result->fetch_assoc()) {
    array_push($arr_exclusion, $row['priority']);
}
$priority_list = array_values(array_diff($arr_temp, $arr_exclusion));

$sql = 'SELECT id, email FROM tbl_bots';
$result  = $conn->query($sql);
$bot_list = array();
while($row = $result->fetch_assoc()) {
    array_push($bot_list, $row);
}

$select_data = json_encode(
    array(
        'priority' => $priority_list,
        'bot_id' => $bot_list
    )
);
?>

<!-- Sign In Page -->
<!DOCTYPE html>
<?php require_once('env.php')?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage VISAs - Home</title>
    <link rel="icon" href="<?php echo $BASE_URL?>favicon.png">
    <!-- include Tailwind CSS stylesheet -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- include Tailwind CSS custom configration -->
    <script type="text/javascript" src="js/tailwind.config.js"></script>
    <!-- include jQuery -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- include Toastr Notification -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 24px;
        }
        .switch input { 
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }
        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }
        input:checked + .slider:before {
            -webkit-transform: translateX(36px);
            -ms-transform: translateX(36px);
            transform: translateX(36px);
        }
        /* Rounded sliders */
        .slider.round {
        border-radius: 24px;
        }
        .slider.round:before {
        border-radius: 50%;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>
<body class="w-full h-screen bg-primary-normal font-[Inter]">
    <div class="flex">
        <!-- Leftbar -->
        <div class="relative w-[280px] h-screen overflow-y-auto">
            <!-- Navigation Section  -->
            <div class="min-h-[500px] ">
                <!-- Banner Label -->
                <h6 class="mt-[40px] pl-[60px] text-[16px] text-white font-semibold">visa bot בוט לוויזות</h6>
                <!-- Navigation Links -->
                <ul class="mt-[24px] px-[24px] text-[16px] text-white font-semibold">
                    <a href="home.php">
                        <li class="mt-[12px] duration-300 hover:bg-primary-light">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="inline-block w-6 h-6">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            home בית
                        </li>
                    </a>
                    <a href="logs.php">
                        <li class="mt-[12px] duration-300 hover:bg-primary-light">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="inline-block w-6 h-6">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                                <path strokeLinecap="round" strokeLinejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                            </svg>
                            logs יומנים
                        </li>
                    </a>
                    <a href="permission.php">
                        <li class="mt-[12px] duration-300 hover:bg-primary-light">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            permission רְשׁוּת
                        </li>
                    </a>
                </ul>
                
                <!-- User Infomation & Logout -->
                <div class="absolute px-[16px] py-[32px] w-full bottom-0">
                    <div class="pt-5 flex justify-between w-full border-t border-solid border-t-1 border-primary-light">
                        <div>
                            <h6 class="text-white text-[14px] font-semibold"><?php echo $_SESSION['name']?></h6>
                            <p class="text-primary-lightest text-[14px]"><?php echo $_SESSION['email']?></p>
                        </div>
                        <div>
                            <a href="#" id="logout" class="text-primary-lightest" title="Logout">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="w-6 h-6">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main -->
        <div class="pt-3 w-[calc(100vw-280px)] h-screen">
            <!-- Round White Board -->
            <div class="h-[calc(100vh-0.9rem)] p-[32px] rounded-tl-[32px] rounded-bl-[32px] bg-white overflow-y-auto">
                <!-- Page Title Section -->
                <div class="flex justify-between">
                    <!-- Page Title -->
                    <h1 class="text-[30px] font-semibold">Home - בית</h1>
                    <!-- Language Button -->
                    <button id="change-language" class="h-[44px] px-[18px] py-[10px] bg-primary-light text-white rounded-[8px] duration-200 hover:bg-primary-lighter hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="inline-block w-6 h-6">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                        </svg>
                        Hebrew עברית
                    </button>
                </div>
                <!-- Users Table -->
                <div class="mt-10">
                    <h1 class="text-right text-[21px] font-semibold">Users</h1>
                    <hr>
                    <button type="button" class="mt-3 block text-white font-semibold bg-primary-light hover:bg-primary-lighter focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg px-5 py-2.5 text-center" data-role="add">
                        + Add new
                    </button>
                    <table id="tbl-users" class="w-full mt-5">
                        <thead class="bg-primary-normal text-white text-center">
                            <tr>
                                <th class="py-[12px] px-[10px] rounded-tl-[24px]">No</th>
                                <th class="py-[12px] px-[10px]">First name</th>
                                <th class="py-[12px] px-[10px]">Last name</th>
                                <th class="py-[12px] px-[10px]">Passport</th>
                                <th class="py-[12px] px-[10px] min-w-[80px]">Latest Day</th>
                                <th class="py-[12px] px-[10px] min-w-[80px]">Current Appointment Day</th>
                                <th class="py-[12px] px-[10px]">Priority</th>
                                <th class="py-[12px] px-[10px]">Bot</th>
                                <th class="py-[12px] px-[10px]">Pause / Resume</th>
                                <th class="py-[12px] px-[10px]">Created By</th>
                                <th class="py-[12px] px-[10px] rounded-tr-[24px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75a4.5 4.5 0 01-4.884 4.484c-1.076-.091-2.264.071-2.95.904l-7.152 8.684a2.548 2.548 0 11-3.586-3.586l8.684-7.152c.833-.686.995-1.874.904-2.95a4.5 4.5 0 016.336-4.486l-3.276 3.276a3.004 3.004 0 002.25 2.25l3.276-3.276c.256.565.398 1.192.398 1.852z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.867 19.125h.008v.008h-.008v-.008z" />
                                    </svg>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr><td colspan="11" class="py-[12px] px-[10px]">No data to display.</td></tr>
                        </tbody>
                        <tfoot class="bg-primary-normal text-white text-center">
                            <tr><td colspan="11" class="py-[8px] px-[10px] rounded-bl-[24px] rounded-br-[24px]">Showing all users for appointment search.</td></tr>
                        </tfoot>
                    </table>
                </div>
                <!-- Bots Table -->
                <div class="mt-10">
                    <h1 class="text-right text-[21px] font-semibold">Bots</h1>
                    <hr>
                    <button type="button" class="mt-3 block text-white font-semibold bg-primary-light hover:bg-primary-lighter focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg px-5 py-2.5 text-center" data-role="add">
                        + Add new
                    </button>
                    <table id="tbl-bots" class="w-full mt-5">
                        <thead class="bg-primary-normal text-white text-center">
                            <tr>
                                <th class="py-[12px] px-[10px] rounded-tl-[24px]">No</th>
                                <th class="py-[12px] px-[10px]">Email</th>
                                <th class="py-[12px] px-[10px]">Password</th>
                                <th class="py-[12px] px-[10px] rounded-tr-[24px]">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75a4.5 4.5 0 01-4.884 4.484c-1.076-.091-2.264.071-2.95.904l-7.152 8.684a2.548 2.548 0 11-3.586-3.586l8.684-7.152c.833-.686.995-1.874.904-2.95a4.5 4.5 0 016.336-4.486l-3.276 3.276a3.004 3.004 0 002.25 2.25l3.276-3.276c.256.565.398 1.192.398 1.852z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.867 19.125h.008v.008h-.008v-.008z" />
                                    </svg>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr><td colspan="4" class="py-[12px] px-[10px]">No data to display.</td></tr>
                        </tbody>
                        <tfoot class="bg-primary-normal text-white text-center">
                            <tr><td colspan="4" class="py-[8px] px-[10px] rounded-bl-[24px] rounded-br-[24px]">Showing passport information which is created for all bots.</td></tr>
                        </tfoot>
                    </table>
                </div>
                <!-- Running Bot Section -->
                <div class="mt-10 text-center flex justify-center items-center gap-4">
                    <button id="run-bot" style="box-shadow: 0px 1px 2px rgba(16, 24, 40, 0.8);" class="animate-bounce h-[44px] px-[18px] py-[10px] bg-primary-light text-white font-semibold rounded-[8px] duration-200 hover:bg-primary-lighter">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="inline-block w-6 h-6">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                        </svg>
                        Run Bot
                    </button>
                    <input type="checkbox" id="bottype" name="bottype" />
                    <label for="bottype">Run bot with UI</label>
                    <button id="stop-bot" style="box-shadow: 0px 1px 2px rgba(16, 24, 40, 0.8);" class="animate-bounce h-[44px] px-[18px] py-[10px] bg-black text-white font-semibold rounded-[8px] duration-200 hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" class="inline-block w-6 h-6">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                        </svg>
                        Stop Bot
                    </button>
                </div>
            </div>
        </div>
    </div>  

    <!-- Custom JS -->
    <script type="text/javascript" src="js/config.keys.js"></script>
    <script>
        var userEmail = '<?php echo $_SESSION['email']?>';
        var userName = '<?php echo $_SESSION['name']?>';
        var selectData = JSON.parse('<?php echo $select_data?>');
        console.log(selectData);
        $('#logout').on('click', function(e) {
            e.preventDefault();
            $.post(BASE_URL + 'controller/logoutController.php', {}, (res) => {
                if (res === 'success') {
                    window.location.href = 'sign-in.php';
                    return;
                }
            });
        });
    </script>
    <script type="text/javascript" src="js/table.editable.js"></script>
    <script type="text/javascript" src="js/home.js"></script>
</body>
</html>