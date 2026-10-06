<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $table='document_types';

    protected $fillable = ['doc_type','formate'];
}