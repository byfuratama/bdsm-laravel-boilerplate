# BDSM Laravel Boilerplate

Merupakan template laravel 5.4 dengan tambahan fungsi-fungsi sendiri

## Requirement

- [Composer](https://getcomposer.org/download/)
- [Git](https://git-scm.com/downloads) (atau bisa memakai git dengan visual seperti Git-Kraken)
- [Postman](https://www.getpostman.com/downloads/) atau [Insomnia](https://insomnia.rest/)

## Instalasi

1. clone repo ini di dalam www atau hdtdocs
```
git clone https://github.com/byfuratama/bdsm-laravel-boilerplate.git bdsm-laravel
```

2. Masuk ke dalam folder yang sudah diclone
```
cd bdsm-laravel
```

3. Lakukan instalasi project dan tunggu sampai instalasi selesai
```
composer install
```

4. Buat environtment file dan generate key untuk aplikasi
```
cp .env.example .env
```
5. Generate key untuk aplikasi
```
php artisan key:generate
```

6. Generate secret JWT untuk API token
```
php artisan jwt:secret
```

7. Buat database baru dengan nama bdsm dalam phpmyadmin atau heidisql

8. Buka file .env dan ubah parameter berikut menurut database
##### DB_DATABASE=bdsm
##### DB_USERNAME=root
##### DB_PASSWORD=

9. Jalankan migration untuk mengisi dummy data
```
php artisan migrate:refresh --seed
```

10. Aplikasi sudah bisa di testing

## Testing

Gunakan aplikasi Postman/Insomnia untuk testing aplikasi 
Route yang tersedia (bisa dilihat di routes/api.php) :
```
[POST] /login?username=admin&password=admin
```
Jika sudah dapat token:
```
[POST] /logout?token=
[GET] /test?token=
[GET] /test-detail?token=
```

## Coba-Coba

Untuk belajar dan coba-coba bisa lihat file-file berikut sebagai referensi, sudah isi komentar

*Model*
```
app/Test.php
app/TestDetail.php
```
*Controller*
```
app/Http/Controllers/TestController.php
```
*Migration*
```
database/migrations/create_test_table.php
database/migrations/create_test_detail_table.php
```
*API Route*
```
routes/api.php
```

## Tips

Untuk membuat model, controller, dan migration sekaligus gunakan command berikut
```
php artisan make:model Nama -c -m
```

