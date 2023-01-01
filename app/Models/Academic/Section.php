<?php

namespace App\Models\Academic;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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

    public static function getSectionsByClassNumeric($section_numeric)
    {
        return DB::table('sections')->select('section_id', 'section_name', 'section_numeric')->where('section_numeric', $section_numeric)->orderBy('section_name', 'asc')->get();
    }


}