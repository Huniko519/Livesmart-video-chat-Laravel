<?php
include_once 'header.php';
?>

<h1 class="h3 mb-2 text-gray-800" id="localeTitle"><span data-localize="locales"></span> - <?php
    if (isset($_GET['file'])) {
        $fileLocale = $_GET['file'] . '.json';
    } else {
        $fileLocale = 'en_US.json';
    }
    $fileLocale = substr($fileLocale, 0, -5);
    echo $fileLocale;
    ?></h1>
<div id="error" style="display:none;" class="alert alert-danger"></div>
<?php if ($_SESSION["tenant"] == 'lsv_mastertenant' || @$_GET['id'] == $_SESSION["agent"]['agent_id']) { ?>

    <div class="row">
        <div class="col-sm-6">
            <div class="p-1">
                <h6 data-localize="locales_info"></h6>
                <br/>
                <form class="user">

                    <div id="localeStrings"></div>

                    <a href="javascript:void(0);" id="saveLocale" class="btn btn-primary btn-user btn-block" data-localize="save">
                        
                    </a>
                    <hr>


                </form>

            </div>

        </div>
        <div class="col-sm-6">
            <div class="p-1">
                <h6 data-localize="locales_desc">
                    
                </h6>
                <br/>
                <div class="form-group">
                    <label for="roomName"><h6 data-localize="locales_name"></h6></label>
                    <input type="text" class="form-control" id="fileName" aria-describedby="fileName">
                </div>
                <a href="javascript:void(0);" id="addLocale" class="btn btn-primary btn-user btn-block" data-localize="add">Add</a>
                <br/>
                <?php
                if ($handle = opendir('../locales')) {
                    echo '<a href="locale.php" class="btn btn-light">en_US</a><hr>';
                    while (false !== ($entry = readdir($handle))) {
                        if ($entry != "." && $entry != ".." && $entry != "en_US.json" && substr($entry, -3) != "zip") {
                            $entry = substr($entry, 0, -5);
                            $delete = '| <a href="deletelocale.php?file=' . $entry . '" id="deleteLocale' . $entry . '" class="btn btn-light">Delete</a>';
                            echo '<a href="locale.php?file=' . $entry . '" class="btn btn-light">' . $entry . '</a>' . $delete . '<hr>';
                        }
                    }

                    closedir($handle);
                }
                ?>

            </div>
        </div>

    </div>

<?php } ?>
<?php
include_once 'footer.php';
