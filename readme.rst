###################
Setting yang perlu di rubah setelah pull dari repository ini
###################

*******************
Generate encrytion key
*******************

fungsi generate encryption_key bisa di akses di controller GeneratorData fungsi generateEncryptionKey(). kode yang dihasilkan ini digunakan untuk mengisi encryption_key dan token_password di config.php

*******************
1. config.php
*******************
    1) encryption_key (taruh key di dalam fungsi "hex2bin()"),
    2) sess_cookie_name,
    3) csrf_token_name,
    4) csrf_cookie_name,
    5) theme_url (ambil link tema dari egov/front atau egov/tema)
    6) token_password (generate encryption_key)
*******************
2. buat repo baru di git untuk mengganti repo ini
*******************
    pastikan repo ini tidak ditimpa oleh pekerjaan yang menggunakan repo ini
*******************
3. silahkan run query dibawah untuk session driver
*******************
    repo ini menggunakan database session driver, silahkan run query dibawah ini untuk generate session table yang akan digunakan
    
    CREATE TABLE IF NOT EXISTS `ci_sessions` (`id` varchar(128) NOT NULL,`ip_address` varchar(45) NOT NULL,`timestamp` int(10) unsigned DEFAULT 0 NOT NULL,`data` blob NOT NULL, KEY `ci_sessions_timestamp` (`timestamp`));
*******************
4. generate starting data
*******************
	silahkan akses fungsi generateData() di controller GeneratorData untuk mengisi data dummy yang diperlukan untuk memulai aplikasi



###################
Dokumentasi CodeIgniter
###################
`bisa dilihat di halaman ini <https://codeigniter.com/user_guide/installation/index.html>`
