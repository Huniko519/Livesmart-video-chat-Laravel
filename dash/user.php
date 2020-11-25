<?php
include_once 'header.php';
?>

<h1 class="h3 mb-2 text-gray-800" id="userTitle" data-localize="user"></h1>
<div id="error" style="display:none;" class="alert alert-danger"></div>
<?php if ($_SESSION["tenant"] == 'lsv_mastertenant') { ?>

    <div class="row">
        <div class="col-lg-5">
            <div class="p-1">

                <form class="user">

                    <div class="form-group">
                        <label for="first_name"><h6 data-localize="first_name"></h6></label>
                        <input type="text" class="form-control " id="first_name" aria-describedby="first_name">
                    </div>
                    <div class="form-group">
                        <label for="last_name"><h6 data-localize="last_name"></h6></label>
                        <input type="text" class="form-control " id="last_name" aria-describedby="last_name">
                    </div>
                    <div class="form-group">
                        <label for="email"><h6 data-localize="email"></h6></label>
                        <input type="text" class="form-control " id="email" aria-describedby="email">
                    </div>
                    
                    <div class="form-group">
                        <label for="last_name"><h6><span data-localize="password"></span> <span id="leftblank"></span>:</h6></label>
                        <input type="password" class="form-control " id="password" autocomplete="new-password">
                    </div>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="is_blocked">
                        <label class="custom-control-label" for="is_blocked" data-localize="blocked"></label>
                    </div>
                    <hr>
                    <a href="javascript:void(0);" id="saveUser" class="btn btn-primary btn-user btn-block" data-localize="save">
                        
                    </a>
                    <hr>

                </form>

            </div>
        </div>
    </div>
<?php } ?>
<?php
include_once 'footer.php';
