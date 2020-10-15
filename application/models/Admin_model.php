<?php
/*
 * Copyright 2020
 * Sistem Informasi Pengadaan Barang By Satria Aprilian
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    public function get($table, $data = null, $where = null)
    {
        if ($data != null) {
            return $this->db->get_where($table, $data)->row_array();
        } else {
            return $this->db->get_where($table, $where)->result_array();
        }
    }

    public function update($table, $pk, $id, $data)
    {
        $this->db->where($pk, $id);
        return $this->db->update($table, $data);
    }

    public function insert($table, $data, $batch = false)
    {
        return $batch ? $this->db->insert_batch($table, $data) : $this->db->insert($table, $data);
    }

    public function delete($table, $pk, $id)
    {
        return $this->db->delete($table, [$pk => $id]);
    }

    public function getUsers($id)
    {
        $this->db->where('id_user !=', $id);
        return $this->db->get('pb_user')->result_array();
    }

    public function getBarang()
    {
        $this->db->join('pb_jenis j', 'b.jenis_id = j.id_jenis');
        $this->db->join('pb_satuan s', 'b.satuan_id = s.id_satuan');
        $this->db->order_by('id_barang');
        return $this->db->get('pb_barang b')->result_array();
    }

    public function getBarangMasuk($limit = null, $id_barang = null, $range = null)
    {
        $this->db->select('*');
        $this->db->join('pb_user u', 'bm.user_id = u.id_user');
        $this->db->join('pb_supplier sp', 'bm.supplier_id = sp.id_supplier');
        $this->db->join('pb_barang b', 'bm.barang_id = b.id_barang');
        $this->db->join('pb_satuan s', 'b.satuan_id = s.id_satuan');
        if ($limit != null) {
            $this->db->limit($limit);
        }

        if ($id_barang != null) {
            $this->db->where('id_barang', $id_barang);
        }

        if ($range != null) {
            $this->db->where('tanggal_masuk' . ' >=', $range['mulai']);
            $this->db->where('tanggal_masuk' . ' <=', $range['akhir']);
        }

        $this->db->order_by('id_barang_masuk', 'DESC');
        return $this->db->get('pb_barang_masuk bm')->result_array();
    }

    public function getBarangKeluar($limit = null, $id_barang = null, $range = null)
    {
        $this->db->select('*');
        $this->db->join('pb_user u', 'bk.user_id = u.id_user');
        $this->db->join('pb_barang b', 'bk.barang_id = b.id_barang');
        $this->db->join('pb_satuan s', 'b.satuan_id = s.id_satuan');
        if ($limit != null) {
            $this->db->limit($limit);
        }
        if ($id_barang != null) {
            $this->db->where('id_barang', $id_barang);
        }
        if ($range != null) {
            $this->db->where('tanggal_keluar' . ' >=', $range['mulai']);
            $this->db->where('tanggal_keluar' . ' <=', $range['akhir']);
        }
        $this->db->order_by('id_barang_keluar', 'DESC');
        return $this->db->get('pb_barang_keluar bk')->result_array();
    }

    public function getMax($table, $field, $kode = null)
    {
        $this->db->select_max($field);
        if ($kode != null) {
            $this->db->like($field, $kode, 'after');
        }
        return $this->db->get($table)->row_array()[$field];
    }

    public function count($table)
    {
        return $this->db->count_all($table);
    }

    public function sum($table, $field)
    {
        $this->db->select_sum($field);
        return $this->db->get($table)->row_array()[$field];
    }

    public function min($table, $field, $min)
    {
        $field = $field . ' <=';
        $this->db->where($field, $min);
        return $this->db->get($table)->result_array();
    }

    public function chartBarangMasuk($bulan)
    {
        $like = 'BM-' . date('y') . $bulan;
        $this->db->like('id_barang_masuk', $like, 'after');
        return count($this->db->get('pb_barang_masuk')->result_array());
    }

    public function chartBarangKeluar($bulan)
    {
        $like = 'BK-' . date('y') . $bulan;
        $this->db->like('id_barang_keluar', $like, 'after');
        return count($this->db->get('pb_barang_keluar')->result_array());
    }

    public function laporan($table, $mulai, $akhir)
    {
        $tgl = $table == 'pb_barang_masuk' ? 'tanggal_masuk' : 'tanggal_keluar';
        $this->db->where($tgl . ' >=', $mulai);
        $this->db->where($tgl . ' <=', $akhir);
        return $this->db->get($table)->result_array();
    }

    public function cekStok($id)
    {
        $this->db->join('pb_satuan s', 'b.satuan_id=s.id_satuan');
        return $this->db->get_where('pb_barang b', ['id_barang' => $id])->row_array();
    }
}
