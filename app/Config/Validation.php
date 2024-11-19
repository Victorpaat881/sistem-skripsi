<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var string[]
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    public $form = [
        'nama' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Nama pasien tidak boleh kosong'
            ]
        ],
        'rm' => [
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Nomor rekam medis tidak boleh kosong',
                'numeric' => 'Nomor rekam medis hanya bisa di isi dengan angka'
            ]
        ],
        'nik' => [
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'NIK tidak boleh kosong',
                'numeric' => 'NIK hanya bisa di isi dengan angka'
            ]
        ],
        'tanggal_berkunjung' => [
            'rules' => 'required|valid_date',
            'errors' => [
                'required' => 'Tanggal kunjungan terakhir tidak boleh kosong',
                'valid_date' => 'Tanggal kunjungan terakhir yang anda masukkan tidak sesuai format. Contoh Penulisan: 2022-07-27'
            ]
        ],
        'penyakit' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Penyakit tidak boleh kosong'
            ]
        ]
    ];
    public $user = [
        'username' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Username tidak boleh kosong'
            ]
        ],
        'password' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Password tidak boleh kosong'
            ]
        ]
    ];
    public $users = [
        'username' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Username tidak boleh kosong'
            ]
        ]
    ];
    public $chart = [
        'year' => [
            'rules' => 'numeric',
            'errors' => [
                'numeric' => 'Silahkan pilih terlebih dahulu data dari tahun berapa yang anda ingin tampilkan'
            ]
        ],
        'month' => [
            'rules' => 'permit_empty|in_list[1,2,3,4,5,6,7,8,9,10,11,12]',
            'errors' => [
                'permit_empty' => 'Data yang anda masukkan salah',
                'in_list' => 'Silahkan pilih terlebih dahulu data dari bulan berapa yang anda ingin tampilkan'
            ]
        ]
    ];
    public $stok_obat_insert = [
        'NAMA_OBAT' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Nama obat tidak boleh kosong'
            ]
        ],
        'SATUAN' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Satuan tidak boleh kosong'
            ]
        ],
        'MASUK' => [
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Obat masuk tidak boleh kosong',
                'numeric' => 'Obat masuk hanya bisa di isi dengan angka'
            ]
        ],
        'KELUAR' => [
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Obat keluar tidak boleh kosong',
                'numeric' => 'Obat keluar hanya bisa di isi dengan angka'
            ]
        ],
        // 'SISA' => [
        //     'rules' => 'required|numeric',
        //     'errors' => [
        //         'required' => 'Sisa tidak boleh kosong',
        //         'numeric' => 'Sisa hanya bisa di isi dengan angka'
        //     ]
        // ]
        'tanggal' => [
            'rules' => 'required|valid_date',
            'errors' => [
                'required' => 'Tanggal tidak boleh kosong',
                'valid_date' => 'Tanggal yang anda masukkan tidak sesuai format. Contoh Penulisan: 2022-07-27'
            ]
        ]
    ];
    public $stok_obat_update = [
        'tanggal' => [
            'rules' => 'required|valid_date',
            'errors' => [
                'required' => 'Tanggal tidak boleh kosong',
                'valid_date' => 'Tanggal yang anda masukkan tidak sesuai format. Contoh Penulisan: 2022-07-27'
            ]
        ],
        'NAMA_OBAT' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Nama obat tidak boleh kosong'
            ]
        ],
        'SATUAN' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Satuan tidak boleh kosong'
            ]
        ],
        'MASUK' => [
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Obat masuk tidak boleh kosong',
                'numeric' => 'Obat masuk hanya bisa di isi dengan angka'
            ]
        ],
        'KELUAR' => [
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Obat keluar tidak boleh kosong',
                'numeric' => 'Obat keluar hanya bisa di isi dengan angka'
            ]
        ],
        // 'SISA' => [
        //     'rules' => 'required|numeric',
        //     'errors' => [
        //         'required' => 'Sisa tidak boleh kosong',
        //         'numeric' => 'Sisa hanya bisa di isi dengan angka'
        //     ]
        // ],
    ];
    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------
}
