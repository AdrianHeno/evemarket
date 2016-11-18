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
        <h2 style="margin-top:0px">Trading <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="int">From Region <?php echo form_error('from_region') ?></label>
            <input type="text" class="form-control" name="from_region" id="from_region" placeholder="From Region" value="<?php echo $from_region; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">To Region <?php echo form_error('to_region') ?></label>
            <input type="text" class="form-control" name="to_region" id="to_region" placeholder="To Region" value="<?php echo $to_region; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Item Id <?php echo form_error('item_id') ?></label>
            <input type="text" class="form-control" name="item_id" id="item_id" placeholder="Item Id" value="<?php echo $item_id; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Name <?php echo form_error('name') ?></label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?php echo $name; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">From Price <?php echo form_error('from_price') ?></label>
            <input type="text" class="form-control" name="from_price" id="from_price" placeholder="From Price" value="<?php echo $from_price; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">To Price <?php echo form_error('to_price') ?></label>
            <input type="text" class="form-control" name="to_price" id="to_price" placeholder="To Price" value="<?php echo $to_price; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Margin <?php echo form_error('margin') ?></label>
            <input type="text" class="form-control" name="margin" id="margin" placeholder="Margin" value="<?php echo $margin; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Percentage <?php echo form_error('percentage') ?></label>
            <input type="text" class="form-control" name="percentage" id="percentage" placeholder="Percentage" value="<?php echo $percentage; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Volume <?php echo form_error('volume') ?></label>
            <input type="text" class="form-control" name="volume" id="volume" placeholder="Volume" value="<?php echo $volume; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Days <?php echo form_error('days') ?></label>
            <input type="text" class="form-control" name="days" id="days" placeholder="Days" value="<?php echo $days; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Last Modified <?php echo form_error('last_modified') ?></label>
            <input type="text" class="form-control" name="last_modified" id="last_modified" placeholder="Last Modified" value="<?php echo $last_modified; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('trading') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>