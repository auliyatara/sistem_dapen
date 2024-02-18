09-02-2024 ==> Update kepesertaans table, run this in query:
ALTER TABLE dana_pensiun.kepesertaans ADD kepesertaan_terhitung_mulai_tanggal_pensiun DATE NOT NULL;
ALTER TABLE dana_pensiun.kepesertaans ADD kepesertaan_gaji_pokok BIGINT NOT NULL;
ALTER TABLE dana_pensiun.kepesertaans ADD kepesertaan_npwp varchar(50) NOT NULL;

18-02-2024 ==> Update table iuran:
php artisan migrate
