<?php
//dd($url);
//?>
<style>
	.certificate{
		box-sizing:border-box;
		/* outline:1px solid ;*/
	}
	.certificate body{
		background: #ffffff;
		background: linear-gradient(to bottom, #ffffff 0%,#e1e8ed 100%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#e1e8ed',GradientType=0 );
		height: 100%;
		margin: 0;
		background-repeat: no-repeat;
		background-attachment: fixed;

	}

	.certificate .wrapper-1{
		width:100%;
		height:100vh;
		display: flex;
		flex-direction: column;
	}
	.certificate .wrapper-2{
		padding :30px;
		text-align:center;
	}
	.certificate h1 {
		font-size: 32px;
		line-height: 1.2;
		margin-bottom: 0;
	}
	.certificate .wrapper-2 p{
		margin:0;
		font-size:1.3em;
		color:#aaa;
		font-family: 'Source Sans Pro', sans-serif;
		letter-spacing:1px;
	}
	#BonusCertificateContent {
	display: none;
	}


</style>


<div class=content certificate>
	<div class="wrapper-1">
		<div class="wrapper-2">
			<p> if you want to download Certificate! Click on button below  </p>
			<a href="<?php echo $url ?>  " target="_blank">Download Certificate</a>
		</div>

	</div>
</div>



<link href="https://fonts.googleapis.com/css?family=Kaushan+Script|Source+Sans+Pro" rel="stylesheet">
