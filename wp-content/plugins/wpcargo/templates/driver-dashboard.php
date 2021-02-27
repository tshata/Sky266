<div id="content_div">
    <br>
    <?php
            $comingtrips = $wpdb->get_results( "SELECT * FROM trips WHERE drivers Like '%$user_full_name%' AND status!='Closed' AND status!='Terminated' ORDER BY trip_date ASC");
            $pasttrips = $wpdb->get_results( "SELECT * FROM trips WHERE drivers Like '%$user_full_name%' AND status LIKE 'Closed' ORDER BY trip_date DESC");

            if(!is_array($comingtrips) && !is_array($pasttrips)) { echo "There are no trips assigned to you.<br><br><br><br><br>";}
            else {
            ?>
    <table class="datatable row-border" id="trips_table_list">
        <thead>
            <tr>
                <th>#</th>
                <th>Trip Name</th>
                <th>Trip Date</th>
                <th>Assigned Drivers</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php  $i=1;
              foreach($comingtrips AS $trip){      ?>
            <tr id="<?php echo $trip->id; ?>" class='clickable-row'>
                <td><?php echo $i; ?></td>
                <td><?php echo $trip->trip_name; ?></td>
                <td><?php echo date_format(date_create($trip->trip_date),'d-M-Y'); ?></td>
                <td><?php echo unserialize($trip->drivers); ?></td>
                <td
                    style='background:<?php echo ($trip->status=="Active")?"green" : (($trip->status=="Upcoming")?"orange" : (($trip->status=="Terminated")?"red" : "gray")); ?>'>
                    <?php echo $trip->status; ?>
                </td>
            </tr>
            <?php $i++; }
              foreach($pasttrips AS $trip){ ?>
            <tr id="<?php echo $trip->id; ?>" class='clickable-row'>
                <td><?php echo $i; ?></td>
                <td><?php echo $trip->trip_name; ?></td>
                <td><?php echo date_format(date_create($trip->trip_date),'d-M-Y'); ?></td>
                <td><?php echo unserialize($trip->drivers); ?></td>
                <td
                    style='background:<?php echo ($trip->status=="Active")?"green" : (($trip->status=="Upcoming")?"orange" : (($trip->status=="Terminated")?"red" : "gray")); ?>'>
                    <?php echo $trip->status; ?></td>
            </tr>
            <?php $i++; }
              ?>
        </tbody>
        <tfoot>
            <tr>
                <th>#</th>
                <th>Trip Name</th>
                <th>Trip Date</th>
                <th>Assigned Drivers</th>
                <th>Status</th>
            </tr>
        </tfoot>
    </table>
    <?php } ?>
</div>

<!-- The Modal -->
<form id="myModal" class="wpcargo-modal">
    <!-- Modal content -->
    <div class="modal-content" id="modal-content-driver">
        <div class="modal-header">
            <span class="close" onclick="close_modal()">&times;</span>
            <h4>Modal Header</h4>
        </div>
        <div class="modal-body">
            <div style=" display: none;" id="trip_div">
                <div style=" display: none;" id="activate" class=" wpcargo-row">
                    <div class="wpcargo-col-md-3">Vehicle Reg. :</div>
                    <div class="wpcargo-col-md-9">
                        <input type="text" name="vehicle_reg" placeholder="0"
                            style="width: 200px; height: 25px; background: none;" />
                    </div>
                    <div class="wpcargo-col-md-3"> Vehicle Mileage :</div>
                    <div class="wpcargo-col-md-9">
                        <input type="text" name="opening_mileage" placeholder="0"
                            style="width: 200px; height: 25px; background: none;" />
                    </div>
                    <div class="wpcargo-col-md-3">Trailer Reg. : </div>
                    <div class="wpcargo-col-md-9">
                        <input type="text" name="trailer_reg" placeholder="0"
                            style="width: 200px; height: 25px; background: none;" />
                    </div>
                    <div class="wpcargo-col-md-3">Trip Checklist : </div>
                    <ul id="checklist" class="wpcargo-col-md-9">
                        <li><input type="checkbox" class="abc" style="width: 20px; height: 15px;" />&nbsp;&nbsp;License
                        </li>
                        <li><input type="checkbox" class="abc" style="width: 20px; height: 15px;" />&nbsp;&nbsp;Passport
                        </li>
                    </ul>
                </div>
                <div style=" display: none;" id="end">
                    Car Mileage: <input type="text" name="closing_mileage" placeholder="0"
                        style="width: 200px; background: none;" />
                </div>
            </div>
            <div style=" display: none;" id="shipment_div">
                <div id="success">
                    <h5>Collected Packages</h5>
                    <table id="wpcargo-package-table" class="wpc-multiple-package wpc-repeater">
                        <thead>
                            <tr>
                                <th>Barcode</th>
                                <th>AW</th>
                                <th>DW</th>
                                <th>CBM</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody data-repeater-list="<?php echo WPCARGO_PACKAGE_POSTMETA; ?>">
                            <tr data-repeater-item class="wpc-mp-tr">
                                <?php $i=1;
                                 ?>
                                <td><input type="text" name="wpc-pm-scanner[]" id="wpc-pm-scanner"
                                        placeholder="Barcoder"></td>
                                <td><input type="text" name="wpc-pm-weight[]" id="wpc-pm-weight" placeholder="AW"></td>
                                <td><input type="text" name="wpc-pm-length[]" id="wpc-pm-length" placeholder="L"
                                        style="width: 30%;">
                                    <input type="text" name="wpc-pm-width[]" id="wpc-pm-width" placeholder="W"
                                        style="width: 30%;">
                                    <input type="text" name="wpc-pm-height[]" id="wpc-pm-height" placeholder="H"
                                        style="width: 30%;">
                                </td>
                                <td><input type="text" name="wpc-pm-cbm[]" id="wpc-pm-cbm" placeholder="CBM"></td>
                                <td>
                                    <center>
                                        <button>Scan</button>
                                        <a style="color: red; cursor: pointer;" data-repeater-delete>
                                            <?php esc_html_e('Del','wpcargo'); ?></a>
                                    </center>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="wpc-computation">
                                <td colspan="5">
                                    <input data-repeater-create type="button" class="wpc-add"
                                        value="<?php esc_html_e('Add Package','wpcargo'); ?>" />
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div id="failed">
                    <label>Cause of failure: </label>
                    <select name="failure_cause" style="background:white;">
                        <option>Client Fault</option>
                        <option>Our Fault</option>
                    </select> <br>
                    <label>Remarks: </label>
                    <textarea style="background: white;" name="reasons" placeholder="type here"></textarea>
                </div>
                <input type="hidden" name="booking_id" id="booking_id" value="">
                <input type="hidden" name="remarks" id="remarks" value="">
            </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" name="trip_id" value="" />
            <input type="hidden" id="action" name="action" value="driver_save_forms_action">
            <input type="hidden" id="current_form" name="current_form" value="">

            <a class="button" id="save_btn" onclick="driver_modal_forms_save(this)">Save</a>
            <a class="button" onclick="close_modal()">Close</a>
        </div>
    </div>
</form>

<script>
function trip_singleview(trip_id) {
    if (trip_id == "back_btn") {
        location.reload(true);
    } else {
        $('body').append('<div class="wpcargo-loading">Loading...</div>');
        $.ajax({
            url: wpcargoAJAXHandler.ajax_url,
            type: "POST",
            data: {
                action: 'driver_trip_singleview_action',
                trip_id: trip_id,
            },
            success: function(data) {
                $('#content_div').html(data);
                $('#myModal input[name="trip_id"]').val(trip_id);
                $('.wpcargo-loading').hide();
            },
            error: function(errorThrown) {
                $('#content_div').html('Error retrieving data. Please try again.');
            }

        });
    }
}

function driver_city_singleview(btn) {
    if (btn.id == "back_btn") {
        trip_id: $("#trip_id").val();
        trip_singleview(trip_id);
    }
    else {
        $('body').append('<div class="wpcargo-loading">Loading...</div>');
        $.ajax({
            url: wpcargoAJAXHandler.ajax_url,
            type: "POST",
            data: {
                action: 'driver_city_singleview_action',
                trip_id: $("#trip_id").val(),
                schedule_id: btn.id,
                schedule_city: $(btn).data('schedule_city'),
            },
            success: function(data) {
                $('#content_div').html(data);
                $('.wpcargo-loading').hide();
            },
            error: function(errorThrown) {
                $('#content_div').html('Error retrieving data. Please try again.');
            }

        });
    }

}

function booking_singleview(btn) {
    $('body').append('<div class="wpcargo-loading">Loading...</div>');
    $.ajax({
        url: wpcargoAJAXHandler.ajax_url,
        type: "POST",
        data: {
            action: 'driver_booking_singleview_action',
            trip_id: $("#trip_id").val(),
            booking_id: btn.id,
            schedule_id: $("#schedule_id").val(),
            schedule_city: $("#schedule_city").val(),
        },
        success: function(data) {
            $('#content_div').html(data);
            $('.wpcargo-loading').hide();
        },
        error: function(errorThrown) {
            $('#content_div').html('Error retrieving data. Please try again.');
        }

    });
}

function update_trip_state(elm) {
    if (elm.innerHTML == "Activate Trip") {
        $('#save_btn').hide();
        var heading = "Activating Trip";
        $("#current_form").val("activate_trip_form");
        $('#trip_div #activate').show();
        $('#trip_div #end').hide();
        $("input[type='checkbox'].abc").change(function() {
            var a = $("input[type='checkbox'].abc");
            if (a.length == a.filter(":checked").length) {
                $('#save_btn').show();
            } else $('#save_btn').hide();
        });
    } else {
        var unattended = 0;
        $("#bookings_table_list tbody tr").each(function() {
            unattended += parseInt($(this).find("td:eq(2)").text().trim());
        });
        if (unattended > 0) {
            alert("Trip cannot be ended! Because it has " + unattended + " unattended bookings.");
            return false;
        }
        var heading = "Ending Trip";
        $('#save_btn').show();
        $("#current_form").val("end_trip_form");
        $('#trip_div #activate').hide();
        $('#trip_div #end').show();

    }
    $('#myModal .modal-header').html(heading);
    $('#trip_div').show();
    $('#shipment_div').hide();
    $('#myModal').show();
}

function update_shipment_state(elm) {
    $('#save_btn').show();
    if (elm.innerHTML == "Success") {
        $('#shipment_div #success').show();
        $('#shipment_div #failed').hide();
        $('.wpcargo-modal #modal-content-driver').css('width', '80%;');
        $("#current_form").val("success_form");
    } else if (elm.innerHTML == "Failed") {
        $('#shipment_div #success').hide();
        $('#shipment_div #failed').show();
        $("#current_form").val("failed_form");
    }
    $('#myModal .modal-header h4').text(elm.value);
    $('#shipment_div #booking_id').val(elm.id);
    $("#shipment_div #remarks").val(elm.value);
    $('#trip_div').hide();
    $('#shipment_div').show();
    $('#myModal').show();
}
jQuery(document).ready(function($) {
    'use strict';
    $('#wpcargo-package-table').repeater({
        show: function() {
            $(this).slideDown();
        },
        hide: function(deleteElement) {
            if (confirm(
                    '<?php esc_html_e( 'Are you sure you want to delete this element?', 'wpcargo' ); ?>'
                    )) {
                $(this).slideUp(deleteElement);
            }
        }
    });
    //trips table datatable
    var table = $('#trips_table_list').DataTable({
        stateSave: true
    });
    $('#trips_table_list tbody').on('click', 'tr', function() {
        trip_singleview(this.id);
    });

});

function driver_modal_forms_save(btn) {
    $('body').append('<div class="wpcargo-loading">Loading...</div>');
    $.ajax({
        url: wpcargoAJAXHandler.ajax_url,
        type: "POST",
        data: $("#myModal").serializeArray(),
        success: function(data) {
            if ($("#current_form").val() == "activate_trip_form") {
                $('#trip_status').text("Active");
                $('#trip_update_link').text("End Trip");
                $('#trip_update_link').css("background", "red");
                alert(data);
            } else if ($("#current_form").val() == "end_trip_form") {
                location.reload(true);
            } else if ($("#current_form").val() == "success_form" || $("#current_form").val() ==
                "failed_form") {
                $(".header_label span").text("Status: " + $("#shipment_div #remarks").val());
                $("#success_btns .link").css('display', 'none');
            }

            $('#myModal').hide();
            $('.wpcargo-loading').hide();

        },
        error: function(errorThrown) {
            alert("Failed!");
            $('.wpcargo-loading').hide();
            //$('#msg').html('Error retrieving data. Please try again.');
            //$('#msg').show();
        }

    });
}

function verify_checklist() {
    var a = $("input[type='checkbox'].abc");
    if (a.length == a.filter(":checked").length) {
        alert('all checked');
    }
}
</script>