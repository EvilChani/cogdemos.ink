<span class="navbar-toggle" id="js-navbar-toggle">
		<i class="fas fa-bars"></i>
</span>
<a href="/" class="logo"><img src="/img/logo.png"></a>

<ul class="main-nav" id="js-menu">
	<?php if( !empty( $_SESSION['memberlog'] ) ){ ?>
		<li>
				<?php if( $favorite ) { ?>
					<a href="javascript:void(0);" id="favorite2" class="favclass nav-links favorite"><i class="fa fa-sm fa-heart"></i> Favorite</a>
				<?php }else{ ?>
					<a href="javascript:void(0);" id="favorite2" class="favclass nav-links"><i class="fa fa-sm fa-heart"></i> Favorite</a>
				<?php } ?>
		</li>
	<?php } ?>
		<li>
				<?=$feedback;?>
		</li>
	<?php if( !empty( $_SESSION['memberlog'] ) ){ ?>
		<li>
				<a href="/report/<?=$thisgame['id'];?>" target="_blank" class="nav-links"><i class="fa fa-sm fa-exclamation-circle"></i> Report</a>
		</li>
	<?php } ?>
	<?php if( empty( $_SESSION['memberlog'] ) ){ ?>
		<li>
				<span class="nav-links" style="color:rgba(255,255,255,0.25);">|</span>
		</li>
		<li>
				<a href="javascript:void(0);" class="nav-links" id="login"><i class="fa fa-sm fa-sign-in-alt"></i> Login</a>
		</li>
	<?php } ?>
		<li>
				<span class="nav-links" style="color:rgba(255,255,255,0.25);">|</span>
		</li>
		<li>
				<a href="https://www.choiceofgames.com/make-your-own-games/choicescript-intro/" class="nav-links" target="_blank"><i class="fa fa-sm fa-external-link-alt"></i> Make Games with ChoiceScript</a>
		</li>
		<li>
				<span class="nav-links" style="color:rgba(255,255,255,0.25);">|</span>
		</li>
		<li>
				<a href="javascript:void(0);" id="ddclose" class="nav-links"><i class="fa fa-lg fa-times-circle"></i></a>
		</li>
</ul>
