<style>
#payment_details ::placeholder {
    /* Chrome, Firefox, Opera, Safari 10.1+ */
    color: black;
    opacity: 1;
    /* Firefox */
}

:-ms-input-placeholder {
    /* Internet Explorer 10-11 */
    color: black;
}

::-ms-input-placeholder {
    /* Microsoft Edge */
    color: black;
}

#payment_details input {
    float: right;
    width: 70%;
    background: white;
    margin-top: -.8em;
    color: black;
}

#payment_details {
    padding: 1em;
    background: dimgray;
}

hr {
    color: dimgray;
    background-color: dimgray;
}

#payment_date {
    color: black;
}

#time {
    float: right;
    background: green;
    padding: .3em;
    margin-top: -.57em;
}

/* The Modal (background) */
.modal {
    display: none;
    /* Hidden by default */
    position: fixed;
    /* Stay in place */
    z-index: 1;
    /* Sit on top */
    padding-top: 100px;
    /* Location of the box */
    left: 0;
    top: 0;
    width: 100%;
    /* Full width */
    height: 100%;
    /* Full height */
    overflow: auto;
    /* Enable scroll if needed */
    background-color: rgb(0, 0, 0);
    /* Fallback color */
    background-color: rgba(0, 0, 0, 0.4);
    /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 37%;
}

/* The Close Button */


.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

#submit_payment {
    float: right;
}

#company_details {
    color: black;
}
</style>

<!--
<h2>Modal Example</h2>

 Trigger/Open The Modal
<button id="myBtn">Open Modal</button>   -->

<!-- The Modal -->
<div id="myModal" class="modal">

    <!-- Modal content -->
    <div>

        <div class="modal-content" id="payment_details" style="font-size:1em;">
            <p class="wpcargo-label" style="margin-bottom: 1px; font-size: 1.5em;color:white;"><strong
                    id="heading"><?php // apply_filters( 'wpc_multiple_package_header', esc_html_e( 'Mpesa/Ecocash Payment Details', 'wpcargo' ) ); ?></strong>
            </p>
            <div style="background:darkgray;padding:1em;">
                <p style="border-bottom: 1px solid white;"><span id="company_details"></span> <span id="time"> 05:00
                    </span> </p><br><br>
                <div>
                    <div id="bankSelect" class="sf_columns column_3" style="display: none">
                        <input type="hidden" id="bank" />
                        <span id="payment_idenTifier"></span><select class="input" id="payment_identifierb">
                            <option value=" ">Select Bank Name</option>
                            <option value="Standard Bank">Standard Bank</option>
                            <option value="FNB Lesotho">FNB Lesotho</option>
                            <option value="NedBank Lesotho">NedBank Lesotho</option>
                        </select>
                    </div>
                    <div id="otherMethods" class="sf_columns column_3">
                        <span id="payment_idenTifier"></span><input class="input" type="text" id="payment_identifier"
                            placeholder="Phone Number Used" data-required="true">
                    </div>

                    <div class="sf_columns column_3" id="reference_div">
                        <span>Payment Reference:</span> <input type="text" class="input" id="payment_reference"
                            placeholder="Reference" data-required="true" data-email="true">
                    </div>
                </div>
                <div>
                    <div class="sf_columns column_3">
                        <br><br> Date of Payment:<input type="date" class="input" id="payment_date"
                            placeholder="Date of Payment" data-required="true" data-confirm="true">
                    </div>
                    <div class="sf_columns column_3">
                        <br><br> Amount Paid:<input type="money" class="input" id="payment_amount"
                            placeholder="Amount Paid" data-required="true" data-confirm="true">
                    </div>
                </div>
                <br>
                <button type="button" id="submit_payment" onclick="submit_payment()">Submit</button>
                <button style="float:right;margin: 0 .4em 0 .4em;" type="button" id="modal_close">Close</button><br><br>
            </div>
        </div>
    </div>

</div>

<script>
/* Get the modal       */
var modal = document.getElementById("myModal");

// Get the button that opens the modal
//var btn = document.getElementById("myBtn");


// Get the <span> element that closes the modal
var closeBtn = document.getElementById("modal_close");
var x;

var submit = document.getElementById("submit_payment");
submit.onclick = function() {

    var y = document.getElementById("bankSelect");

    x = (window.getComputedStyle(y).display === "block") ? document.getElementById("payment_identifierb").value :
        document.getElementById("payment_identifier").value;
    //$('#payment_items #payment_identifier').val() ;
    var amount = document.getElementById('payment_amount').value;
    var booking_fee = $('#bookingFee').val();
    if (amount < booking_fee) {
        alert("Please pay amount equal or greater than M" + $('#bookingFee').val() + " to activate your booking");
        modal.style.display = "none";
        document.getElementById('payment_amount').reset();
    } else if (amount >= booking_fee) {
        $.ajax({
            url: wpcargoAJAXHandler.ajax_url,
            type: "POST",
            data: {
                action: 'client_save_payment_action',
                post_id: document.getElementById("post_id").value,
                payment_date: $('#payment_items #payment_date').val(),
                payment_method: $('#payment_items #payment_method').val(),
                payment_identifier: x,
                payment_reference: $('#payment_items #payment_reference').val(),
                payment_amount: $('#payment_items #payment_amount').val(),
            },
            success: function(data) {
                alert(data);
                /*$('#info').html( data );
                    $('#form').hide();
                    $('#info').show(); */
            },
            error: function(errorThrown) {
                alert("Error retrieving data. Please try again.");
                /*$('#info').html("Error retrieving data. Please try again.");
                $('#form').hide();
                $('#info').show(); */
            }

        });
        $('.wpcargo-loading').show();
        modal.style.display = "none";
        window.location.href = "Home";
    }
}


document.getElementById("nextBtn").innerHTML = "Pay";
$("#nextBtn").css("background", "grey");
$("#nextBtn").attr("disabled", true);

var btn = document.getElementById("nextBtn");

btn.onclick = function() {


    function startTimer(duration, display) {
        var timer = duration,
            minutes, seconds;
        setInterval(function() {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.textContent = minutes + ":" + seconds;

            if (--timer < 0) {
                timer = duration;
                modal.style.display = "none";
            }
        }, 1000);

    }

    document.getElementById("time").innerHTML = timer();

    function timer() {
        var fiveMinutes = 60 * 5,
            display = document.querySelector('#time');
        startTimer(fiveMinutes, display);
    };
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
closeBtn.onclick = function() {
    modal.style.display = "none";
}


// When the user clicks anywhere outside of the modal, close it
/* window.onclick = function(event) {
     if (event.target == modal) {
         modal.style.display = "none";
     }
 } */
</script>