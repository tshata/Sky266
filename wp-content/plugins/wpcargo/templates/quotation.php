<style>
#main-div {
    display: grid;
    grid-template-columns: 50% 50%;
    grid-column-gap: .8em;

}

#main-div {
    margin: 1em;
}

#headers_ {
    color: black;
    float: right;
}

.wpcargo-label {
    color: black;

}

#summary_hr {
    margin-top: -.2em;
}

.bordered {
    border: 1px solid white;
    padding: 1em;
}

#myTable {
    font-size: 1em;
}

#headers_ {
    width: 13em;
    text-align: right;
    margin-right: .3em;
}

td {
    vertical-align: top;
}
</style>

<div>
    <div class="wpcargo-col-md-12">
        <h2><?php echo apply_filters('wpc_shipment_details_label', esc_html__('Booking Summary', 'wpcargo' ) ); ?> <span
                style="font-size: 14px; font-style: italic; float: right" id="booking_reference">Booking Reference:
                <b></b></span> </h2>
        <hr style="border: 1px solid black;" /><br>
    </div>
    <div id="main-div">
        <div id="shipper_details" class="bordered">
            <p class="wpcargo-label"><strong><?php esc_html_e('SHIPPERS DETAILS', 'wpcargo'); ?></strong></p>
            <hr id="summary_hr">
            <table id="myTable">
                <tr>
                    <td>
                        <p class="wpcargo-label-info" id="headers_"><strong>Company name: </strong></p>
                    </td>
                    <td>
                        <p id="label_info_company"></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="wpcargo-label-info" id="headers_"><strong>Shipper Name: </strong></p>
                    </td>
                    <td>
                        <p id="label_info_name"></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="wpcargo-label-info" id="headers_"><strong>Shipper Surname: </strong></p>
                    </td>
                    <td>
                        <p id="label_info_surname"></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="wpcargo-label-info" id="headers_"><strong>Main Contacts: </strong></p>
                    </td>
                    <td>
                        <p id="label_info_M_contacts"></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="wpcargo-label-info" id="headers_"><strong>Alternative: </strong></p>
                    </td>
                    <td>
                        <p id="label_info_alternative"></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="wpcargo-label-info" id="headers_"><strong>Email: </strong></p>
                    </td>
                    <td>
                        <p id="label_info_email"></p>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <p class="wpcargo-label-info" id="headers_"><strong>Origin(from): </strong></p>
                    </td>
                    <td>
                        <p id="label_info_origin"></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="wpcargo-label-info" id="headers_"><strong>Destination(to): </strong></p>
                    </td>
                    <td>
                        <p id="label_info_dest"></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p></p>
                    </td>
                    <td>
                        <p></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="wpcargo-label-info" id="headers_"><strong>Mode of Transport: </strong></p>
                    </td>
                    <td>
                        <p id="label_info_mode"></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="wpcargo-label-info" id="headers_"><strong>Service Type: </strong></p>
                    </td>
                    <td>
                        <p id="label_info_service"></p>
                    </td>
                </tr>
            </table>
        </div>
        <div id="parcels_details" class="bordered">
            <p class="wpcargo-label">
                <strong><?php apply_filters( 'wpc_multiple_package_header', esc_html_e( 'PACKAGE DETAILS', 'wpcargo' ) ); ?></strong>
            </p>
            <hr id="summary_hr">
            <table id="myTable">
                <tr>
                    <td>
                        <p class="wpcargo-label-info" id="headers_"><strong> Goods description: </strong></p>
                    </td>
                    <td>
                        <p id="label_info_goods_desc"></p>
                    </td>
                </tr>
                <tr>

                </tr>
                <tr>
                    <td>
                        <p class="wpcargo-label-info" id="headers_"><strong> Total weight estimate (kg):</strong></p>
                    </td>
                    <td>
                        <p id="label_info_est_weight"></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="wpcargo-label-info" id="headers_"><strong> Total Volume Estimate (cbm):</strong></p>
                    </td>
                    <td>
                        <p id="label_info_est_cbm"></p>
                    </td>
                </tr>
                <tr>

                </tr>
                <tr>
                    <td>
                        <p class="wpcargo-label-info" id="headers_"><strong> Special Services: </strong></p>
                    </td>
                    <td>
                        <p id="label_info_special"></p>
                    </td>
                </tr>
            </table>
        </div>
        <div id="collection_details" class="bordered">
            <p class="wpcargo-label">
                <strong><?php apply_filters( 'wpc_multiple_package_header', esc_html_e( 'COLLECTION DETAILS', 'wpcargo' ) ); ?></strong>
            </p>
            <hr id="summary_hr">
            <table id="myTable">
                <tr>
                    <td style="vertical-align: baseline;">
                        <p class="wpcargo-label-info" id="headers_"><strong>Collection Address: </strong></p>
                    </td>
                    <td>
                        <p id="label_info_shipper"></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p></p>
                    </td>
                    <td>
                        <p></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="wpcargo-label-info" id="headers_"><strong>Collection Person: </strong></p>
                    </td>
                    <td>
                        <p id="label_info_person"></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="wpcargo-label-info" id="headers_"><strong> Collection Contacts:</strong></p>
                    </td>
                    <td>
                        <p id="label_info_main_contacts"></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="wpcargo-label-info" id="headers_"><strong>Alternative Contacts: </strong></p>
                    </td>
                    <td>
                        <p id="label_info_alt_contacts"></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="wpcargo-label-info" id="headers_"><strong>Collection Reference: </strong></p>
                    </td>
                    <td>
                        <p id="label_info_reference"></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="wpcargo-label-info" id="headers_"><strong>Collection Instructions:</strong></p>
                    </td>
                    <td>
                        <p id="label_info_instructions"></p>
                    </td>
                </tr>
            </table>
        </div>
        <div id="delivery_details" class="bordered">
            <p class="wpcargo-label">
                <strong><?php apply_filters( 'wpc_multiple_package_header', esc_html_e( 'DELIVERY DETAILS', 'wpcargo' ) ); ?></strong>
            </p>
            <hr id="summary_hr">
            <table id="myTable">
                <tr>
                    <td>
                        <p class="wpcargo-label-info" id="headers_"><strong>Delivery Address:</strong></p>
                    </td>
                    <td>
                        <p id="label_info_daddress"></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p></p>
                    </td>
                    <td>
                        <p></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="wpcargo-label-info" id="headers_"><strong> Delivery Person:</strong></p>
                    </td>
                    <td>
                        <p id="label_info_dperson"></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="wpcargo-label-info" id="headers_"><strong> Delivery Contacts:</strong></p>
                    </td>
                    <td>
                        <p id="label_info_main_dcontacts"></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="wpcargo-label-info" id="headers_"><strong> Alternative Contacts:</strong></p>
                    </td>
                    <td>
                        <p id="label_info_alt_dcontacts"></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="wpcargo-label-info" id="headers_"><strong>Delivery Reference:</strong></p>
                    </td>
                    <td>
                        <p id="label_info_dreference"></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="wpcargo-label-info" id="headers_"><strong>Delivery Instructions:</strong></p>
                    </td>
                    <td>
                        <p id="label_info_dinstructions"></p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div style="margin-left:1.4em;">
        <p class="wpcargo-label">
            <strong><?php apply_filters( 'wpc_multiple_package_header', esc_html_e( 'TERMS AND CONDITIONS', 'wpcargo' ) ); ?></strong>
        </p>
        <hr id="summary_hr">
        <ol>
            <li>Condition 1</li>
            <li>Condition 2</li>
            <li>Condition 3</li>
            <li>Condition 4</li>
        </ol>
    </div>
</div>