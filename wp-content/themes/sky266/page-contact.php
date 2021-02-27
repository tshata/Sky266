<?php
/**
*Template Name: contact
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

 get_header(); ?>
<style>
.contact-form {
    padding: 20px;
    box-shadow: 0px 0px 10px #187CC9;
    width: 100%;
    min-height: 300px;
    margin: 0 auto;
    background-color: rgba(153, 153, 153, 1);
}

.form-group button[type="submit"] {
    padding: 8px 12px;
    font-size: 14px;
    font-family: 'Montserrat', sans-serif;
}

.form-group {
    margin-bottom: 10px;
}

#message {
    height: 140px;
}

/* Style the input fields */
.form-control {
    background: #333333;
}

.form-control .invalid {
    background-color: #ffdddd;
}
</style>
<!-- contact-wrap -->
<section class="contact-wrap section-padding">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <h2>Contact Form</h2>
                <div class="contact-form">
                    <?php echo do_shortcode('[form_plugin]');  ?>
                </div><!-- /.send-feedback -->
            </div>
            <div class="col-sm-12 col-xs-12">
                <div class="office-locations">
                    <h2>Contact Information</h2>
                    <div class="row">
                        <div class="col-sm-3 col-xs-6">
                            <div class="office-address">
                                <h3>Head Office (Lesotho)</h3>
                                <address>
                                    <span>www.sky266.co.ls</span>
                                    <span>(+266) 57555325</span>
                                    <span>(+266) 62555325</span>
                                    <a href="#">info@sky266.co.ls</a>

                                </address>
                            </div>
                        </div><!-- /.col -->

                        <div class="col-sm-3 col-xs-6">
                            <div class="office-address">
                                <h3>South Africa Office</h3>
                                <address>
                                    <span>www.sky27.co.za</span>
                                    <span>(+266) 57555325</span>
                                    <span>(+266) 62555325</span>
                                    <a href="#">info@sky27.co.za</a>

                                </address>
                            </div>
                        </div><!-- /.col -->

                        <div class="col-sm-3 col-xs-6">
                            <div class="office-address">
                                <h3>China Office</h3>
                                <address>
                                    <span>www.sky86.com.cn</span>
                                    <span>(+266) 57555325</span>
                                    <span>(+266) 62555325</span>
                                    <a href="#">info@sky86.com.cn</a>
                                </address>
                            </div>
                        </div><!-- /.col -->

                        <div class="col-sm-3 col-xs-6">
                            <div class="office-address">
                                <h3>Botswana Office</h3>
                                <address>
                                    <span>www.sky267.co.bw</span>
                                    <span>(+266) 57555325</span>
                                    <span>(+266) 62555325</span>
                                    <a href="#">info@sky267.co.bw</a>
                                </address>
                            </div>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container -->
    <div> <br><br>
        <div class="location-map">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d434.8548667504892!2d27.492504001434714!3d-29.3163999376774!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xa92fd185d4beffa7!2sPrint-Corp!5e0!3m2!1sen!2sls!4v1601470100431!5m2!1sen!2sls"
                width="100%" height="400" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false"
                tabindex="0"></iframe>
        </div>
    </div><!-- /.col -->
</section>

<!-- /contact-wrap -->








<?php get_footer(); ?>