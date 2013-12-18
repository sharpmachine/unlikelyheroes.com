<?php
$form = array();
	if (isset($epp_fes) && $epp_fes == 1) {
		$form[] = array(
			'label' => __('Paypal Email', 'memberdeck'),
			'value' => (isset($paypal_email) ? $paypal_email : ''),
			'name' => 'paypal_email',
			'type' => 'email',
			'class' => 'required',
			'wclass' => 'form-row'
			);
	}
	$form[] = array(
		'value' => __('Submit', 'memberdeck'),
		'name' => 'payment_settings_submit',
		'type' => 'submit'
		);
	$payment_form = new MD_Form($form);
	$output = $payment_form->build_form();
	echo '<div class="memberdeck">';
	include_once MD_PATH.'templates/_mdProfileTabs.php';
	echo '<ul class="md-box-wrapper full-width cf"><li class="md-box half"><div class="md-profile"><form method="POST" action="" id="payment-settings" class="payment-settings">';
	echo $output;
	echo '</div></li>';
	do_action('md_payment_settings_extrafields');
	echo '</form></ul></div>';
?>