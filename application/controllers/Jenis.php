<?php
/*
 * Copyright 2020
 * Sistem Informasi Pengadaan Barang By Satria Aprilian
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Jenis extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();

        $this->load->model('Admin_model', 'admin');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = "Jenis";
        $data['jenis'] = $this->admin->get('jenis');
        $this->template->load('templates/dashboard', 'jenis/data', $data);
    }

    private function _validasi()
    {
        $this->form_validation->set_rules('nama_jenis', 'Nama Jenis', 'required|trim');
    }

    public function add()
    {
        $this->_validasi();

        if ($this->form_validation->run() == false) {
            $data['title'] = "Jenis";
            $this->template->load('templates/dashboard', 'jenis/add', $data);
        } else {
            $input = $this->input->post(null, true);
            $insert = $this->admin->insert('pb_jenis', $input);
            if ($insert) {
                set_pesan('data berhasil disimpan');
                redirect('jenis');
            } else {
                set_pesan('data gagal disimpan', false);
                redirect('jenis/add');
            }
        }
    }

    public function edit($getId)
    {
        $id = encode_php_tags($getId);
        $this->_validasi();

        if ($this->form_validation->run() == false) {
            $data['title'] = "Jenis";
            $data['jenis'] = $this->admin->get('pb_jenis', ['id_jenis' => $id]);
            $this->template->load('templates/dashboard', 'jenis/edit', $data);
        } else {
            $input = $this->input->post(null, true);
            $update = $this->admin->update('pb_jenis', 'id_jenis', $id, $input);
            if ($update) {
                set_pesan('data berhasil disimpan');
                redirect('jenis');
            } else {
                set_pesan('data gagal disimpan', false);
                redirect('jenis/add');
            }
        }
    }

    public function delete($getId)
    {
        $id = encode_php_tags($getId);
        if ($this->admin->delete('pb_jenis', 'id_jenis', $id)) {
            set_pesan('data berhasil dihapus.');
        } else {
            set_pesan('data gagal dihapus.', false);
        }
        redirect('jenis');
    }
}
