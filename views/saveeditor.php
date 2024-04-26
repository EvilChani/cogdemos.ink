	<?php require_once( "__head.php" ); ?>
</head>
<body>
	<?php require_once( "__menu.php" ); ?>
<div class="site">
<div class="site-content">
      <div class="container" style="margin-top:120px;">
<header>
  <h1>Developer Slot Editor for &ldquo;<?=$game['title'];?>&rdquo;</h1>
</header>

<fieldset id="filtergames">
	<a class="btn btn-dark" href="/dashboard" onclick="grayOut();">Return to Dashboard</a>
	<a class="btn btn-danger" href="logout.php" onclick="grayOut();">Logout</a>
</fieldset>

<form method="POST" action="/devsave/<?=$game['id'];?>">
<div class="card">
<div class="card-body">

<div class="row">
<?php
foreach( $saves as $save )
	{

		echo "<div class='col-md-4 col-sm-12 mb-4'>";
		echo "<div class='input-group mb-3'><div class='input-group-prepend'><span class='input-group-text'>SCENE: </span></div>";
		echo "<input name='sceneName' class='form-control' value=\"{$save['sceneName']}\">";
		echo "</div></div>";

		foreach( $save['stats'] as $key => $value )
			{
					echo "<div class='col-md-4 col-sm-12'>";
					echo "<div class='input-group mb-3'><div class='input-group-prepend'><span class='input-group-text text-muted'>{$key}: </span></div>";
					if( $value == "false" )
						{
							echo "<select class='form-control' name='{$key}'>";
							echo "<option value='true'>true</option>";
							echo "<option value='false' selected=selected>false</option>";
							echo "</select>";
						}
					elseif( $value == "true" )
						{
							echo "<select class='form-control' name='{$key}'>";
							echo "<option value='true' selected=selected>true</option>";
							echo "<option value='false'>false</option>";
							echo "</select>";
						}
					else
						{
							echo "<input name='{$key}' class='form-control' value=\"{$value}\">";
						}
					echo "</div></div>";
			}
	}
?>
</div>


</div>
</div>


<div class="row">
<div class="col-12 text-right">
<button type="submit" class="btn btn-success">Submit Edited Slot</button>
</div>
</div>

</form>

</div>

</div>
<hr>
</div>
</div>
	<?php require_once( "__foot.php" ); ?>
</div>
	<?php require_once( "__code.php" ); ?>