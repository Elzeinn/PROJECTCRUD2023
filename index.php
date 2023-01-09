<?php
//Koneksi ke server
$host = "localhost";
$user = "root";
$password = "";

//Masukkan nama database 
$db = "crud";

$connect = mysqli_connect($host, $user, $password, $db);
if (!$connect) {
    die("Tidak bisa terkoneksi ke database");
}
$kelas = "";
$nama = "";
$jurusan = "";
$sukses = "";
$error = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if ($op == 'delete') {
    $id = $_GET['id'];
    $sql1 = "delete from siswa where id = '$id'";
    $q1 = mysqli_query($connect, $sql1);
    if ($q1) {
        $sukses = "berhasil hapus data";
    } else {
        $error = "gagal hapus data";
    }
}

if ($op == 'edit') {
    $id = $_GET['id'];
    $sql1 = "select * from siswa where id = '$id'";
    $q1 = mysqli_query($connect, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $kelas = $r1['kelas'];
    $nama = $r1['nama'];
    $jurusan = $r1['jurusan'];

    if ($kelas == '') {
        $error = "Data tidak ditemukan";
    }
}

if (isset($_POST['simpan'])) {
    $kelas = $_POST['kelas'];
    $nama = $_POST['nama'];
    $jurusan = $_POST['jurusan'];

    if ($kelas && $nama && $jurusan) {
        if ($op == 'edit') {
            $sql1 = "update siswa set kelas = '$kelas',nama='$nama',jurusan = '$jurusan' where id = '$id'";
            $q1 = mysqli_query($connect, $sql1);
            if ($q1) {
                $sukses = "Data berhasil di update";
            } else {
                $error = "Data gagal di update";
            }
        } else {
            $sql1 = "insert into siswa(kelas,nama,jurusan) values ('$kelas','$nama','$jurusan')";
            $q1 = mysqli_query($connect, $sql1);
            if ($q1) {
                $sukses = "Berhasil menambahkan data";
            } else {
                $error = "Gagal menambahkan data";
            }
        }
    } else {
        $error = "Silahkan masukkan semua data";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <title>Project CRUD</title>
    <style>
        .mx-auto {
            width: 800px;
        }

        .card {
            margin-top: 10px;
        }
    </style>
</head>


<!--Untuk Menambahkan data-->
<div class="mx-auto">
    <div class="card">
        <div class="card-header">
            Create / Edit Data
        </div>
        <button class=""><a href="code-crud.png">
                <h1>Source Code</h1>
            </a></button>
        <div class="card-body">
            <?php
            if ($error) {
            ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error ?>
                </div>
            <?php
                header("refresh:3;url=index.php");
            }
            ?>
            <?php
            if ($sukses) {
            ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $sukses ?>
                </div>
            <?php
                header("refresh:3;url=index.php");
            }
            ?>
            <form action="" method="POST">
                <div class="mb-3 row">
                    <label for="kelas" class="col-sm-2 col-form-label">Kelas</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="kelas" name="kelas" value="<?php echo $kelas ?>">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="jurusan" class="col-sm-2 col-form-label">Jurusan</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="jurusan" id="jurusan">
                            <option value="">Pilih Jurusan</option>
                            <option value="tkj" <?php if ($jurusan == "tkj") echo "selected" ?>>Tehnik Komputer Jaringan</option>
                            <option value="mm" <?php if ($jurusan == "mm") echo "selected" ?>>Multi Media</option>
                            <option value="tm" <?php if ($jurusan == "tm") echo "selected" ?>>Tehnik Mesin</option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary" />
                </div>
            </form>
        </div>
    </div>


    <!--Untuk Mengeuluarkan data-->
    <div class="card">
        <div class="card-header text-white bg-secondary">
            Data Siswa
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">kelas</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Jurusan</th>
                        <th scope="col">Aksi</th>
                    </tr>
                <tbody>
                    <?php
                    $sql2 = "select * from siswa order by id desc";
                    $q2 = mysqli_query($connect, $sql2);
                    $urut = 1;
                    while ($r2 = mysqli_fetch_array($q2)) {
                        $id = $r2['id'];
                        $kelas = $r2['kelas'];
                        $nama = $r2['nama'];
                        $jurusan = $r2['jurusan'];

                    ?>
                        <tr>
                            <th scope="row"><?php echo $urut++ ?></th>
                            <td scope="row"><?php echo $kelas ?></td>
                            <td scope="row"><?php echo $nama ?></td>
                            <td scope="row"><?php echo $jurusan ?></td>
                            <td scope="row">
                                <a href="index.php?op=edit&id=<?php echo $id ?>"> <button type="button" class="btn btn-warning">Edit</button></a>
                                <a href="index.php?op=delete&id=<?php echo $id ?>" onclick="return confirm('Yakin delete?')"> <button type="button" class="btn btn-danger">Delete</button></a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
                </thead>
            </table>
        </div>
    </div>
</div>
</body>

</html>