# Brief Teknis: Perbaikan Bug & Implementasi Fitur Tertunda (HIMATIK DSS)

Brief ini dibuat untuk mendokumentasikan daftar temuan komponen yang belum terimplementasi, ketidaksesuaian kode (bug/inconsistencies), serta panduan langkah demi langkah untuk melakukan perbaikan sistem secara menyeluruh.

---

## Bagian A: Komponen yang Belum Terimplementasi (Missing Features)

Berikut adalah daftar fitur yang datanya sudah siap di database, tetapi logikanya belum selesai dibangun:

### 1. Validasi Kuota Penerimaan Kandidat (*Quota Enforcement*)
*   **Kondisi Saat Ini**: Tabel `open_recruitment_quotas` menyimpan kuota penerimaan per departemen, tetapi admin dapat meluluskan (`accepted`) kandidat sebanyak-banyaknya tanpa ada pengecekan batas kuota.
*   **Target Implementasi**:
    *   Sebelum mengubah status kandidat menjadi `accepted` di `AdminWebController@decideCandidate` dan `AdminApiController@decideCandidate`, hitung jumlah kandidat yang sudah lolos di departemen target.
    *   Jika kuota sudah terpenuhi, batalkan proses penerimaan dan kembalikan respon error (misal: `"Kuota penerimaan untuk departemen Biro Humas sudah penuh (Maks: X)"`).

### 2. Integrasi Data Real Top 3 Kandidat pada Dashboard Interviewer
*   **Kondisi Saat Ini**: Metode `dashboard()` di `InterviewerWebController` masih memuat data kandidat secara acak (*mock data*) dengan komentar `// Mock for now until SpkResult is fully integrated`.
*   **Target Implementasi**:
    *   Ubah pemanggilan query agar memuat 3 kandidat teratas berdasarkan nilai akhir kalkulasi profile matching (`SpkResult`) untuk departemen tempat interviewer bertugas.

### 3. Keamanan File Bukti & Dokumen Kandidat
*   **Kondisi Saat Ini**: Semua file (foto, bukti follow Instagram, YouTube, dokumen pernyataan politik, tanda tangan) disimpan pada disk `public` (`storage/app/public/...`), sehingga dapat diakses secara bebas oleh siapa saja tanpa autentikasi melalui URL langsung.
*   **Target Implementasi**:
    *   Pindahkan penyimpanan dokumen administratif ke disk `private`.
    *   Buat route dan controller khusus (misal: `/admin/documents/download/{candidate}/{field}`) dengan middleware `auth` dan pengecekan role `admin` atau `interviewer` sebelum mengunduh file tersebut.

---

## Bagian B: Ketidaksesuaian Kode (Bugs & Inconsistencies)

Berikut adalah daftar kesalahan arsitektur atau sintaksis yang dapat merusak aplikasi saat dijalankan:

### 1. Kegagalan Registrasi Profil Kandidat via REST API
*   **Lokasi**: `CandidateApiController@storeProfile` dan `CandidateProfileService@createFor`
*   **Masalah**: 
    1.  Validasi NIM menggunakan `'unique:candidates,nim'`. Padahal kolom `nim` tidak ada di tabel `candidates` melainkan di tabel `users`.
    2.  `CandidateProfileService` mencoba memasukkan data identitas (`nickname`, `nim`, `prodi`, `kelas`, `phone`, `address`) ke dalam fungsi `Candidate::create()`. Karena kolom-kolom ini tidak ada di tabel `candidates` dan tidak terdaftar di `$fillable`, data tersebut diabaikan. Akibatnya, profil kandidat yang mendaftar melalui API mobile akan kehilangan informasi NIM dan identitas lainnya di tabel `users`.
*   **Solusi Perbaikan**:
    *   Perbaiki validasi NIM di `CandidateProfileRules` menjadi `'unique:users,nim,' . $userId`.
    *   Pada `CandidateProfileService@createFor`, lakukan pembaruan data user sebelum membuat objek kandidat:
        ```php
        $user->update([
            'nickname' => $data['nickname'],
            'nim' => $data['nim'],
            'prodi' => $data['prodi'],
            'kelas' => $data['kelas'],
            'phone' => $data['phone'],
            'address' => $data['address'],
        ]);
        ```

### 2. Bug Mismatch Skema Tabel Jadwal Interview di REST API
*   **Lokasi**: `CandidateApiController@getAvailableSchedules` dan `CandidateApiController@bookSchedule`
*   **Masalah**: Kode API masih mengacu pada skema tabel lama yang telah di-refactor. Kode mencoba memfilter menggunakan `is_active` dan melakukan `select` terhadap kolom `session_name`, `scheduled_at`, dan `location`. Di database terbaru, kolom-kolom tersebut telah dibuang dan diganti dengan `date`, `start_time`, `end_time`, dan `is_blocked`.
*   **Solusi Perbaikan**:
    *   Ubah filter `is_active => true` menjadi `is_blocked => false`.
    *   Sesuaikan pengurutan dari `scheduled_at` ke `date` dan `start_time`.
    *   Sesuaikan payload respon JSON agar mengembalikan data jam mulai (`start_time`) dan selesai (`end_time`).

### 3. Error Query Pencarian NIM & Prodi di Pendaftaran Admin
*   **Lokasi**: `AdminWebController@registrations`
*   **Masalah**: Query pencarian menggunakan relasi langsung `candidates.nim` atau `candidates.prodi`. Hal ini memicu database error karena kolom tersebut tidak didefinisikan pada tabel `candidates`.
*   **Solusi Perbaikan**:
    *   Gunakan scope relasi Eloquent untuk mencari ke dalam tabel `users`:
        ```php
        $query->whereHas('user', function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('nim', 'like', "%{$search}%")
              ->orWhere('prodi', 'like', "%{$search}%");
        });
        ```

### 4. Kesalahan Nama Route Redirect pada Web Controller
*   **Lokasi**: `CandidateWebController::redirectCandidateUser()`
*   **Masalah**: Controller melakukan redirect ke rute `interviewer.schedule`, sedangkan nama rute yang benar dan terdaftar di `web.php` adalah `interviewer.schedules` (dengan akhiran **s**). Hal ini memicu error *Route not defined*.
*   **Solusi Perbaikan**:
    *   Ubah redirect tujuan menjadi `route('interviewer.schedules')`.

---

## Bagian C: Panduan Langkah Eksekusi Perbaikan

Bagi developer yang ditugaskan untuk memperbaiki proyek ini, ikuti langkah-langkah terstruktur berikut:

```
[Langkah 1: Perbaiki API Kandidat & Validasi]
 └─ Ubah CandidateProfileRules (nim unik di users)
 └─ Tambahkan $user->update() di CandidateProfileService

[Langkah 2: Sinkronisasi API Jadwal]
 └─ Perbarui query getAvailableSchedules & bookSchedule sesuai skema baru (is_blocked, date, start/end_time)

[Langkah 3: Perbaiki Bug Web Controller]
 └─ Perbaiki query pencarian di AdminWebController
 └─ Perbaiki nama route redirect interviewer.schedules di CandidateWebController

[Langkah 4: Implementasi Validasi Kuota Penerimaan]
 └─ Tambahkan check kuota di AdminApiController@decideCandidate & AdminWebController

[Langkah 5: Verifikasi & Uji Coba]
 └─ Jalankan: php artisan migrate:fresh --seed
 └─ Jalankan: php artisan test
```
