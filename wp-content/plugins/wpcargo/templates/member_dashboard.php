<section class="dashboard-module_dashboard_2I0lL">
    <h2>My Account</h2>
    <div class="grid-x grid-margin-x grid-margin-y card-container dashboard-module_card-container_1wMCY">
        <div class="cell medium-6 align-self-stretch dashboard-module_animate-card_2Y2b-">
            <div class="card-module_card_3XoAu">
                <div class="card-module_card-header_QEX_8 card-module_add-to-cart_3naMa">
                    <h4>Bookings</h4>
                </div>
                <?php require( __DIR__.'/shipments-table.php'  ); ?>
                <ul class="card-module_menu-container_3Hu8Y">
                
                    <li><a href="<?php echo esc_url( home_url( 'index.php/request' ) ); ?>">Make New Booking</a></li>
                    <li><a href="#">Edit Bookings(only none collected)</a></li>
                    <li><a href="#">Delete Booking</a></li>
                </ul>
            </div>
        </div>
        <br><br>
        <div class="cell medium-6 align-self-stretch dashboard-module_animate-card_2Y2b-">
            <div class="card-module_card_3XoAu">
                <div class="card-module_card-header_QEX_8 card-module_credit-card_2ccm3">
                    <h4>Payments &amp; Invoices</h4>
                </div>
                <ul class="card-module_menu-container_3Hu8Y">
                    <li><a data-react-link="true" href="/invoices">Invoices</a></li>
                    <li><a data-react-link="true" href="/pay_methods.php">Payment Methods</a></li>
                </ul>
            </div>
        </div>
       
        <div class="cell medium-6 align-self-stretch dashboard-module_animate-card_2Y2b-">
            <div class="card-module_card_3XoAu">
                <div class="card-module_card-header_QEX_8 card-module_user-account_2Lhi4">
                    <h4>Customer Information</h4>
                </div>
                <ul class="card-module_menu-container_3Hu8Y">
                    <li><a data-react-link="true" href="/account/personal-details">Personal Details</a></li>
                    <li><a data-react-link="true" href="/account/address-book">Address Book</a></li>
                    <li><a data-react-link="true" href="/account/newsletters">Newsletter Subscriptions</a></li>
                </ul>
            </div>
        </div>
       
    </div>
</section>


<style>
.h1, .h2, .h3, .h4, .p {
    margin-top: 0;
}

.h1 {
    color: #4d4d4f;
    font-size: 20px;
    margin-bottom: 20px;
}

.h1 {
    font-size: 2em;
    margin: 0.67em 0;
}


.h1 {
    display: block;
    font-size: 2em;
    margin-block-start: 0.67em;
    margin-block-end: 0.67em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    font-weight: bold;
}



.body {
    margin: 0;
    padding: 0;
    background: #f4f4f4;
    font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;
    font-weight: normal;
    line-height: 1.5;
    color: #0a0a0a;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

:root {
    --swiper-theme-color: #007aff;
}

.html {
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    font-size: 13px;
}

.html {
    line-height: 1.15;
    -webkit-text-size-adjust: 100%;
}


.dashboard-module_animate-card_2Y2b->div {
    min-height: 185px;
    min-width: 380px;
}


.card-module_card_3XoAu {
    margin-bottom: 0;
    padding: 19px 24px 24px;
}


.card-module_card_3XoAu {
    padding: 24px;
}

.card-module_card_3XoAu {
    padding: 16px;

    border-radius: 3px;
    -webkit-box-shadow: 0 2px 2px 0 rgb(77 77 79 / 8%), 0 0 2px 0 rgb(77 77 79 / 16%);
    box-shadow: 0 2px 2px 0 rgb(77 77 79 / 8%), 0 0 2px 0 rgb(77 77 79 / 16%);
    margin-bottom: 10px;

    padding: 15px 0 0 20px;
}

*, *::before, *::after {
    -webkit-box-sizing: inherit;
    box-sizing: inherit;
}

.div {
    display: block;
}

.grid-x {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: horizontal;
    -webkit-box-direction: normal;
    -ms-flex-flow: row wrap;
    flex-flow: row wrap;
}

.body {
    margin: 0;
    padding: 0;
    background: #f4f4f4;
    font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;
    font-weight: normal;
    line-height: 1.5;
    color: #0a0a0a;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

</style>