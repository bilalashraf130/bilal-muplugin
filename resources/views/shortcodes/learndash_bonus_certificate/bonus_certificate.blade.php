<style>
	.form-style{
		max-width: 450px;
		background: #FAFAFA;
		padding: 30px;
		margin: 50px auto;
		box-shadow: 1px 1px 25px rgba(0, 0, 0, 0.35);
		border-radius: 10px;
		border: 2px solid #305A72;
		font-family: 'Montserrat', sans-serif;
	}
	.form-style ul{
		padding:0;
		margin:0;
		list-style:none;
	}
	.form-style ul li{
		display: block;
		margin-bottom: 10px;
		min-height: 35px;
	}
	.form-style ul li  .field-style{
		box-sizing: border-box;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		padding: 8px;
		outline: none;
		border: 1px solid #B0CFE0;
		font-family: 'Montserrat', sans-serif;
	}
	.form-style ul li  .field-style:focus{
		box-shadow: 0 0 5px #B0CFE0;
		border:1px solid #B0CFE0;
	}
	.form-style ul li .field-split{
		width: 100%;
	}
	.form-style ul li .field-full{
		width: 100%;
	}
	.form-style ul li input.align-left{
		float:left;
	}
	.form-style ul li input.align-right{
		float:right;
	}
	.form-style ul li textarea{
		width: 100%;
		height: 100px;
	}
	.form-style ul li input[type="button"],
	.form-style ul li input[type="submit"] {


		background-color: #f36f5b;
		border: 2px solid #f36f5b;
		display: inline-block;
		cursor: pointer;
		color: #FFFFFF;
		padding: 8px 18px;
		text-decoration: none;
	}
	.get_feedback{
		background-color: #f36f5b;
		border: 2px solid #f36f5b;
		display: inline-block;
		cursor: pointer;
		color: #FFFFFF;
		padding: 8px 18px;
		text-decoration: none;
	}
	.alert {
		position: relative;
		padding: 0.75rem 1.25rem;
		margin-bottom: 1rem;
		border: 1px solid transparent;
		border-radius: 0.25rem;
		font-size: 14px;
	}
	.alert-danger {
		color: #1b1e21;
		background-color: #d6d8d9;
		border-color: #c6c8ca;
	}


	.rate {
		float: left;
		height: 46px;
		padding: 0 10px;
	}
	.rate:not(:checked) > input {
		position:absolute;
		/*top:-9999px;*/
		display: none;
	}
	.rate:not(:checked) > label {
		float:right;
		width:1em;
		overflow:hidden;
		white-space:nowrap;
		cursor:pointer;
		font-size:30px;
		color:#ccc;
	}
	.rate:not(:checked) > label:before {
		content: '★ ';
	}
	.rate > input:checked ~ label {
		color: #ffc700;
	}
	.rate:not(:checked) > label:hover,
	.rate:not(:checked) > label:hover ~ label {
		color: #deb217;
	}
	.rate > input:checked + label:hover,
	.rate > input:checked + label:hover ~ label,
	.rate > input:checked ~ label:hover,
	.rate > input:checked ~ label:hover ~ label,
	.rate > label:hover ~ input:checked ~ label {
		color: #c59b08;
	}
	.upload-btn-wrapper {
		position: relative;
		overflow: hidden;
		display: inline-block;
	}

	.linkedin_btn {
		border: 2px solid gray;
		color: gray;
		background-color: white;
		padding: 8px 20px;
		border-radius: 8px;
		font-size: 20px;
		font-weight: bold;
	}

	.upload-btn-wrapper input[type=file] {
		font-size: 100px;
		position: absolute;
		left: 0;
		top: 0;
		opacity: 0;
	}

	.head {
		font-size: 25px;
		font-weight: 200;
	}

	.blue-btn:hover,
	.blue-btn:active,
	.blue-btn:focus,
	.blue-btn {
		background: transparent;
		border: 2px solid #f36f5b;
		border-radius: 8px;
		color: grey;
		font-size: 16px;
		margin-bottom: 20px;
		outline: none !important;
		padding: 10px 20px;
		background-color: white;
	}

	.fileUpload {
		position: relative;
		overflow: hidden;
		margin-top: 0;
		width: 75%;
	}

	.fileUpload input.uploadlogo {
		position: absolute;
		top: 0;
		right: 0;
		margin: 0;
		padding: 0;
		font-size: 20px;
		cursor: pointer;
		opacity: 0;
		filter: alpha(opacity=0);
		width: 100%;
		height: 100%;
	}

	/*Chrome fix*/
	input::-webkit-file-upload-button {
		cursor: pointer !important;
		height: 30px;
		width: 80%;
	}

	#loader{
		height: 1em;
		width: 1em;
		display: block;
		position: absolute;
		top: 50%;
		left: 50%;
		margin-left: -0.5em;
		margin-top: -0.5em;
		content: "";
		-webkit-animation: spin 1s ease-in-out infinite;
		animation: spin 1s ease-in-out infinite;
		background: url("/wp-content/plugins/woocommerce/assets/images/icons/loader.svg") center center;
		background-size: cover;
		line-height: 1;
		text-align: center;
		font-size: 2em;
		color: rgba(0,0,0,.75);
	}

	#loading{
		height: 100%;
		position: absolute;
		left: 0;
		top: 0;
		width: 100%;
		background-color: #ffffffa6;
		z-index: 10;
		display:none;
	}
	#info{
		display:none;
	}
</style>

<div>


<form method="post" id="bonus_certificate" class="form-style" enctype="multipart/form-data">
	<div id="loading">
		<div id="loader">

		</div>

	</div>
	<h4>Enter your assignment right here!</h4>
	<h3>Upload LinkedIn Screenshot</h3>

	<ul>
		<input type="hidden" name="course_id" value="<?php echo learndash_get_course_id() ?>"/>
		<div class="fileUpload blue-btn btn width100">
			<span><b>Upload Linkedin Screenshot : No file selected</b></span>
			<input type="file" name="linkedin_review" id="linkedin_review"  class="uploadlogo" />
		</div>
		<li>
			<div class="rate" >

				<input type="radio" id="star5" name="course_review_star" value="5" />
				<label for="star5" title="text">5 stars</label>
				<input type="radio" id="star4" name="course_review_star" value="4" />
				<label for="star4" title="text">4 stars</label>
				<input type="radio" id="star3" name="course_review_star" value="3" />
				<label for="star3" title="text">3 stars</label>
				<input type="radio" id="star2" name="course_review_star" value="2" />
				<label for="star2" title="text">2 stars</label>
				<input type="radio" id="star1" name="course_review_star" value="1" />
				<label for="star1" title="text">1 star</label>
			</div>
		</li>
		<li>
			<input type="text" name="course_feedback" id="course_feedback" class="field-style field-full align-none" placeholder="Public Review" />
		</li>
		<li>
			<input type="text" name="feedback" id="feedback" class="field-style field-full align-none" placeholder="Feedback" />
		</li>

		<li>
			<button  class="get_feedback" type="submit" > Submit</button>
		</li>
	</ul>
	<div id="info" class="alert alert-danger" role="alert">

	</div>
</form>
</div>
