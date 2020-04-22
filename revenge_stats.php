<?php 
if ( ! defined( 'ABSPATH' ) ) exit; 

function displayRevengeHelper($record, $threat) {
	if (isset($record[$threat])) {
		echo $record[$threat];
	} else {
		echo '0';
	}
}

function displayRevengeCount($label, $threat, $thisWeeks, $lastWeeks, $thisMonths, $lastMonths, $fullTotals) {
	?>
	<tr>
		<th scope="row"><?php echo($label); ?></th>
		<td><?php displayRevengeHelper($thisWeeks, $threat); ?></td>
		<td><?php displayRevengeHelper($lastWeeks, $threat); ?></td>
		<td><?php displayRevengeHelper($thisMonths, $threat); ?></td>
		<td><?php displayRevengeHelper($lastMonths, $threat); ?></td>
		<td><?php displayRevengeHelper($fullTotals, $threat); ?></td>
	</tr>
	<?php
}

function displayPageContent() {
	global $wpdb;

	$sql = "SELECT threat, count(*) as count FROM ".$wpdb->prefix . "revenge WHERE created_at BETWEEN '".gmdate("Y-m-d", strtotime("last sunday"))." 00:00:00' AND '".gmdate("Y-m-d", strtotime("this saturday"))." 23:59:59' GROUP BY threat";

	$thisWeeks = [];
	$results = $wpdb->get_results($sql);
	foreach($results as $key => $row) {
		$thisWeeks[$row->threat] = $row->count;
	}

	$d1 = strtotime("last sunday");
	$sSunday = gmdate("Y-m-d", strtotime("-1 week", $d1));
	$sSaturday = gmdate("Y-m-d", strtotime("last saturday"));

	$sql = "SELECT threat, count(*) as count FROM ".$wpdb->prefix . "revenge WHERE created_at BETWEEN '".$sSunday." 00:00:00' AND '".$sSaturday." 23:59:59' GROUP BY threat";

	$lastWeeks = [];
	$results = $wpdb->get_results($sql);
	foreach($results as $key => $row) {
		$lastWeeks[$row->threat] = $row->count;	
	}

	$sql = "SELECT threat, count(*) as count FROM ".$wpdb->prefix . "revenge WHERE created_at BETWEEN '".gmdate("Y-m-d", strtotime("first day of this month"))." 00:00:00' AND '".gmdate("Y-m-d", strtotime("last day of this month"))." 23:59:59' GROUP BY threat";

	$thisMonths = [];
	$results = $wpdb->get_results($sql);
	foreach($results as $key => $row) {
		$thisMonths[$row->threat] = $row->count;
	}

	$d1 = strtotime("first day of this month");
	$bLastMonth = gmdate("Y-m-d", strtotime("-1 month", $d1));
	$d2 = strtotime("first day of this month");
	$eLastMonth = gmdate("Y-m-d", strtotime("-1 day", $d2));

	$sql = "SELECT threat, count(*) as count FROM ".$wpdb->prefix . "revenge WHERE created_at BETWEEN '".$bLastMonth." 00:00:00' AND '".$eLastMonth." 23:59:59' GROUP BY threat";

	$lastMonths = [];
	$results = $wpdb->get_results($sql);
	foreach($results as $key => $row) {
		$lastMonths[$row->threat] = $row->count;
	}

	$sql = "SELECT threat, count(*) as count FROM ".$wpdb->prefix . "revenge GROUP BY threat";

	$fullTotals = [];
	$results = $wpdb->get_results($sql);
	foreach($results as $key => $row) {
		$fullTotals[$row->threat] = $row->count;
	}
	?>
	<h1>Revenge Stats</h1>
	<?php
	$sql = "SELECT count(*) as count FROM ".$wpdb->prefix . "revenge";
	$results = $wpdb->get_results($sql);
	if (isset($results[0]) && $results[0]->count > 1) {
		echo "<h2>Revenge has attacked and brought down ".$results[0]->count." threats!</h2>";
	}
	?>
	<table class="revenge">
		<thead>
			<tr>
				<th scope="col">Threat</th>
				<th scope="col">This Week</th>
				<th scope="col">Last Week</th>
				<th scope="col">This Month</th>
				<th scope="col">Last Month</th>
				<th scope="col">Total</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			displayRevengeCount('Bot Attack', 'bot attack', $thisWeeks, $lastWeeks, $thisMonths, $lastMonths, $fullTotals);
			displayRevengeCount('Author Query String', 'author query vulnerability', $thisWeeks, $lastWeeks, $thisMonths, $lastMonths, $fullTotals);
			displayRevengeCount('Wp-config file targeted', 'wp-config file targeted', $thisWeeks, $lastWeeks, $thisMonths, $lastMonths, $fullTotals);
			displayRevengeCount('Phpmyadmin folder targeted', 'phpmyadmin targeted', $thisWeeks, $lastWeeks, $thisMonths, $lastMonths, $fullTotals);
			displayRevengeCount('Phpunit folder targeted', 'phpunit targeted', $thisWeeks, $lastWeeks, $thisMonths, $lastMonths, $fullTotals);
			displayRevengeCount('PHP code injection', 'php code injection', $thisWeeks, $lastWeeks, $thisMonths, $lastMonths, $fullTotals);
			displayRevengeCount('Password injection', 'password injection', $thisWeeks, $lastWeeks, $thisMonths, $lastMonths, $fullTotals);
			displayRevengeCount('DFS targeted', 'dfs targeted', $thisWeeks, $lastWeeks, $thisMonths, $lastMonths, $fullTotals);
			displayRevengeCount('Autodiscover folder targeted', 'autodiscover targeted', $thisWeeks, $lastWeeks, $thisMonths, $lastMonths, $fullTotals);
			displayRevengeCount('Wpad targeted', 'wpad targeted', $thisWeeks, $lastWeeks, $thisMonths, $lastMonths, $fullTotals);
			displayRevengeCount('Webconfig file targeted', 'webconfig targeted', $thisWeeks, $lastWeeks, $thisMonths, $lastMonths, $fullTotals);
			displayRevengeCount('Vuln file targeted', 'vuln targeted', $thisWeeks, $lastWeeks, $thisMonths, $lastMonths, $fullTotals);
			displayRevengeCount('Base64 script injection', 'base64 script injection', $thisWeeks, $lastWeeks, $thisMonths, $lastMonths, $fullTotals);
			displayRevengeCount('Script tag injection', 'script injection', $thisWeeks, $lastWeeks, $thisMonths, $lastMonths, $fullTotals);
		?>
		</tbody>
	</table>		
	<?php
}

displayPageContent();