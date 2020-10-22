<?php
		$_SESSION['admin'] = "?";
		print<<<END
			<script>
				window.location = "index.php";
			</script>
END;

?>