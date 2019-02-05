<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">SmiloPay</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php $pageName = basename($_SERVER['PHP_SELF']); ?>
                <li <?php echo ($pageName == 'index.php') ? "class='active'" : ""; ?>><a href="/">Home<span class="sr-only">(current)</span></a></li>
                <li <?php echo ($pageName == 'information.php') ? "class='active'" : ""; ?>><a href="/pages/information.php">Information</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="https://smilo.io" target="_blank">Smilo.io</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Links<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="https://smilo.io" target="_blank">Website</a></li>
                        <li><a href="https://smilowallet.io">Wallet</a></li>
                        <li><a href="http://testnet-explorer.smilo.network" target="_blank">Block Explorer</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="https://medium.com/smilo-platform" target="_blank">Medium</a></li>
                        <li><a href="https://twitter.com/SmiloPlatform" target="_blank">Twitter</a></li>
                        <li><a href="https://t.me/SmiloPlatform" target="_blank">Telegram</a></li>
                        <li><a href="https://www.reddit.com/user/smilo-platform" target="_blank">Reddit</a></li>
                        <li><a href="https://www.linkedin.com/company/27205452/" target="_blank">LinkedIn</a></li>
                        <li><a href="https://www.facebook.com/SmiloPlatform/" target="_blank">Facebook</a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>