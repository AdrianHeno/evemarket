<!doctype html>
<html>
    <head>
        <title>harviacode.com - codeigniter crud generator</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
        <link rel="stylesheet" href="<?php echo base_url('assets/datatables/dataTables.bootstrap.css') ?>"/>
        <style>
            body{
                padding: 15px;
            }
        </style>
    </head>
    <body>
        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-4">
                <h2 style="margin-top:0px">Trading List</h2>
            </div>
            <div class="col-md-4 text-center">
                <div style="margin-top: 4px"  id="message">
                    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                </div>
            </div>
            <div class="col-md-4 text-right">
                <?php echo anchor(site_url('trading/create'), 'Create', 'class="btn btn-primary"'); ?>
	    </div>
        </div>
        <table class="table table-bordered table-striped" id="mytable">
            <thead>
                <tr>
                    <th width="80px">No</th>
		    <th>From Region</th>
		    <th>To Region</th>
		    <th>Item Id</th>
		    <th>Name</th>
		    <th>From Price</th>
		    <th>To Price</th>
		    <th>Margin</th>
		    <th>Percentage</th>
		    <th>Volume</th>
		    <th>Days</th>
		    <th>Last Modified</th>
		    <th>Action</th>
                </tr>
            </thead>
	    <tbody>
            <?php
            $start = 0;
            foreach ($trading_data as $trading)
            {
                ?>
                <tr>
		    <td><?php echo ++$start ?></td>
		    <td><?php echo $trading->from_region ?></td>
		    <td><?php echo $trading->to_region ?></td>
		    <td><?php echo $trading->item_id ?></td>
		    <td><?php echo $trading->name ?></td>
		    <td><?php echo $trading->from_price ?></td>
		    <td><?php echo $trading->to_price ?></td>
		    <td><?php echo $trading->margin ?></td>
		    <td><?php echo $trading->percentage ?></td>
		    <td><?php echo $trading->volume ?></td>
		    <td><?php echo $trading->days ?></td>
		    <td><?php echo $trading->last_modified ?></td>
		    <td style="text-align:center" width="200px">
			<?php 
			echo anchor(site_url('trading/read/'.$trading->id),'Read'); 
			echo ' | '; 
			echo anchor(site_url('trading/update/'.$trading->id),'Update'); 
			echo ' | '; 
			echo anchor(site_url('trading/delete/'.$trading->id),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
			?>
		    </td>
	        </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/datatables/jquery.dataTables.js') ?>"></script>
        <script src="<?php echo base_url('assets/datatables/dataTables.bootstrap.js') ?>"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#mytable").dataTable();
            });
        </script>
    </body>
</html>