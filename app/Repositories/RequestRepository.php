<?php 
namespace App\Repositories;

use App\Models\Requests;

class RequestRepository{

    public function add_request($data){
        return Requests::create($data);
    }
}
