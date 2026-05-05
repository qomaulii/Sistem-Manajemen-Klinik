<?php

namespace App\Models;

use CodeIgniter\Model;

class MyBaseModel extends Model
{
    // Fungsi untuk memetakan data DB menjadi properti objek
    public function populate($row) {
        if ($row) {
            foreach ($row as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }

    // Menggantikan load() CI3
    public function load($id) {
        $row = $this->find($id);
        $this->populate($row);
        return $this;
    }

    // Menggantikan save() CI3 yang menyimpan langsung dari properti
    public function save($data = null): bool {
        if ($data === null) {
            $data = [];
            foreach ($this->allowedFields as $field) {
                if (property_exists($this, $field)) {
                    $data[$field] = $this->{$field};
                }
            }
        }

        // Cek apakah primary key ada DAN nilainya tidak kosong/null
        $pkValue = $this->{$this->primaryKey} ?? null;
        
        if (!empty($pkValue)) {
            // UPDATE jika primary key sudah ada
            return parent::update($pkValue, $data);
        } else {
            // INSERT jika primary key belum ada
            $insertID = parent::insert($data);
            if ($insertID) {
                $this->{$this->primaryKey} = $insertID;
                return true;
            }
            return false;
        }
    }

    // Menggantikan get() CI3
    public function get($limit = 0, $offset = 0, $reverse = 0) {
        $builder = $this->builder();
        if ($reverse) {
            $builder->orderBy($this->primaryKey, 'desc');
        } else {
            $builder->orderBy($this->primaryKey, 'asc');
        }
        
        if ($limit) $builder->limit($limit, $offset);
        $query = $builder->get();
        
        $ret_val = [];
        $class = get_class($this);
        foreach ($query->getResult() as $row) {
            $model = new $class();
            $model->populate($row);
            $ret_val[$row->{$this->primaryKey}] = $model;
        }
        return $ret_val;
    }

    // Menggantikan get_by_fkey() CI3
    public function get_by_fkey($fkey, $value, $order = 'desc', $limit = 1) {
        $builder = $this->builder();
        if ($limit) $builder->limit($limit);
        $builder->orderBy($this->primaryKey, $order);
        $query = $builder->where($fkey, $value)->get();
        
        if ($limit == 1) {
            $this->populate($query->getRow());
            return $this;
        } else {
            $ret_val = [];
            $class = get_class($this);
            foreach ($query->getResult() as $row) {
                $model = new $class();
                $model->populate($row);
                $ret_val[$row->{$this->primaryKey}] = $model;
            }
            return $ret_val;
        }
    }

    // Menggantikan delete() CI3
    public function delete($id = null, bool $purge = false) {
        if ($id === null && isset($this->{$this->primaryKey})) {
            $id = $this->{$this->primaryKey};
        }
        return parent::delete($id, $purge);
    }
}