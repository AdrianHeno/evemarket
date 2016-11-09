<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Region Trading</title>

	<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.3.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function() {
	$('.table').DataTable();
    } );
</script>
</head>
<body>
    <div class="container">
		<div class="starter-template">
			<nav class="navbar navbar-default">
				<div class="container-fluid">
					<div class="navbar-header">
						<h3>The Forge vs Heimatar</h3>
					</div>
					<a href="<?php echo $base_url ?>/myjql/jql_form" class="btn btn-success" style="float:right;">New Search</a>
				</div>
			</nav>
			<div class="row control-group">
				<div class="form-group col-xs-12">
					<table class="table table-striped">
						<tr>
							<th>Item</th>
							<th>Forge Price</th>
							<th>Heimatar Price</th>
							<th>Margin</th>
							<th>%</th>
							<th>Weekly Volume</th>
							<th>Days of Sale</th>
						</tr>
						<?php
							foreach($prices as $price){
						?>
						<tr>
							<td><?php echo $price['name']?></td>
							<td><?php echo number_format($price['10000002'], 2)?></td>
							<td><?php echo number_format($price['10000030'], 2)?></td>
							<td><?php echo number_format($price['margin'], 2)?></td>
							<td><?php echo number_format($price['percentage'], 2)?></td>
							<td><?php echo $price['movement']['total_volume']?></td>
							<td><?php echo $price['movement']['days_of_sale']?></td>
						</tr>
						<?php								
							}
						?>
					</table>
				</div>
			</div>
		</div>
    </div>

</body>
</html>