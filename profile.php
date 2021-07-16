<?php
require 'connection.php';
checkLogin();
$jabatan = mysqli_query($conn, "SELECT * FROM jabatan");

if (isset($_POST['btnChangePassword'])) {
	if (changePassword($_POST) > 0) {
		setAlert("Your Password has been changed", "Successfully changed", "success");
		header("Location: profile.php");
	}
}

?>

<!DOCTYPE html>
<html>

<head>
	<?php include 'include/css.php'; ?>
	<title>Profile</title>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">

		<?php include 'include/navbar.php'; ?>

		<?php include 'include/sidebar.php'; ?>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<div class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm">
							<h1 class="m-0 text-dark">Profile</h1>
						</div><!-- /.col -->
					</div><!-- /.row -->
				</div><!-- /.container-fluid -->
			</div>
			<!-- /.content-header -->

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">
					<div class="row m-4">
						<div class="col-lg-2">
							<img src="<?= $dataUser['foto'] ? "assets/img/img_profiles/" . $dataUser['foto'] : "https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg" ?>" class="img-fluid" />
						</div>
						<div class="col-lg">
							<div class="card">
								<ul class="list-group list-group-flush">
									<li class="list-group-item">Nama Lengkap: <?= $dataUser['nama_lengkap']; ?></li>
									<li class="list-group-item">Username: <?= $dataUser['username']; ?></li>
									<li class="list-group-item">Jabatan: <?= $dataUser['nama_jabatan']; ?></li>
								</ul>
							</div>
						</div>
					</div>
					<!-- Button trigger modal -->
					<button type="button" class="btn btn-success" data-toggle="modal" data-target="#editProfileModal">
						<i class="fas fa-fw fa-edit"></i> Edit
					</button>

					<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#changePasswordModal">
						<i class="fas fa-fw fa-lock"></i> Change Password
					</button>

					<!-- Modal -->
					<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<form method="post" action="" enctype="multipart/form-data">
								<input type="hidden" name="id_user" value="<?= $dataUser['id_user']; ?>">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<div class="form-group">
											<label for="nama_lengkap">Nama Lengkap</label>
											<input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" required value="<?= $dataUser['nama_lengkap']; ?>">
										</div>
										<div class="form-group">
											<label for="username">Username</label>
											<input type="hidden" name="username" id="username" value="<?= $dataUser['username']; ?>">
											<input style="cursor: not-allowed;" disabled type="text" name="username" id="username" class="form-control" required value="<?= $dataUser['username']; ?>">
										</div>
										<div class="form-group">
											<label for="id_jabatan">Jabatan</label>
											<input type="hidden" name="id_jabatan" id="id_jabatan" value="<?= $dataUser['id_jabatan']; ?>">
											<input style="cursor: not-allowed;" disabled type="text" class="form-control" required value="<?= $dataUser['nama_jabatan']; ?>">
										</div>
										<div class="form-group">
											<label for="foto">Foto <small>Max 5MB</small></label>
											<input type="file" class="form-control" name="foto" id="foto" accept="image/png, image/jpeg">
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Close</button>
										<button type="submit" name="btnEditProfile" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Save</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<!-- Modal change password -->
					<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<form method="post">
								<input type="hidden" name="id_user" value="<?= $dataUser['id_user']; ?>">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<div class="form-group">
											<label for="old_password">Old Password</label>
											<input type="password" minlength="6" name="old_password" id="old_password" class="form-control" required>
										</div>
										<div class="form-group">
											<label for="new_password">Password</label>
											<input type="password" minlength="6" name="new_password" id="new_password" class="form-control" required>
										</div>
										<div class="form-group">
											<label for="new_password_verify">Password Verify</label>
											<input type="password" minlength="6" name="new_password_verify" id="new_password_verify" class="form-control" required>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Close</button>
										<button type="submit" name="btnChangePassword" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Save</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</section>
			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->
		<!-- <footer class="main-footer">
    <strong>Copyright</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b></b>
    </div>
  </footer> -->

	</div>
</body>

</html>

<?php

if (isset($_POST['btnEditProfile'])) {

	$id_user = htmlspecialchars($_POST['id_user']);
	$nama_lengkap = htmlspecialchars(addslashes($_POST['nama_lengkap']));
	$username = htmlspecialchars($_POST['username']);
	$id_jabatan = htmlspecialchars($_POST['id_jabatan']);

	// Untuk foto
	$foto = $_FILES['foto']['name'];
	$file = $_FILES['foto']['tmp_name'];
	$size = $_FILES['foto']['size'];
	$tipe = $_FILES['foto']['type'];
	$folder = "assets/img/img_profiles/";
	$saring = array('gif', 'png', 'jpg');
	$ext = pathinfo($foto, PATHINFO_EXTENSION);

	if (strlen($foto)) {
		// Cek format foto.
		$ext = pathinfo($foto, PATHINFO_EXTENSION);
		if (in_array($ext, $saring)) {
			// Cek ukurannya.
			// 5242880 = 5MB.
			if ($size < 5242880) {
				$img = sha1($foto);
				// Jika Mencoba upload & jika berhasil di upload
				if (move_uploaded_file($file, $folder . $img)) {
					$query = mysqli_query($conn, "UPDATE user SET nama_lengkap = '$nama_lengkap', username = '$username', id_jabatan = '$id_jabatan', foto = '$img' WHERE id_user = '$id_user'");
?>
					<script type="text/javascript">
						alert("Data berhasil disimpan!");
					</script>
				<?php
				} else {
					// Jika gagal di upload.
				?>
					<script type="text/javascript">
						alert("Error!");
					</script>
				<?php
				}
			} else {
				// Jika gambar melebihi ukuran yang ditentukan.
				?>
				<script type="text/javascript">
					alert("Ukuran gambar terlalu besar! (Max : 5MB)");
				</script>
			<?php
			}
		} else {
			// Jika format gambar tidak sesuai dengan $saring
			?>
			<script type="text/javascript">
				alert("Format gambar tidak dizinkan!");
			</script>
		<?php
		}
	} else {

		// Jika tidak upload foto, diganti dengan tanpa_foto.jpg
		mysqli_query($conn, "UPDATE user SET nama_lengkap = '$nama_lengkap', username = '$username', id_jabatan = '$id_jabatan' WHERE id_user = '$id_user'");

		?>
		<script type="text/javascript">
		</script>
<?php
	}
}
?>