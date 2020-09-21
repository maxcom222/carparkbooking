<?php
$map = array(
	'pjActionStep0' => 0,
	'pjActionStep1' => 1,
	'pjActionStep2' => 2,
	'pjActionStep3' => 3,
	'pjActionStep4' => 4,
	'pjActionStep5' => 5,
	'pjActionStep6' => 6,
	'pjActionStep7' => 7
);
?>
<div class="progress-wrap">
	<div class="_ps _ps1<?php echo $map[$_GET['action']] == 1 ? '_cur' : ($map[$_GET['action']] > 1 ? '_pas' : NULL); ?>"></div>
	<div class="_ps _ps2<?php echo $map[$_GET['action']] == 2 ? '_cur' : ($map[$_GET['action']] > 2 ? '_pas' : NULL); ?>"></div>
	<div class="_ps _ps3<?php echo $map[$_GET['action']] == 3 ? '_cur' : ($map[$_GET['action']] > 3 ? '_pas' : NULL); ?>"></div>
	<div class="_ps _ps4<?php echo $map[$_GET['action']] == 4 ? '_cur' : ($map[$_GET['action']] > 4 ? '_pas' : NULL); ?>"></div>
	<div class="_ps _ps5<?php echo $map[$_GET['action']] == 5 ? '_cur' : ($map[$_GET['action']] > 5 ? '_pas' : NULL); ?>"></div>
	<div class="_ps _ps6<?php echo $map[$_GET['action']] == 6 ? '_cur' : ($map[$_GET['action']] > 6 ? '_pas' : NULL); ?>"></div>
	<div class="_ps _ps7<?php echo $map[$_GET['action']] == 7 ? '_cur' : ($map[$_GET['action']] > 7 ? '_pas' : NULL); ?>"></div>
	<div class="progress-back"></div>
	<div class="progress-front" style="width: <?php echo 165.166 * ($map[$_GET['action']] - 1); ?>px"></div>
</div>