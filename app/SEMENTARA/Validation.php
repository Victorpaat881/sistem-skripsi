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

        'tanggal' => [
            'rules' => 'required|valid_date',
            'errors' => [
                'required' => 'Tanggal kadaluarsa tidak boleh kosong',
                'valid_date' => 'Tanggal kadaluarsa yang anda masukkan tidak sesuai format. Contoh Penulisan: 2022-07-27'
            ]
        ]
    ];
    public $stok_obat_update = [
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
        'tanggal' => [
            'rules' => 'required|valid_date',
            'errors' => [
                'required' => 'Tanggal kadaluarsa tidak boleh kosong',
                'valid_date' => 'Tanggal kadaluarsa yang anda masukkan tidak sesuai format. Contoh Penulisan: 2022-07-27'
            ]
        ]
    ];
    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------
}
