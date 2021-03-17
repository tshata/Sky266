<?php
$connected = sendwp_client_connected();
$forwarding = sendwp_forwarding_enabled();
?>

<div class="sendwp-page">

<img src="<?php echo \SendWP\Assets::image_url('logo-render.png'); ?>" alt="SendWP" class="logo" />

<?php if(!$connected) { ?>
<div id="sendwp-connect">
    <p><?php _e('Your site is currently not connected to sendwp.com.', 'sendwp'); ?></p>
    <form method="POST" action="<?php echo sendwp_get_server_url(); ?>_/signup">
        <input type="hidden" name="client_name" value="<?php echo sendwp_get_client_name(); ?>">
        <input type="hidden" name="client_url" value="<?php echo sendwp_get_client_url(); ?>">
        <input type="hidden" name="client_redirect" value="<?php echo sendwp_get_client_redirect(); ?>">
        <input type="hidden" name="client_secret" value="<?php echo sendwp_get_client_secret(); ?>">
        <button type="submit" class="button button-primary">
            <?php _e('Connect to SendWP', 'sendwp'); ?>
        </button>
    </form>
</div>
<?php } else { ?>
<div id="sendwp-enabled">
    <label class="switch" id="sendwp-enabled-button">
        <input type="checkbox" id="sendwp-enabled-checkbox" <?php print $forwarding ? 'checked' : ''; ?>/>
        <span class="slider round"></span>
    </label>
    <span id="sendwp-enabled-status"><?php print $forwarding ? $vars['enabled'] : $vars['disabled']; ?></span>
</div>
<?php } ?>
<hr />

<p>
    <strong>Questions?</strong> Get help at <a href="https://sendwp.com/account/get-help">https://sendwp.com/account/get-help</a>
</p>

<!-- spoiler -->
<div id="spoiler-block">
    <span id="spoiler-title">Debug Info<span id="spoiler-arrow" class="down"></span></span>
    <div id="spoiler-content" class="closed">
        <ul>
            <li>Server URL: <?php echo sendwp_get_server_url(); ?></li>
            <li>Client Name: <?php echo sendwp_get_client_name(); ?></li>
            <li>Client URL: <?php echo sendwp_get_client_url(); ?></li>
            <li>Client Redirect: <?php echo sendwp_get_client_redirect(); ?></li>
            <li>Client Secret: <?php echo sendwp_get_client_secret(); ?></li>
            <li>Authorization Hash: <?php echo sendwp_generate_hash() ?> </li>
            <li>Connection Status: <?php print $connected ? 'Connected' : 'Not Connected'; ?></li>
            <li>Forwarding Status: <?php print $forwarding ? 'Enabled' : 'Disabled'; ?></li>
            <li>Last Check In: <?php echo sendwp_last_pulse(); ?></li>
            <li>Last Result: <?php echo sendwp_last_pulse_result(); ?></li>
        </ul>
    </div>
</div>

</div>
<!-- end of main content -->