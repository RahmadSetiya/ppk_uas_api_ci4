<?php

namespace App\Models;

use CodeIgniter\Model;

class ExcerciseModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'excercises';
    protected $primaryKey       = 'excercise_id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'excercise_id',
        'user_id',
        'name',
        'type',
        'datetime',
        'is_done',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    function get_excercise_stat($id = null){
        if($id == null){
            return  $this->join('stats', 'stats.excercise_id = excercises.excercise_id', 'left')->findAll();
        }
        return $this->join('stats', 'stats.excercise_id = excercises.excercise_id', 'left')->where('excercises.excercise_id', $id)->first();
    }

    function get_excercise_stat_by_user($id){
        return $this->join('stats', 'stats.excercise_id = excercises.excercise_id', 'left')->where('stats.user_id', $id)->findAll();
    }
}
