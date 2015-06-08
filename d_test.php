<html>
	<head>
		<title>D Test</title>
		<script src="js/jquery_2.js"></script>
		<script src="js/jquery.dataTables.js"></script>
		<link rel="stylesheet" href="css/jquery.dataTables.css" media="screen">
	</head>
	<body>
		<table id="example" class="display cell-border" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th>Name</th>
				<th>Mobile Number</th>
				<th>Email</th>
				<th>Birthdate</th>
			</tr>
		</thead>
		</table>
	</body>
	<script>
	$(document).ready(function() {
		$('#example').dataTable( {
			"processing": true,
			"ajax": "server_test.php",
			"columns": [
            { "data": "name" },
            { "data": "position" },
			{ "data" : "office"},
			/*	"render":function(data, type, full, meta){			
				return full.contact[0] + "<br>" + full.contact[1];
			}},*/
			{ "data": "start_date" }
        ]
		} );
	} );
	</script>

</html>