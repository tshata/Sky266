<h5 style="line-height:30px;">
    <?php echo esc_html__('You are Logged in as: ', 'wpcargo' ).' <b>'.$user_full_name.'</b>'; ?>
    <div id="dashboard_nav">
        <button class="link active" name="dashboard" id="dashboard" onclick="payments_toggle(this)">Dashboard</button>
        <button class="link" name="profile" id="profile" onclick="payments_toggle(this)" >My Profile</button>
        <a class="link" href="<?php echo esc_url(wp_logout_url(home_url())); ?>">Logout<i class="fa fa-key"></i></a>
    </div>
</h5>
<div id="wpcargo-account">
    <!-- form step tree -->
    <?php //print_r   ($user_info); ?>
    <div class="sf-content" id="dashboard_div">
        <?php
             if(in_array( 'wpcargo_driver', $user_info->roles )) 	require_once( WPCARGO_PLUGIN_PATH.'templates/driver-dashboard.php' );
             else if(in_array( 'Client', $user_info->roles )) require_once( WPCARGO_PLUGIN_PATH.'templates/shipments-table.php' );
             else require_once( WPCARGO_PLUGIN_PATH.'templates/member_dashboard.php' );  ?>
    </div>
    <div class="sf-content" id="profile_div" style="display:none;">
        profile
    </div>
    <br>
</div>
<script>
function payments_toggle(btn) {
    $(".sf-content").hide();
    $("#" + btn.id + "_div").show();

    $('#dashboard_nav .link').each(function() {
        if (this.id == btn.id) {
            $(this).addClass('active');
        } else {
            $(this).removeClass('active');
        }
    });
}
$(document).ready(function() {
    $(".sf-content").hide();
    $("#dashboard_div").show();
    $("#dashboard_nav #dashboard").addClass('active');

    $('#dataTable').DataTable({
        stateSave: true,
        sort: true,
        searching: true,
    });

    <?php echo  $user_info; ?>
});
</script>

<?php get_footer(); ?>
<!--style>
   #wpcargo-account {
      border:solid 1px white;
      outline: none;
      padding: 10px;
    }
    #shipment-list input[type=search] {
      padding: 2px;
      width: 60%;
    }
    #shipment-list thead th {
      vertical-align: bottom;
      color: #fff;
      background-color: var( --wpcargo );
      border-color: var( --wpcargo );
      border: 1px solid #eeeeee;
    }
    #dataTable {
      font-size: 13px;
    }
    #dashboard_nav .active {
       color: #DCDCDC;
    }
    #bookings_table_list {
       padding: 5px;
    }
    #bookings_table_list td, #bookings_table_list th {
       padding: 5px 10px;
    }

   #bookings_table_list tr {
       border-bottom: 1px solid #DCDCDC;
       background: black;
    }
   #bookings_table_list .head_tr {
       border-bottom: 1px solid #DCDCDC;
       background: none;
    }

   #bookings_table_list button {
       color: black;
    }
   #success input[type=text], #success input[type=number]{
       background: white;
   }
   .back{
        color: white; width: 10%;
        display: inline-block;
        border: solid 2px #333333;
        border-radius: 5px;
        background: #333333;
        font-weight: 700;
   }
   .header_label{
       color: black;
       width: 70%;
       background: #DDDDDD;
       text-transform: uppercase;
       display: inline-block;
       border-radius: 5px;
       padding: 2px;
       text-align: center;
   }


  </style-->