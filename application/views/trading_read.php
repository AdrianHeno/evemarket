<!doctype html>
<html>
    <head>
        <title>harviacode.com - codeigniter crud generator</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
        <style>
            body{
                padding: 15px;
            }
        </style>
    </head>
    <body>
        <h2 style="margin-top:0px">Trading Read</h2>
        <table class="table">
	    <tr><td>From Region</td><td><?php echo $from_region; ?></td></tr>
	    <tr><td>To Region</td><td><?php echo $to_region; ?></td></tr>
	    <tr><td>Item Id</td><td><?php echo $item_id; ?></td></tr>
	    <tr><td>Name</td><td><?php echo $name; ?></td></tr>
	    <tr><td>From Price</td><td><?php echo $from_price; ?></td></tr>
	    <tr><td>To Price</td><td><?php echo $to_price; ?></td></tr>
	    <tr><td>Margin</td><td><?php echo $margin; ?></td></tr>
	    <tr><td>Percentage</td><td><?php echo $percentage; ?></td></tr>
	    <tr><td>Volume</td><td><?php echo $volume; ?></td></tr>
	    <tr><td>Days</td><td><?php echo $days; ?></td></tr>
	    <tr><td>Last Modified</td><td><?php echo $last_modified; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('trading') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>