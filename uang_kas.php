<?php
require 'connection.php';
checkLogin();
$bulan_pembayaran = mysqli_query($conn, "SELECT * FROM bulan_pembayaran ORDER BY tahun ASC");
if (isset($_POST['btnAddBulanPembayaran'])) {
  if (addBulanPembayaran($_POST) > 0) {
    setAlert("Bulan Pembayaran has been added", "Successfully added", "success");
    header("Location: uang_kas.php");
  }
}

if (isset($_POST['btnEditBulanPembayaran'])) {
  if (editBulanPembayaran($_POST) > 0) {
    setAlert("Bulan Pembayaran has been changed", "Successfully changed", "success");
    header("Location: uang_kas.php");
  }
}


?>

<!DOCTYPE html>
<html>

<head>
  <?php include 'include/css.php'; ?>
  <title>Uang Kas</title>
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
          <div class="row justify-content-center mb-2">
            <div class="col-sm">
              <h1 class="m-0 text-dark">Uang Kas</h1>
            </div><!-- /.col -->
            <div class="col-sm text-right">
              <?php if ($_SESSION['id_jabatan'] !== '3') : ?>
                <button class="btn btn-primary" data-toggle="modal" data-target="#tambahBulanPembayaranModal"><i class="fas fa-fw fa-plus"></i> Tambah Bulan</button>
                <!-- Modal -->
                <div class="modal fade text-left" id="tambahBulanPembayaranModal" tabindex="-1" role="dialog" aria-labelledby="tambahBulanPembayaranModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <form method="post">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="tambahBulanPembayaranModalLabel">Tambah Bulan Pembayaran</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <div class="row">
                            <div class="col-lg">
                              <div class="form-group">
                                <label for="nama_bulan">Nama Bulan</label>
                                <select name="nama_bulan" id="nama_bulan" class="form-control">
                                  <option value="januari">Januari</option>
                                  <option value="februari">Februari</option>
                                  <option value="maret">Maret</option>
                                  <option value="april">April</option>
                                  <option value="mei">Mei</option>
                                  <option value="juni">Juni</option>
                                  <option value="juli">Juli</option>
                                  <option value="agustus">Agustus</option>
                                  <option value="september">September</option>
                                  <option value="oktober">Oktober</option>
                                  <option value="november">November</option>
                                  <option value="desember">Desember</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-lg">
                              <div class="form-group">
                                <label for="tahun">Tahun</label>
                                <input type="number" required name="tahun" value="<?= date('Y') + 1; ?>" class="form-control">
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="pembayaran_perminggu">Pembayaran Perminggu</label>
                            <input type="number" name="pembayaran_perminggu" id="pembayaran_perminggu" required class="form-control" placeholder="Rp.">
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Close</button>
                          <button type="submit" name="btnAddBulanPembayaran" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Save</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              <?php endif ?>
            </div>
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-lg text-left">
              <h5>Pilih Bulan Pembayaran</h5>
            </div>
          </div>

          <div class="col-lg-4 my-4">
            <form action="" method="POST">
              <label>Search:
              </label>
              <input type="text" name="cari" class="form-control form-control-sm" placeholder="" aria-controls="table_id">
            </form>
          </div>

          <?php

          if (isset($_POST['cari'])) {
            $bulan_pembayaran = mysqli_query($conn, "SELECT * FROM bulan_pembayaran
            WHERE CONCAT(nama_bulan, ' ', tahun) LIKE '%$_POST[cari]%' ORDER BY tahun ASC");
          }

          ?>

          <?php

          $tahun = [];

          foreach ($bulan_pembayaran as $index => $dbp) :
            // Looping dari basis data, dimasukin ke array dengan key per tahun.
            $tahun[$dbp['tahun']][] = $dbp;
          endforeach;
          ?>

          <?php foreach ($tahun as $kas => $tahun) : ?>

            <div class="my-4 text-left">
              <h4><?= $kas ?></h4>
            </div>
            <div class="row">
              <?php
              foreach ($tahun as $isiKas) :
                $id_bulan_pembayaran = $isiKas['id_bulan_pembayaran'];
                $total_uang_kas_bulan_ini = mysqli_fetch_assoc(mysqli_query($conn, "SELECT sum(minggu_ke_1 + minggu_ke_2 + minggu_ke_3 + minggu_ke_4) as total_uang_kas_bulan_ini FROM uang_kas WHERE id_bulan_pembayaran = '$id_bulan_pembayaran'"));
                $total_uang_kas_bulan_ini = $total_uang_kas_bulan_ini['total_uang_kas_bulan_ini'];
              ?>
                <div class="col-lg-3">
                  <div class="card shadow">
                    <div class="card-body">
                      <h5><a href="detail_bulan_pembayaran.php?id_bulan_pembayaran=<?= $isiKas['id_bulan_pembayaran']; ?>" class="text-dark"><?= ucwords($isiKas['nama_bulan']); ?></a></h5>
                      <h6 class="text-muted"><?= $kas; ?></h6>
                      <h6>Rp. <?= number_format($isiKas['pembayaran_perminggu']); ?> / minggu</h6>
                      <h6>Total Uang Kas Bulan Ini: <span class="my-2 btn btn-success">Rp. <?= number_format($total_uang_kas_bulan_ini); ?></span></h6>
                      <a href="detail_bulan_pembayaran.php?id_bulan_pembayaran=<?= $isiKas['id_bulan_pembayaran']; ?>" class="btn btn-info"><i class="fas fa-fw fa-align-justify"></i></a>
                      <!-- <button type="button" data-toggle="modal" data-target="#editBulanPembayaranModal<?= $isiKas['id_bulan_pembayaran']; ?>" class="btn btn-success"><i class="fas fa-fw fa-edit"></i></button> -->
                      <!-- Modal -->
                      <div class="modal fade" id="editBulanPembayaranModal<?= $isiKas['id_bulan_pembayaran']; ?>" tabindex="-1" role="dialog" aria-labelledby="editBulanPembayaranModalLabel<?= $isiKas['id_bulan_pembayaran']; ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <form method="post">
                            <input type="hidden" name="id_bulan_pembayaran" value="<?= $isiKas['id_bulan_pembayaran']; ?>">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="editBulanPembayaranModalLabel<?= $isiKas['id_bulan_pembayaran']; ?>">Ubah Bulan Pembayaran</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <div class="row">
                                  <div class="col-lg">
                                    <div class="form-group">
                                      <label for="nama_bulan<?= $isiKas['id_bulan_pembayaran']; ?>">Nama Bulan</label>
                                      <input type="hidden" name="nama_bulan" value="<?= $isiKas['nama_bulan']; ?>">
                                      <input style="cursor: not-allowed;" disabled type="text" class="form-control" id="nama_bulan<?= $isiKas['id_bulan_pembayaran']; ?>" value="<?= $isiKas['nama_bulan']; ?>">
                                    </div>
                                  </div>
                                  <div class="col-lg">
                                    <div class="form-group">
                                      <label for="tahun<?= $isiKas['id_bulan_pembayaran']; ?>">Tahun</label>
                                      <input type="hidden" name="tahun" value="<?= $isiKas['tahun']; ?>">
                                      <input style="cursor: not-allowed;" disabled type="number" id="tahun<?= $isiKas['id_bulan_pembayaran']; ?>" value="<?= $isiKas['tahun']; ?>" class="form-control">
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="pembayaran_perminggu<?= $isiKas['id_bulan_pembayaran']; ?>">Pembayaran Perminggu</label>
                                  <input type="number" name="pembayaran_perminggu" id="pembayaran_perminggu<?= $isiKas['id_bulan_pembayaran']; ?>" required class="form-control" placeholder="Rp." value="<?= $isiKas['pembayaran_perminggu']; ?>">
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Close</button>
                                <button type="submit" name="btnEditBulanPembayaran" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Save</button>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                      <?php if ($_SESSION['id_jabatan'] == '1') : ?>
                        <a href="hapus_bulan_pembayaran.php?id_bulan_pembayaran=<?= $isiKas['id_bulan_pembayaran']; ?>" class="btn btn-danger btn-delete" data-nama="<?= ucwords($dbp['nama_bulan']); ?> | <?= $dbp['tahun']; ?>"><i class="fas fa-fw fa-trash"></i></a>
                      <?php endif ?>
                    </div>
                  </div>
                </div>
              <?php
              endforeach;
              ?>

            </div>
          <?php
          endforeach;
          ?>
        </div>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <!-- <footer class="main-footer">
    <strong>Copyright &copy; 2020 By Andri Firman Saputra.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer> -->

  </div>
</body>

</html>