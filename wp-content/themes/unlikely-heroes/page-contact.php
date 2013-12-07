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
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="col-md-6">
					<div class="row">
						<div class="col-md-12">
							<p>We love to hear from you blah…</p>
							<hr>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<address>
								Unlikey Heroes <br>
								P.O.Box 6143 <br>
								North Hollywood, CA 91603
							</address>

							<phone>(999)999-9999</phone>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<form role="form">
						<div class="form-group">
							<label for="name">Name</label>
							<input type="text" class="form-control" id="name">
						</div>
						<div class="form-group">
							<label for="email">Email address</label>
							<input type="email" class="form-control" id="email">
						</div>
						<div class="form-group">
							<label for="subject">Subject</label>
							<input type="text" class="form-control" id="subject">
						</div>
						<div class="form-group">
							<label for="message">Message</label>
							<textarea class="form-control" id="message" row="5"></textarea>
						</div>
						<button type="submit" class="btn">Submit</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>