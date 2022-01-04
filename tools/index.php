<?php 
include '../include/lib/count.php';
$count = new Count();

$acc = $count->getCountAccounts();
$com = $count->getCountComments();
$lvl = $count->getCountLevels();
$user = $count->getCountUsers();
?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, maximum-scale=1.0, user-scalable=no">
<link href="../include/components/css/styles.css" rel="stylesheet">
<link href="../include/components/images/tools_favicon.png" rel="shortcut icon">
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.2/dist/chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<title>GDPS Tools [IoCore]</title>

<body>
	<main id="tools">
		<h1>GDPS Tools</h1>
		<section id="toolbox">
			<h2 class="toolName">Uploading</h2>
			<a class="button" href="songAdd.php">Song Upload</a>
			<a class="button" href="levelReupload.php">Level reupload</a>
			<a class="button" href="linkAccount.php">Link GD Account</a>
			<a class="button" href="levelToGD.php">Level to GD</a>
			<a class="button" href="cron/">Cron</a>
		</section>
		<section id="toolbox" style="height: 45rem; width: 99%;">
			<h2 class="toolName">Stats</h2>
			<canvas id="statistic"></canvas>
			<script>
				const ctx = document.getElementById("statistic");
				const statistic = new Chart(ctx, {
					type: 'bar',
					data: {
						
						labels: ['Accounts', 'Levels', 'Comments', 'Users'],
						datasets: [{
							label: 'GDPS Stats',
							data: [<?php echo $acc; ?>, <?php echo $lvl; ?>, <?php echo $com; ?>, <?php echo $user; ?>],
							backgroundColor: [
								'rgba(255, 99, 132, 0.2)',
								'rgba(54, 162, 235, 0.2)',
								'rgba(255, 206, 86, 0.2)',
								'rgba(75, 192, 192, 0.2)'
							],
							borderColor: [
								'rgba(255, 99, 132, 1)',
								'rgba(54, 162, 235, 1)',
								'rgba(255, 206, 86, 1)',
								'rgba(75, 192, 192, 1)'
							],
							borderWidth: 1
						}]
					},
					options: {
					scales: {
						y: {
							beginAtZero: true
						}
					}
				}
				});
				</script>
		</section>
	</main>
	<footer>Provided by <span><a href="https://github.com/WoidZero/IoCore">IoCore</a></span> / Developed by <a href="https://github.com/WoidZero">WoidZero</a></footer>
</body>