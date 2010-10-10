<html>
	<style type="text/css">
		body {
			background-color: #ffa;
			margin: 0px;
			padding: 0px;
		}
		
		#container {
			margin: 10px;
			padding: 50px;
			background-color: #666;
			color: #fff;
		}
	</style>
	<body>
		<div id="container">
			<pre>
				<? if (isset($errfile)) echo '<strong>'.$errfile.'</strong><br/>';?>
				<? if (isset($errline)) echo 'Line: <strong>'.$errline.'</strong><br/>';?>
				<? if (isset($error)) echo $error?>
			</pre>
		</div>
	</body>
</html>