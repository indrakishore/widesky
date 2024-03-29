<?php
    include('connect.php');
    $userid = $_SESSION['user_id'];
?>
<?php
    $query = mysqli_query($con,"select * from user where email='$userid'");
    $result = mysqli_fetch_array($query);
    $name = $result['name'];
    $rank = $result['rank'];
?>
<nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="home.php">WIDESKY E-RETAILS PVT. LTD.</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <!-- Call Search -->
                    <li><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a></li>
                    <!-- #END# Call Search -->
                    <li class="pull-right"><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="material-icons">more_vert</i></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <img src="images/user.png" width="48" height="48" alt="User" />
                </div>
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><?php echo $name."  "; ?><span class="badge"><?php if($rank!='no'){ echo $rank;}?></span></div>
                    <div class="email"><?php echo $userid;?></div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="user-profile.php"><i class="material-icons">person</i>Profile</a></li>
                            <li><a href="change-password.php"><i class="material-icons">vpn_key</i>Change Password</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="http://www.ultimateshop.online"><i class="material-icons">shopping_cart</i>Ultimate Shop</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="logout.php"><i class="material-icons">input</i>Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="header">MAIN NAVIGATION</li>
                    <li>
                        <a href="home.php">
                            <i class="material-icons">dashboard</i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="user-profile.php">
                            <i class="material-icons">person</i>
                            <span>User Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">adjust</i>
                            <span>Pin Management</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="pin-request.php">Pin Request</a>
                            </li>
                            <li>
                                <a href="pin.php">View Pins</a>
                            </li>
                            <li>
                                <a href="pin-transfer.php">Pin Transfer</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="join.php">
                            <i class="material-icons">person</i>
                            <span>Join User</span>
                        </a>
                    </li>
                    <li>
                        <a href="vendor-registration.php">
                            <i class="material-icons">list</i>
                            <span>Vendor Registration</span>
                        </a>
                    </li>
                    <li>
                        <a href="wallet.php">
                            <i class="material-icons">account_balance_wallet</i>
                            <span>Wallet</span>
                        </a>
                    </li>
                    <li>
                        <a href="partnership-program.php">
                            <i class="material-icons">star</i>
                            <span>Partnership Program</span>
                        </a>
                    </li>
                    <li>
                        <a href="tree.php">
                            <i class="material-icons">change_history</i>
                            <span>Tree</span>
                        </a>
                    </li>
                    <li>
                        <a href="sponsor-tree.php">
                            <i class="material-icons">change_history</i>
                            <span>Sponsor Tree/Purchase Income</span>
                        </a>
                    </li>
                    <li>
                        <a href="upgrade-package.php">
                            <i class="material-icons">arrow_upward</i>
                            <span>Upgrade Package</span>
                        </a>
                    </li>
                    <li>
                        <a href="payment-received-history.php">
                            <i class="material-icons">account_balance_wallet</i>
                            <span>Payment Received History</span>
                        </a>
                    </li>
                    <li>
                        <a href="bv-received-history.php">
                            <i class="material-icons">label</i>
                            <span>BV History</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; <?php echo date('Y'); ?> <a href="javascript:void(0);">WIDESKY E-RETAILS PVT. LTD.</a>.
                </div>
                <div class="version">
                    <b>Version: </b> 2.0.0 Beta
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
        <!-- Right Sidebar -->
        <aside id="rightsidebar" class="right-sidebar">
            <ul class="nav nav-tabs tab-nav-right" role="tablist">
                <li role="presentation" class="active"><a href="#skins" data-toggle="tab">SKINS</a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active in active" id="skins">
                    <ul class="demo-choose-skin">
                        <li data-theme="red" class="active">
                            <div class="red"></div>
                            <span>Red</span>
                        </li>
                        <li data-theme="pink">
                            <div class="pink"></div>
                            <span>Pink</span>
                        </li>
                        <li data-theme="purple">
                            <div class="purple"></div>
                            <span>Purple</span>
                        </li>
                        <li data-theme="deep-purple">
                            <div class="deep-purple"></div>
                            <span>Deep Purple</span>
                        </li>
                        <li data-theme="indigo">
                            <div class="indigo"></div>
                            <span>Indigo</span>
                        </li>
                        <li data-theme="blue">
                            <div class="blue"></div>
                            <span>Blue</span>
                        </li>
                        <li data-theme="light-blue">
                            <div class="light-blue"></div>
                            <span>Light Blue</span>
                        </li>
                        <li data-theme="cyan">
                            <div class="cyan"></div>
                            <span>Cyan</span>
                        </li>
                        <li data-theme="teal">
                            <div class="teal"></div>
                            <span>Teal</span>
                        </li>
                        <li data-theme="green">
                            <div class="green"></div>
                            <span>Green</span>
                        </li>
                        <li data-theme="light-green">
                            <div class="light-green"></div>
                            <span>Light Green</span>
                        </li>
                        <li data-theme="lime">
                            <div class="lime"></div>
                            <span>Lime</span>
                        </li>
                        <li data-theme="yellow">
                            <div class="yellow"></div>
                            <span>Yellow</span>
                        </li>
                        <li data-theme="amber">
                            <div class="amber"></div>
                            <span>Amber</span>
                        </li>
                        <li data-theme="orange">
                            <div class="orange"></div>
                            <span>Orange</span>
                        </li>
                        <li data-theme="deep-orange">
                            <div class="deep-orange"></div>
                            <span>Deep Orange</span>
                        </li>
                        <li data-theme="brown">
                            <div class="brown"></div>
                            <span>Brown</span>
                        </li>
                        <li data-theme="grey">
                            <div class="grey"></div>
                            <span>Grey</span>
                        </li>
                        <li data-theme="blue-grey">
                            <div class="blue-grey"></div>
                            <span>Blue Grey</span>
                        </li>
                        <li data-theme="black">
                            <div class="black"></div>
                            <span>Black</span>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>
        <!-- #END# Right Sidebar -->
    </section>
