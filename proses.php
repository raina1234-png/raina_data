<?php
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    // Gunakan mysqli_real_escape_string agar inputan yang pakai tanda kutip (') tidak bikin error SQL
    $id      = mysqli_real_escape_string($conn, $_POST['id']);
    $nim     = mysqli_real_escape_string($conn, $_POST['nim']);
    $nama    = mysqli_real_escape_string($conn, $_POST['nama']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);
    
    $foto_nama = $_FILES['foto']['name'];
    $tmp_name  = $_FILES['foto']['tmp_name'];

    // 1. CEK FOLDER: Pastikan folder 'uploads' sudah ada, jika belum buat otomatis
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    if ($id == "") { 
        // --- PROSES TAMBAH ---
        $ekstensi  = pathinfo($foto_nama, PATHINFO_EXTENSION);
        $nama_baru = time() . "_" . uniqid() . "." . $ekstensi; // Tambah uniqid biar lebih aman

        if (move_uploaded_file($tmp_name, 'uploads/' . $nama_baru)) {
            $query = "INSERT INTO mahasiswa (nim, nama_lengkap, jurusan, foto) VALUES ('$nim', '$nama', '$jurusan', '$nama_baru')";
        } else {
            echo "<script>alert('Gagal upload gambar! Pastikan memilih file.'); window.history.back();</script>";
            exit;
        }

    } else { 
        // --- PROSES EDIT ---
        if ($foto_nama != "") {
            // Jika user upload foto baru
            $ekstensi  = pathinfo($foto_nama, PATHINFO_EXTENSION);
            $nama_baru = time() . "_" . uniqid() . "." . $ekstensi;
            move_uploaded_file($tmp_name, 'uploads/' . $nama_baru);
            
            // Hapus foto lama dari folder agar tidak nyampah
            $ambil = mysqli_query($conn, "SELECT foto FROM mahasiswa WHERE id='$id'");
            $data  = mysqli_fetch_array($ambil);
            if ($data['foto'] != "" && file_exists("uploads/" . $data['foto'])) {
                unlink("uploads/" . $data['foto']);
            }

            $query = "UPDATE mahasiswa SET nim='$nim', nama_lengkap='$nama', jurusan='$jurusan', foto='$nama_baru' WHERE id='$id'";
        } else {
            // Jika user tidak ganti foto
            $query = "UPDATE mahasiswa SET nim='$nim', nama_lengkap='$nama', jurusan='$jurusan' WHERE id='$id'";
        }
    }

    // Eksekusi Query
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data berhasil disimpan!'); window.location='index.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// --- PROSES HAPUS ---
if (isset($_GET['hapus'])) { 
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    
    // Ambil nama file foto dulu sebelum data di DB dihapus
    $ambil = mysqli_query($conn, "SELECT foto FROM mahasiswa WHERE id='$id'");
    $data  = mysqli_fetch_array($ambil);
    
    // Hapus file fisiknya
    if ($data['foto'] != "" && file_exists("uploads/" . $data['foto'])) {
        unlink("uploads/" . $data['foto']);
    }

    // Hapus data di database
    if (mysqli_query($conn, "DELETE FROM mahasiswa WHERE id='$id'")) {
        echo "<script>alert('Data berhasil dihapus!'); window.location='index.php';</script>";
    }
}
?>