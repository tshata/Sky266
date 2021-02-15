<style>
	@media only screen and (max-width: 760px),
	(min-device-width: 768px) and (max-device-width: 1024px)  {
		/* Force table to not be like tables anymore */
		table#shipment-history,
		table#shipment-history thead,
		table#shipment-history tbody,
		table#shipment-history th,
		table#shipment-history td,
		table#shipment-history tr {
			display: block;
		}
		/* Hide table headers (but not display: none;, for accessibility) */
		table#shipment-history thead tr {
			position: absolute;
			top: -9999px;
			left: -9999px;
		}
		table#shipment-history tr {
			border: 1px solid #ccc;
			text-align: initial !important;
		}
		table#shipment-history td {
			/* Behave  like a "row" */
			border: none;
			border-bottom: 1px solid #eee;
			position: relative;
			padding-left: 50% !important;
			text-align: initial !important;
		}
		table#shipment-history td:before {
			/* Now like a table header */
			position: absolute;
			/* Top/left values mimic padding */
			top: 6px;
			left: 6px;
			width: 45%;
			padding-right: 10px;
			white-space: nowrap;
		}
		#wpcargo-result-print  #shipment-history tbody tr {
			background-color: #d6d6d6;
				width: 100%;
		}
		#wpcargo-result-print  #shipment-history tbody tr:nth-child(odd) {
			background-color: #f1f1f1;
		}
		/*
		Label the data
		*/
		table#shipment-history td:nth-of-type(1):before { content: "<?php esc_html_e('Date', 'wpcargo'); ?>"; font-weight:700; }
		table#shipment-history td:nth-of-type(2):before { content: "<?php esc_html_e('Time', 'wpcargo'); ?>"; font-weight:700; }
		table#shipment-history td:nth-of-type(3):before { content: "<?php esc_html_e('Location', 'wpcargo'); ?>"; font-weight:700; }
		table#shipment-history td:nth-of-type(4):before { content: "<?php esc_html_e('Status', 'wpcargo'); ?>"; font-weight:700; }
		table#shipment-history td:nth-of-type(5):before { content: "<?php esc_html_e('Remarks', 'wpcargo'); ?>"; font-weight:700; }
		table#shipment-history td:nth-of-type(6):before { content: "<?php echo do_action('wpcargo_shipment_history_responsive_header'); ?>"; font-weight:700; }
	}
</style>
<div id="wpcargo-history-section" class="wpcargo-history-details print-section" style="margin-top:40px;">
    <p class="header-title"><strong><?php echo apply_filters( 'wpc_shipment_history_header', esc_html__( 'Shipment History' , 'wpcargo') ); ?></strong></p>
    <?php do_action('before_wpcargo_shipment_history', $shipment->ID) ?>
    <table id="shipment-history" class="table wpcargo-table" style="width: 100%;">
        <thead>

        <?php
            $history_fields_array = array(
                'date' => array(
                    'label' => esc_html__('Date', 'wpcargo'),
                    'field' => 'text',
                    'required' => 'false',
                    'options' => array()
                ),
                'updated-name' => array(
                    'label' => esc_html__('Updated By', 'wpcargo'),
                    'field' => 'text',
                    'required' => 'false',
                    'options' => array()
                ),
                'status' => array(
                    'label' => esc_html__('Status', 'wpcargo'),
                    'field' => 'select',
                    'required' => 'false',
                    'options' => $wpcargo->status
                ),
                'remarks' => array(
                    'label' => esc_html__('Remarks', 'wpcargo'),
                    'field' => 'textarea',
                    'required' => 'false',
                    'options' => array()
                ),
            );

        ?>
        <tr>
			<?php foreach( $history_fields_array as $history_name => $history_fields ): ?>
				<th><?php echo $history_fields['label']; ?></th>
			<?php endforeach; ?>
            <?php do_action('wpcargo_shipment_history_header'); ?>
        </tr>
        </thead>
        <tbody>
        <?php
            $shipment_history = maybe_unserialize( get_post_meta( $shipment->ID, 'wpcargo_shipments_update', true ) );
            if(!empty($shipment_history)){
                foreach(array_reverse($shipment_history) as $shipments){
                    ?>
                    <tr class="history-row">
						<?php foreach( $history_fields_array as $history_name => $history_fields ): ?>
							<td class="history-data <?php echo wpcargo_to_slug($history_name); ?> <?php echo wpcargo_to_slug($shipments[$history_name]); ?>">
                            <?php echo ($history_name=='date')? date_format(date_create($shipments[$history_name]),'d-M-Y')." (".$shipments['time'].")": $shipments[$history_name]; ?>
                            </td>
						<?php endforeach; ?>
                        <?php do_action('wpcargo_shipment_history_data', $shipments ); ?>
                    </tr>
                    <?php
                }
            }
        ?>
        </tbody>
    </table>
    <?php do_action('after_wpcargo_shipment_history', $shipment->ID) ?>
</div>