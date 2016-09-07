<?php $this->load->view('head');?>
<!-- Left side column. contains the logo and sidebar -->
<?php $this->load->view('left');?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <strong>
                <div class="btn btn-block btn-primary btn-lg" style="background: #f5f5f5;color: #3c8dbc">
                <div style="font-weight: bold">当前用户</div>
                <small><?php if($is_super){echo '超级'.$vars[1][0]['role'];}else{ echo $vars[1][0]['role'];}?></small>
                </div>
            </strong>
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Your Page Content Here -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

<!-- Main Footer -->
<?php $this->load->view('foot');?>
