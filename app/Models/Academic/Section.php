<?php

namespace App\Models\Academic;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'sections';
    protected $primaryKey = 'section_id';

    public function form()
    {
        return $this->hasOne(Form::class, 'form_numeric', 'section_numeric');
    }

      /**
     * Get teacher owning the section.
     *
     */
    public function teacher()
    {
        return $this->hasOne(User::class, 'id', 'section_teacher');
    }


}