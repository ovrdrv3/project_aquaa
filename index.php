<!DOCTYPE html>
<html>
<head>
	<meta name="description" content="A site to remember to water your plants">
  	<title>plants app</title>
	<!-- bootstrap -->
	<link rel="stylesheet" type="text/css" href="styles/css/bootstrap.min.css">
	<!-- font awesome -->
	<link rel="stylesheet" type="text/css" href="styles/css/font-awesome.min.css" media="screen">
	<!-- custom css -->
	<link rel="stylesheet" type="text/css" href="styles/css/personal.css" media="screen">
	<!-- google fonts -->
	<!-- <link href="https://fonts.googleapis.com/css?family=Baloo+Bhaina|Open+Sans" rel="stylesheet"> -->

</head>

<body>
	<nav class="navbar navbar-lightnavbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" style="color: lightblue;"href="#"><i class="fa fa-tint fa-2x" aria-hidden="true"></i></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse" aria-expanded="false" style="height: 1px;">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#"></a></li>
            <li><a href="#about"></a></li>
            <li><a href="#contact"></a></li>
          </ul>
        </div>
      </div>
    </nav>

	<!-- main content -->
	<div class="container">
	<div class="row">
		<!-- main table content -->
		<div class="col-md-10" >
			<!-- little guide to show what the colors represent	 -->
			<div class="col-md-4 water-past-date centered-text">Past water date</div>
			<div class="col-md-4 water-ready centered-text">Ready for watering</div>
			<div class="col-md-4 water-not-ready centered-text">Not Ready for watering</div>
			<table class="table table-responsive" id="main-table">
			  <thead>
			    <tr>
			      <th>Plant</th>
			      <th>Last <i class="fa fa-tint"></i></th>
			      <th>Next <i class="fa fa-tint"></i></th>
			      <th>Frequency</th>
				  <th style="text-align: center;">Water Now?</th>
			    </tr>
			  </thead>

			  <tbody id="list-of-plants"></tbody>
			  <!-- form for adding a new plant -->
			  <tr id="new-plant-tr">
				  <form class="form-inline" method="post" id="new-plant-form" >
					  <td>
						  <input name="name" type="text" id="new-plant-form-name" tabindex=1 class="form-control mb-2 mr-sm-2 mb-sm-0" placeholder="(Sansevieria, English Ivy)">
					  </td>
					  <td colspan="2">
						  <input name="water_date" class="form-control mb-2 mr-sm-2 mb-sm-0" type="date"  tabindex=3 value="<?= date("Y-m-d") ?>" id="example-date-input">
					  </td>
					  <td>
						  <input name="water_frequency" type="text" tabindex=2 class="form-control mb-2 mr-sm-2 mb-sm-0" placeholder="Every (#) days">
					  </td>
					  <td>
						  <button name="submit" type="submit"  class="btn btn-block" style="width:100%">
						  <i class="fa fa-plus" aria-hidden="true"></i>
					  </button>
					  </td>
				  </form>
		  	  </tr>

			  <tr id="edit_button_row">
				  <td colspan="5"><button class="btn btn-block"><i class="fa fa-pencil fa-2x"></i></button></td>
			  </tr>

			</table>
		</div>
		<!-- sidebar -->
		<div class="col-md-2">
			<button id="theholybutton">click me</button>
			<h2>Is your internal calendar perfect?</h2>
			<p>It is difficult to remember the last time plants are watered. This becomes increasingly difficult as the number of plants in your office and home grow. Project aquaa puts the power of data to work and is an easy to use utility to help keep track of your plants!</p>
			<p>< > by ovrdrv3</p>
		</div>
	</div>
	<!-- container -->
	</div>


<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/app.js"></script>
</body>
</html>
