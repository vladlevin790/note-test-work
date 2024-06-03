<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $table = 'note';

    protected $fillable = [
        "fullname",
        "company",
        "phone-number",
        "email",
        "date_birth",
        "path_to_photo"
    ];

}
