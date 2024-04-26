	<?php require_once( "__head.php" ); ?>
</head>
<body>
	<?php require_once( "__menu.php" ); ?>
<div class="site">
    <div class="site-content">
          <div class="container" style="margin-top:120px;">
    <header>
      <h1>Uploading Files for &ldquo;<?=$game['title'];?>&rdquo;</h1>
    </header>

    <fieldset id="filtergames">
        <a class="btn btn-dark" href="/dashboard" onclick="grayOut();">Return to Dashboard</a>
        <a class="btn btn-danger" href="logout.php" onclick="grayOut();">Logout</a>
    </fieldset>

    <?php
        if( !empty( $_SESSION['errormessage'] ) )
            {
                foreach( $_SESSION['errormessage'] as $message )
                    {
                        echo '<div class="alert alert-danger alert-dismissible fade show mt-4"><i class="fa fa-exclamation-circle"></i> '.$message.'<button type="button" class="close" data-dismiss="alert"><span >&times;</span></button></div>';
                    }
                unset( $_SESSION['errormessage'] );
            }
        if( !empty( $_SESSION['noticemessage'] ) )
            {
                foreach( $_SESSION['noticemessage'] as $message )
                    {
                        echo '<div class="alert alert-info alert-dismissible fade show mt-4"><i class="fa fa-info-circle"></i> '.$message.'<button type="button" class="close" data-dismiss="alert"><span >&times;</span></button></div>';
                    }
                unset( $_SESSION['noticemessage'] );
            }
        if( !empty( $_SESSION['successmessage'] ) )
            {
                foreach( $_SESSION['successmessage'] as $message )
                    {
                        echo '<div class="alert alert-success alert-dismissible fade show mt-4"><i class="fa fa-check-circle"></i> '.$message.'<button type="button" class="close" data-dismiss="alert"><span >&times;</span></button></div>';
                    }
                unset( $_SESSION['successmessage'] );
            }
    ?>

    <div class="card">
    <div class="card-body">

    <div class="progress">
        <div class="bar progress-bar progress-striped progress-bar-success"></div >
        <div class="percent">0%</div >
    </div>

    <div class="status alert alert-success" style="display:none"></div>

    <hr>

<div class="row">

<div class="col col-md-6 col-sm-12">
    <section>
        <h4>Upload / Overwrite Files:</h4>
        <p class="mb-1"><b>.txt</b>, <b>.jpg</b>, <b>.jpeg</b>, <b>.png</b>, <b>.gif</b>, <b>.mp3</b>, <b>.wav</b>, and <b>.svg</b> only.</p>
        <p class="mt-1">No spaces or special characters are allowed in filenames.</p>

        <form action="/assets/<?=$game['id'];?>" method="post" enctype="multipart/form-data" class="pure-form">
            <input type="hidden" name="token" value="<?=$_SESSION['token'];?>" />
            <div class="row">
                <div class="col col-9">
                <input type="file" name="images[]" multiple="multiple" id="images" class="button button-nopad button-secondary" style="float:none;clear:both;display:block;width:98%;padding:2%;">
                </div>
                <div class="col col-3">
                <input type="submit" value="Upload" class="button button-success">
                </div>
            </div>
        </form>

    <?php if($_SESSION['override']!=1){ ?>
		<div class="note text-muted my-2">Max file size is currently 2MB per txt file and you may only upload a maximum of 20 files at one time.</div>
    <?php }else{ ?>
		<div class="note text-muted my-2">Your account has no filesize restrictions. You may only upload a maximum of 20 files at one time.</div>
    <?php } ?>

    <p class="alert text-muted"><i>The title picture that shows for your game on CoGDemos.ink listings can be changed by uploading a file named <strong>dashpic.jpg</strong> The dashpic.jpg image will be cropped to <b>320x180</b> pixels.</i></p>

        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h4 class="mt-4">Current Scene Files:</h4>
                <ul id="scenelist" class="list unordered-list">
                    <?php
                    foreach( $files as $key => $file )
                        {
                            $ext = explode( ".", $file );
                            if( $ext[1] == "txt" )
                                {
                                    echo "<li><a href='/unfile/{$game['id']}/{$key}' class='delete' data-confirm='Are you sure to delete {$file}?'><i class='fa fa-times-circle text-danger' data-toggle='tooltip' title='Delete {$file}'></i></a> {$file}</li>";
                                }
                        }
                    ?>
                </ul>
                <p id="noscenelist" class="alert alert-info" style="display:none"><i class="fa fa-info-circle"></i> <a href="/upload/<?=$game['id'];?>">Refresh page</a> to view updated scene list.</p>
            </div>
            <div class="col-md-6 col-sm-12 mb-4">
                <a href="https://cogdemos.ink/play/<?=$game['author'];?>/<?=$game['url'];?>/mygame/scenes" class="btn btn-outline-info text-dark btn-block my-2" target="_blank"><i class="fa fa-link"></i> Directory Link</a>
                <br><br>
                <div><?=number_format( $game['words'] );?> Words</div>
                <a href="/words/<?=$game['id'];?>" class="btn btn-outline-default text-dark btn-block my-2"><i class="fa fa-sort-amount-up"></i> Update Word Count</a>
            </div>
        </div>
    </section>
</div>

<div class="col col-md-6 col-sm-12">
    <h5 class="text-warning"><i class="fa fa-exclamation-triangle"></i> Please Be Advised:</h5>
    <div class="alert alert-warning">
        <p>Your files may be deleted, your account may be terminated, and you may face legal action for the following:</p>
        <ul>
        <li>Uploading files that are not directly related and relevant to ChoiceScript game hosting.</li>
        <li>Publishing, posting, uploading, distributing or disseminating any inappropriate, profane, derogatory, defamatory, infringing, obscene, indecent or unlawful topic, material, or information.</li>
        <li>Uploading files that contain software or other material protected by intellectual property laws or by rights of privacy of publicity unless you own or control such rights, or have received all necessary consents.</li>
        <li>Uploading files that contain viruses, corrupted files, or any other similar software or programs that may damage the operation of another's computer.</li>
        </ul>
    </div>
</div>

</div>

</div>
</div>






</div>

</div>
<hr>
</div>
	<?php require_once( "__foot.php" ); ?>
</div>
	<?php require_once( "__code.php" ); ?>

<script type="text/javascript" src="/js/jquery.form.min.js"></script>
<script type="text/javascript" src="/js/upload.js"></script>
<script>
var deleteLinks = document.querySelectorAll('.delete');
for (var i = 0; i < deleteLinks.length; i++) {
  deleteLinks[i].addEventListener('click', function(event) {
      event.preventDefault();
      var choice = confirm(this.getAttribute('data-confirm'));
      if (choice) {
        window.location.href = this.getAttribute('href');
      }
  });
}
</script>