<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, maximum-scale=1.0, user-scalable=no">
		<link href="../../include/components/css/styles.css" rel="stylesheet">
		<link href="../../include/components/images/tools_favicon.png" rel="shortcut icon">
		<script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.2/dist/chart.min.js"></script>
		<script src="https://code.jquery.com/jquery-latest.js"></script>
		<title>GDPS Tools [Cron]</title>
	</head>
	<body>
		<main id="cron">
			<p id="1"><a class="button" onclick="autoban()">Start Cron</a></p>
			<p id="2"></p><p id="3"></p><p id="4"></p><p id="5"><p id="6"></p>
		</main>
	</body>
</html>

<script>
    function autoban() {
        $("#1").html("<h1>Cron log</h1><br>Starting AutoBan...");
        var old = $("#1").html();
        var a = "1";
        $.ajax({
          type: "POST",
          url: "autoban.php",
          data: { a:a }
        }).done(function(result) {
            if(result == 1) {
                $("#1").html(old + "<br />AutoBan finished");
                fixcps();
            } else {
                $("#1").html(result + "");
                fixcps();
            }
        });
      }
    function fixcps() {
        $("#2").html("Fixing cps...");
        var old = $("#2").html();
        var a = "1";
        $.ajax({
          type: "POST",
          url: "fixcps.php",
          data: { a:a }
        }).done(function(result) {
            if(result == 1) {
                $("#2").html(old + "<br />cps fixed!");
                fixnames();
            } else {
                $("#2").html(result + "");
                fixnames();
            }
        });
      }
    function fixnames() {
        $("#3").html("Fixing names...");
        var old = $("#3").html();
        var a = "1";
        $.ajax({
          type: "POST",
          url: "fixnames.php",
          data: { a:a }
        }).done(function(result) {
            if(result == 1) {
                $("#3").html(old + "<br />names fixed!");
                friends();
            } else {
                $("#3").html(result + "");
                friends();
            }
        });
      }
    function friends() {
        $("#4").html("Fixing friends...");
        var old = $("#4").html();
        var a = "1";
        $.ajax({
          type: "POST",
          url: "friendsLeaderboard.php",
          data: { a:a }
        }).done(function(result) {
            if(result == 1) {
                $("#4").html(old + "<br />friends fixed!");
                rbl();
            } else {
                $("#4").html(result + "");
                rbl();
            }
        });
      }
    function rbl() {
        $("#5").html("Cleaning...");
        var old = $("#5").html();
        var a = "1";
        $.ajax({
          type: "POST",
          url: "removeBlankLevels.php",
          data: { a:a }
        }).done(function(result) {
            if(result == 1) {
                $("#5").html(old + "<br />Cleaned!");
                lvls();
            } else {
                $("#5").html(result + "");
                lvls();
            }
        });
      }
    function lvls() {
        $("#6").html("Fixing levels...");
        var old = $("#6").html();
        var a = "1";
        $.ajax({
          type: "POST",
          url: "fixlevels.php",
          data: { a:a }
        }).done(function(result) {
            if(result == 1) {
                $("#6").html(old + "<br />Levels fixed!<br>Cron ended!");
            } else {
                $("#6").html(result + "");
            }
        });
      }
</script>

