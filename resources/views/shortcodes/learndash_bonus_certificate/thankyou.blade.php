

<html lang="en">

<head>

	<title>Thank You</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
	.btn-outline-success{
		background-color: #F36F65;
		color: white;
		border-radius: 0px!important;
		border: 0px!important;
	}
	.text-success{
		color: #F36F65!important;
	}
</style>
<body>
<div class="vh-100 d-flex justify-content-center align-items-center">
	<div class="col-md-4">
		<div class="card  bg-white shadow p-5">
			<div class="mb-4 text-center">
				<svg xmlns="http://www.w3.org/2000/svg" class="text-success" width="75" height="75"
					 fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
					<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
					<path
						d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z" />
				</svg>
			</div>
			<div class="text-center">
				<h1>Thank You ! <?=$user_name?></h1>
				<p>Congratulations! you’ve successfully completed the audio course on  <?php echo $course_title; ?> </p>
				<a href="<?php echo $course_link;?>" class="btn btn-outline-success">Download Certificate</a>
				<a href="<?php echo home_url(); ?>" class="btn btn-outline-success">Home</a>
			</div>
		</div>
	</div>
</div>
</body>

</html>


