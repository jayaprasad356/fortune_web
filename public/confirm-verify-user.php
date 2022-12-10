<div id="content" class="container col-md-12">
	<?php
	include_once('includes/custom-functions.php');
	$fn = new custom_functions;
    include_once('includes/functions.php');
    $function = new functions;

	if (isset($_POST['btnUpdate'])) {
		if (defined('ALLOW_MODIFICATION') && ALLOW_MODIFICATION == 0) {
			echo '<label class="alert alert-danger">This operation is not allowed in demo panel!.</label>';
			return false;
		}

		// $ID = (isset($_GET['id'])) ? $db->escapeString($fn->xss_clean($_GET['id'])) : "";
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $ID = $db->escapeString($fn->xss_clean($_GET['id']));
        } else { ?>
            <script>
                alert("Something went wrong, No data available.");
                window.location.href = "manage_users.php";
            </script>
        <?php
        }

		// delete data from menu table
		$sql_query = "UPDATE users SET status=1 WHERE id =" . $ID;
		$db->sql($sql_query);
		$verify_result = $db->getResult();
		if (!empty($verify_result)) {
			$verify_result = 0;
		} else {
			$verify_result = 1;
		}
		// if delete data success back to reservation page
		if ($verify_result == 1) {
			header("location: manage_users.php");
		}
	}

	if (isset($_POST['btnNo'])) {
		header("location: manage_users.php");
	}
	if (isset($_POST['btncancel'])) {
		header("location: manage_users.php");
	}

	?>
		<h1>Confirm Action</h1>
		<hr />
		<form method="post">
			<p>Are you sure want to Verify this User?</p>
			   <input type="submit" class="btn btn-success" value="Verify" name="btnUpdate" />
			<input type="submit" class="btn btn-danger" value="Cancel" name="btnNo" />
		</form>
		<div class="separator"> </div>
</div>

<?php $db->disconnect(); ?>