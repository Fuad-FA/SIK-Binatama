<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Makanan & Minuman
            [
                'kode_produk' => 'PRD-001',
                'nama'        => 'KORI CHIPS',
                'kategori'    => 'makanan_minuman',
                'harga'       => 6000,
                'keterangan'  => 'Keripik pegagan 40g',
            ],
            [
                'kode_produk' => 'PRD-002',
                'nama'        => 'CHOCO PSBB',
                'kategori'    => 'makanan_minuman',
                'harga'       => 6000,
                'keterangan'  => 'Cokelat herbal',
            ],
            [
                'kode_produk' => 'PRD-003',
                'nama'        => 'JAMPI PSBB (pouch)',
                'kategori'    => 'makanan_minuman',
                'harga'       => 10000,
                'keterangan'  => 'Pouch 20g',
            ],
            [
                'kode_produk' => 'PRD-004',
                'nama'        => 'JAMPI PSBB (botol)',
                'kategori'    => 'makanan_minuman',
                'harga'       => 25000,
                'keterangan'  => 'Botol 150g',
            ],
            [
                'kode_produk'    => 'PRD-005',
                'nama'           => 'JENS',
                'kategori'       => 'makanan_minuman',
                'harga'          => 4000,
                'keterangan'     => 'Sari jeruk nipis serbuk sachet',
            ],
            [
                'kode_produk'    => 'PRD-006',
                'nama'           => 'IMUN JELLY',
                'kategori'       => 'makanan_minuman',
                'harga'          => 0,
                'harga_by_order' => true,
                'keterangan'     => 'Jeli peningkat imun',
            ],
            [
                'kode_produk' => 'PRD-007',
                'nama'        => 'NILA MARINASI',
                'kategori'    => 'makanan_minuman',
                'harga'       => 30000,
                'keterangan'  => '±300g, harga menyesuaikan pasar',
            ],
            [
                'kode_produk' => 'PRD-008',
                'nama'        => 'AYAM UNGKEP HERBAL',
                'kategori'    => 'makanan_minuman',
                'harga'       => 25000,
                'keterangan'  => '±300g, harga menyesuaikan pasar',
            ],
            // Pembersih
            [
                'kode_produk' => 'PRD-009',
                'nama'        => 'PHARMALIGHT 450ML',
                'kategori'    => 'pembersih',
                'harga'       => 7000,
                'keterangan'  => 'Pencuci piring kemasan botol',
            ],
            [
                'kode_produk' => 'PRD-010',
                'nama'        => 'DESIN SPRAY',
                'kategori'    => 'pembersih',
                'harga'       => 20000,
                'keterangan'  => 'Desinfektan cair 200ml',
            ],
            [
                'kode_produk' => 'PRD-011',
                'nama'        => 'F HAND SOAP',
                'kategori'    => 'pembersih',
                'harga'       => 15000,
                'keterangan'  => 'Pencuci tangan pump 450ml',
            ],
            [
                'kode_produk' => 'PRD-012',
                'nama'        => 'HAND SANITIZER',
                'kategori'    => 'pembersih',
                'harga'       => 120000,
                'keterangan'  => 'Jerigen 5 liter',
            ],
            [
                'kode_produk' => 'PRD-013',
                'nama'        => 'ALL SABUN',
                'kategori'    => 'pembersih',
                'harga'       => 65000,
                'keterangan'  => 'Jerigen 5 liter',
            ],
            // Lainnya
            [
                'kode_produk' => 'PRD-014',
                'nama'        => 'PHARMACARE ROLL ON',
                'kategori'    => 'lainnya',
                'harga'       => 20000,
                'keterangan'  => 'Botol kaca 10ml',
            ],
            [
                'kode_produk' => 'PRD-015',
                'nama'        => 'NATURA SOAP SEREH',
                'kategori'    => 'lainnya',
                'harga'       => 12000,
                'keterangan'  => 'Sabun mandi 75g',
            ],
            [
                'kode_produk' => 'PRD-016',
                'nama'        => 'HERBS OIL',
                'kategori'    => 'lainnya',
                'harga'       => 30000,
                'keterangan'  => 'Minyak urut 100ml',
            ],
            [
                'kode_produk' => 'PRD-017',
                'nama'        => 'ROCY FACE MIST',
                'kategori'    => 'lainnya',
                'harga'       => 10000,
                'keterangan'  => 'Botol stick 8ml',
            ],
            [
                'kode_produk' => 'PRD-018',
                'nama'        => 'PUPUK ORGANIK CAIR',
                'kategori'    => 'lainnya',
                'harga'       => 20000,
                'keterangan'  => 'Botol 180ml',
            ],
        ];

        foreach ($products as $product) {
            Product::create(array_merge($product, [
                'stok'      => 10,
                'satuan'    => 'pcs',
                'is_active' => true,
                'harga_by_order' => $product['harga_by_order'] ?? false,
            ]));
        }
    }
}