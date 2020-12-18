<!-- main sidebar -->
<aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= base_url('dashboard'); ?>" class="brand-link">
        <img src="<?= base_url('assets'); ?>/img/logo3.png" alt="AdminLTE Logo" class="brand-image" style="opacity: .8" width="70px">
        <span class="brand-text font-weight-light"><img src="<?= base_url('assets'); ?>/img/logo2.png" alt="" width="120px"></span>
    </a>
    
    <!-- Sidebar -->
    <div class="sidebar nav-collapse-hide-child nav-pills">
        <!-- Sidebar user panel (optional) -->
        <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div> -->
        
        <!-- Sidebar Menu -->
        <nav class="mt-3">
            <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                <?php foreach($sidebar['menu'] as $key => $value): ?>
                    <?php if($this->_general_m->getOnce('id_menu', 'user_menu_sub', array('id_menu' => $value['id_menu']))): ?>
                        <!-- jika ada submenunya pilih treeview -->
                        <li class="nav-item has-treeview <?php 
                                if($this->uri->segment(1) == $value['url']){
                                    echo("menu-open");
                                }
                            ?>">
                            <a href="<?php base_url($value['url']); ?>" class="nav-link <?php 
                                if($this->uri->segment(1) == $value['url']){
                                    echo "active";
                                }
                            ?>">
                                <i class="nav-icon <?= $value['icon']; ?>"></i>
                                <p>
                                    <?= $value['title']; ?>
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>    
                            <ul class="nav nav-treeview">
                                <?php foreach($sidebar['submenu'] as $v): ?>
                                    <?php if($v['id_menu'] == $value['id_menu']): ?>
                                        <li class="nav-item">
                                            <a href="<?= base_url($v['url']); ?>" class="nav-link <?php 
                                            $thisUrlSub = $this->uri->segment(1).'/'.$this->uri->segment(2);
                                            
                                            if($thisUrlSub == $v['url']){
                                                echo "active";
                                            } elseif($thisUrlSub == $v['url'].'/'){
                                                echo "active";
                                            } else {
                                                //nothing
                                            } ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p><?= $v['title']; ?></p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- jika tidak ada submenunya pilih yang single link -->
                        <!-- FIXME hapus penanda dashboard -->
                        <?php if($value['id_menu'] != 0): ?>
                            <li class="nav-item">
                                <a href="<?= base_url($value['url']); ?>" class="nav-link  <?php 
                                    if($this->uri->segment(1) == $value['url']){
                                        echo "active";
                                    }
                                ?>">
                                    <i class="nav-icon <?= $value['icon']; ?>"></i>
                                    <p>
                                        <?= $value['title']; ?>
                                    </p>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if($this->session->userdata('role_id') == 1): ?>
                    <li class="nav-header">MAINTENANCE MENU</li>
                    
                    <!-- menu application setting -->
                    <?php $sidebar['settings'] = $this->_general_m->getOnce('*', 'user_menu', array('id_menu' => '5')) ?>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon <?= $sidebar['settings']['icon']; ?>"></i>
                            <p>
                                <?= $sidebar['settings']['title']; ?>
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php $sidebar['settings']['submenu'] = $this->_general_m->getAll('id_menu_sub, title, url', 'user_menu_sub', array('id_menu' => '5')); ?>
                            <?php foreach($sidebar['settings']['submenu'] as $value): ?>
                                <li class="nav-item">
                                    <a href="<?= base_url().$value['url']; ?>" id="<?= $value['id_menu_sub']; ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p><?= $value['title']; ?></p>
                                    </a>
                                </li>
                            <?php endforeach;?>
                        </ul>
                    </li>

                    <!-- menu general setting -->
                    <?php $sidebar['settings'] = $this->_general_m->getOnce('*', 'user_menu', array('id_menu' => '7')) ?>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon <?= $sidebar['settings']['icon']; ?>"></i>
                            <p>
                                <?= $sidebar['settings']['title']; ?>
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php $sidebar['settings']['submenu'] = $this->_general_m->getAll('id_menu_sub, title, url', 'user_menu_sub', array('id_menu' => '7')); ?>
                            <?php foreach($sidebar['settings']['submenu'] as $value): ?>
                                <li class="nav-item">
                                    <a href="<?= base_url().$value['url']; ?>" id="<?= $value['id_menu_sub']; ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p><?= $value['title']; ?></p>
                                    </a>
                                </li>
                            <?php endforeach;?>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </nav><!-- /.sidebar-menu -->
    </div><!-- /.sidebar -->
</aside><!-- /main sidebar -->