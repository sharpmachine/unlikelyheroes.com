<?php get_header(); ?>

<div class="jumbotron jumbotron-about">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h1>
					<span><?php the_title(); ?></span>
				</h1>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="row main-content sixteen-vr">
		<form class="form-horizontal" action="" role="form">
			<div class="col-md-offset-1 col-md-10">
				<div class="row">

					<div class="col-md-6">
						<h2 class="text-center">One Time</h2>
						<ul class="list-group">
							<li class="list-group-item">
								<div class="form-group">
									<div class="radio">
										<input type="radio" name="oneTimeDonations" id="onTimeDonations1" value="option1"> 
										<label for="onTimeDonations1">$5 - Stop a rape</label>
									</div>
								</div>
							</li>
							<li class="list-group-item">
								<div class="form-group">
									<div class="radio">
										<input type="radio" name="oneTimeDonations" id="onTimeDonations2" value="option2"> 
										<label for="onTimeDonations2">$500 - Rescue a child for a month</label>
									</div>
								</div>
							</li>
							<li class="list-group-item">
								<div class="form-group">
									<div class="radio">
										<input type="radio" name="oneTimeDonations" id="onTimeDonations3" value="option3"> 
										<label for="onTimeDonations3">$5000 - Rescue a girl for a year</label>
									</div>
								</div>
							</li>
							<li class="list-group-item">
								<div class="input-group">
									<span class="input-group-addon">$</span>
									<input type="text" class="form-control" id="inputEmail3" placeholder="Other Amount…">
								</div>
							</li>
						</ul>
					</div>

					<div class="col-md-6">
						<h2 class="text-center">Monthly Partner</h2>
						<ul class="list-group">
							<li class="list-group-item">
								<div class="form-group">
									<div class="radio">
										<input type="radio" name="monthlyPartnerDonation" id="monthlyPartnerDonation1" value="option1"> 
										<label for="monthlyPartnerDonation1">$5 - Stop a rape</label>
									</div>
								</div>
							</li>
							<li class="list-group-item">
								<div class="form-group">
									<div class="radio">
										<input type="radio" name="monthlyPartnerDonation" id="monthlyPartnerDonation2" value="option2"> 
										<label for="monthlyPartnerDonation2">$500 - Rescue a child for a month</label>
									</div>
								</div>
							</li>
							<li class="list-group-item">
								<div class="form-group">
									<div class="radio">
										<input type="radio" name="monthlyPartnerDonation" id="monthlyPartnerDonation3" value="option3"> 
										<label for="monthlyPartnerDonation3">$5000 - Rescue a girl for a year</label>
									</div>
								</div>
							</li>
							<li class="list-group-item">
								<div class="input-group">
									<span class="input-group-addon">$</span>
									<input type="text" class="form-control" id="inputEmail3" placeholder="Other Amount…">
								</div>
							</li>
						</ul>
					</div>
				</div>

				<div class="row">
					<h2 class="text-center">Pay By:</h2>
					<div class="col-md-12">
						<div class="donation-payment-buttons text-center">
							<button class="btn"><span class="icon-credit"></span> Credit Card</button>
							<span class="or">Or</span>
							<button class="btn"><span class="icon-paypal"></span> Paypal</button>
						</div>
					</div>
				</div>

			</div>
		</form>
	</div>
</div>

<?php get_footer(); ?>