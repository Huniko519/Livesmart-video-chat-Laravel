<?php
include_once 'header.php';
?>


<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left" data-localize="recordings"></h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-bordered" id="recordings_table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center" data-localize="filename">Filename</th>
                        <th class="text-center" data-localize="room">Room</th>
                        <th class="text-center" data-localize="agent">Agent</th>
                        <th class="text-center" data-localize="date">Date</th>
                        <th class="text-center" data-localize="action">Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>

            </table>
        </div>

    </div>

</div>



<?php
include_once 'footer.php';
?>
