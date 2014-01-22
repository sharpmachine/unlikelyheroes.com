<?php 
$api = 'http://ignitiondeck.com/id/?action=get_extensions';
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $api);

$json = curl_exec($ch);
curl_close($ch);
$data = json_decode($json);
?>
<div class="wrap">
	<h2>IgnitionDeck Extensions</h2>
	<?php
	foreach ($data as $item) {
		$title = $item->title;
		$desc = $item->short_desc;
		$link = $item->link;
		$thumbnail = $item->thumbnail;
		?>
		<div class="extension">
			<h3 class="extension-title"><?php echo $title; ?></h3>
			<div class="extension-image" style="background-image: url(<?php echo $thumbnail; ?>);"></div>
			<p class="extension-desc"><?php echo $desc; ?></p>
			<div class="extension-link">
				<button class="button" onclick="location.href='<?php echo $link; ?>'">Get Extension</button>
			</div>
		</div>
	<?php } ?>
</div>