<nav class="navbar navbar-default" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button id="navmenu" type="button" class="navbar-toggle collapsed btn btn-info btn-embossed btn-lg" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="dashboard.php"><img src="../../public/images/vyvasynclogotype.png" width="150px" class="img-responsive"></a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li><a title="Dashboard" id="dasht" href="dashboard.php"><i class="fa fa-home"></i> Dashboard</a></li> 
                <li><a title="Notifications" id="notifyt" href="notifications.php"><i class="fa fa-globe"></i> Notifications<span id="header_notifications_total" class="notification-bold"></span></a></li> 
                <li><a title="Notifications" id="settings" href="password.php"><i class="fa fa-cog"></i> Settings</a></li> 
                <li><a id="signoutt" title="Logout" class='btn-signout' onclick='logout()' type="button"><i class="fa fa-sign-out"></i> Sign out</a></button></li>
            </ul>
        </div>
    </div>
</nav>