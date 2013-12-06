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
		<div class="col-md-offset-1 col-md-10">

			<div class="row">
				<div class="col-md-6">
					<h2 class="text-center">One Time</h2>

					<form class="form-horizontal" action="" role="form">

						<ul class="list-group">
							<li class="list-group-item">
								<div class="form-group">
									
									<div class="radio">
										<label>
											<input type="radio" name="oneTimeDonations" id="onTimeDonations1" value="option1"> $5 - Stop a rape
										</label>

									</div>
								</div>
							</li>
							<li class="list-group-item">
								<div class="form-group">
									
									<div class="radio">
										<label>
											<input type="radio" name="oneTimeDonations" id="onTimeDonations2" value="option2"> $500 - Rescue a child for a month
										</label>

									</div>
								</div>
							</li>
							<li class="list-group-item">
								<div class="form-group">
									
									<div class="radio">
										<label>
											<input type="radio" name="oneTimeDonations" id="onTimeDonations3" value="option3"> $5000 - Rescue a girl for a year
										</label>

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

					</form>


				</div>
				<div class="col-md-6">
					<h2 class="text-center">Monthly Partner</h2>

					<form class="form-horizontal" action="" role="form">

						

						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<div class="radio">
									<label>
										<input type="radio"> $5 - Stop a rape
									</label>
								</div>
							</div>
						</div>


						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<div class="radio">
									<label>
										<input type="radio"> $500 - Rescue a child for a month
									</label>
								</div>
							</div>
						</div>


						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<div class="radio">
									<label>
										<input type="radio"> $5000 - Rescue a girl for a year
									</label>
								</div>
							</div>
						</div>


						<div class="form-group">
							<div class="col-sm-10">
								<input type="email" class="form-control" id="inputEmail3" placeholder="Email">
							</div>
						</div>

						

					</form>

				</div>
			</div>





		</div>
	</div>
</div>

<!-- <div class="container">
	<div class="row">
		<div class="col-lg-12">
			<?php //get_template_part( 'loop', 'page' ); ?>
		</div>
	</div>
</div> -->

<?php get_footer(); ?>
