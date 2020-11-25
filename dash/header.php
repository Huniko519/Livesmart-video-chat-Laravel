<?php
session_start();

include_once '../server/connect.php';
$isInclude = true;
if (isset($_GET['wplogin']) && isset($_GET['url'])) {
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents WHERE username = ?');
        $stmt->execute([$_GET['wplogin']]);
        $user = $stmt->fetch();

        if ($user) {
            $isInclude = false;
            $_SESSION["tenant"] = ($user['is_master']) ? 'lsv_mastertenant' : $user['tenant'];
            $_SESSION["username"] = $user['username'];
            $actual_link = base64_decode($_GET['url']);
            $currentPath = $_SERVER['PHP_SELF'];
            $pathInfo = pathinfo($currentPath);
            $basename = $pathInfo['basename'];
        } else {
            header("Location:loginform.php");
        }
    } catch (Exception $e) {
        return false;
    }
} else {

    $currentPath = $_SERVER['PHP_SELF'];
    $pathInfo = pathinfo($currentPath);
    $hostName = $_SERVER['HTTP_HOST'];

    $actual_link = 'https://' . $hostName . $pathInfo['dirname'] . '/';
    $actual_link = str_replace('dash/', '', $actual_link);
    $basename = $pathInfo['basename'];
}

if (empty($_SESSION["username"])) {
    header("Location:loginform.php");
} else {
    $pos = strpos($_SERVER['REQUEST_URI'], 'validate.php');

    if ($pos === false && !$setVal) {
        header("Location:validate.php");
    }

    if ($pos !== false && $setVal) {
        header("Location:dash.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>LiveSmart Agent Dashboard</title>

        <!-- Custom fonts for this template-->
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

        <!-- Custom styles for this template-->

        <link href="css/sb-admin-2.min.css" rel="stylesheet">
        <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/agent.css" rel="stylesheet">
        <link rel="stylesheet" href="css/simplechat.css" rel="stylesheet">
        <link rel="stylesheet" href="css/bootstrap-datetimepicker.css" rel="stylesheet">
    </head>

    <body id="page-top">

        <!-- Page Wrapper -->
        <div id="wrapper">
            <?php
            if ($isInclude) {
                ?>
                <!-- Sidebar -->
                <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

                    <!-- Sidebar - Brand -->
                    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dash.php">
                        <img src="img/logo.png">
                    </a>

                    <!-- Divider -->
                    <hr class="sidebar-divider my-0">

                    <!-- Nav Item - Dashboard -->
                    <li class="nav-item active">
                        <a class="nav-link" href="dash.php">
                            <i class="fas fa-fw fa-tachometer-alt"></i>
                            <span data-localize="dashboard"></span></a>
                    </li>

                    <!-- Divider -->
                    <hr class="sidebar-divider">
                    <?php if ($_SESSION["tenant"] == 'lsv_mastertenant') { ?>
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="agents.php" data-toggle="collapse" data-target="#collapseAgents" aria-expanded="true" aria-controls="collapseTwo">
                                <i class="fas fa-fw fa-users-cog"></i>
                                <span data-localize="agents"></span>
                            </a>
                            <div id="collapseAgents" class="collapse" aria-labelledby="collapseAgents" data-parent="#accordionSidebar">
                                <div class="bg-white py-2 collapse-inner rounded">
                                    <h6 class="collapse-header" data-localize="agents"></h6>
                                    <a class="collapse-item" href="agents.php" data-localize="list_agents"></a>
                                    <a class="collapse-item" href="agent.php" data-localize="add_agent"></a>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                    <li class="nav-item">
                        <a class="nav-link" href="visitors.php">
                            <i class="fas fa-fw fa-list"></i>
                            <span data-localize="visitors"></span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="rooms.php" data-toggle="collapse" data-target="#collapseRooms" aria-expanded="true" aria-controls="collapseTwo">
                            <i class="fas fa-fw fa-video"></i>
                            <span data-localize="rooms"></span>
                        </a>
                        <div id="collapseRooms" class="collapse" aria-labelledby="collapseRooms" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <h6 class="collapse-header" data-localize="rooms"></h6>
                                <a class="collapse-item" href="rooms.php" data-localize="list_rooms"></a>
                                <a class="collapse-item" href="room.php" data-localize="room_management"></a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="chats.php">
                            <i class="fas fa-fw fa-comment-dots"></i>
                            <span data-localize="chat_history"></span></a>
                    </li>
                    <?php if ($_SESSION["tenant"] == 'lsv_mastertenant') { ?>
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="users.php" data-toggle="collapse" data-target="#collapseUsers" aria-expanded="true" aria-controls="collapseTwo">
                                <i class="fas fa-fw fa-users"></i>
                                <span data-localize="users"></span>
                            </a>
                            <div id="collapseUsers" class="collapse" aria-labelledby="collapseUsers" data-parent="#accordionSidebar">
                                <div class="bg-white py-2 collapse-inner rounded">
                                    <h6 class="collapse-header" data-localize="users"></h6>
                                    <a class="collapse-item" href="users.php" data-localize="list_users"></a>
                                    <a class="collapse-item" href="user.php" data-localize="add_user"></a>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                    <?php if ($_SESSION["tenant"] == 'lsv_mastertenant') { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="recordings.php">
                                <i class="fas fa-fw fa-compact-disc"></i>
                                <span data-localize="recordings"></span></a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="config.php">
                                <i class="fas fa-fw fa-cogs"></i>
                                <span data-localize="configurations"></span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="locale.php">
                                <i class="fas fa-fw fa-globe"></i>
                                <span data-localize="locales"></span></a>
                        </li>
                    <?php } ?>


                    <!-- Divider -->
                    <hr class="sidebar-divider d-none d-md-block">

                    <!-- Sidebar Toggler (Sidebar) -->
                    <div class="text-center d-none d-md-inline">
                        <button class="rounded-circle border-0" id="sidebarToggle"></button>
                    </div>

                </ul>
                <!-- End of Sidebar -->
                <?php
            }
            ?>
            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">
                    <?php
                    if ($isInclude) {
                        ?>
                        <!-- Topbar -->
                        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                            <h2 data-localize="title"></h2>
                            <!-- Topbar Navbar -->
                            <ul class="navbar-nav ml-auto">

                                <div class="topbar-divider d-none d-sm-block"></div>

                                <!-- Nav Item - User Information -->
                                <li class="nav-item dropdown no-arrow">

                                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span id="statusIcon"><i class="fas fa-fw fa-times-circle" id="statusNotOk"></i></span>

                                        <span class="mr-2 d-none d-lg-inline text-gray-600"><?php echo @$_SESSION["agent"]["first_name"] . ' ' . @$_SESSION["agent"]["last_name"]; ?></span>
                                        <img class="img-profile rounded-circle" src="img/small-avatar.jpg">
                                    </a>
                                    <!-- Dropdown - User Information -->
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                        <a class="dropdown-item" href="agent.php?id=<?php echo $_SESSION["agent"]["agent_id"]; ?>">
                                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                            <span data-localize="profile"></span>
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal">
                                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                            <span data-localize="logout"></span>
                                        </a>
                                    </div>
                                </li>

                            </ul>

                        </nav>
                        <?php
                    }
                    ?>
                    <!-- End of Topbar -->
                    <!-- Begin Page Content -->
                    <div class="container-fluid">
