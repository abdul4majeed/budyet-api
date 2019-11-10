<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    protected $fillable = ['name','email','msg'];
    function CreateRecord($data)
    {
        $this->create($data);
    }
}
