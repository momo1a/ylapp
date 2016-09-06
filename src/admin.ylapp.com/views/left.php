<aside class="main-sidebar">
    <section class="sidebar">
    <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">当前用户</li>
            <!-- Optionally, you can add icons to the links -->
            <!--<li class="active"><a href="#"><i class="fa fa-link"></i> <span>Link</span></a></li>
            <li><a href="#"><i class="fa fa-link"></i> <span>Another Link</span></a></li>
            <li class="treeview">
                <a href="#"><i class="fa fa-link"></i> <span>Multilevel</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#">Link in level 2</a></li>
                    <li><a href="#">Link in level 2</a></li>
                </ul>
            </li>-->
            <?php if(!empty($menu)){foreach($menu as $value){?>
                <li class="treeview"><a href="#"><i class="fa fa-link"></i> <span><?php echo $value['title'];?></span></a></li>
            <?php }}?>
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>