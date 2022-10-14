<?php namespace App\Models\Lib\Hrm;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Religions extends Model  {
    
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'lib_religions';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'user_id'];

	/**
	 * The attributes use for soft delete.
	 *
	 * @var timestamp
	 */
    protected $dates = ['deleted_at'];
    
    /**
     * Religions::employees()
     * 
     * @return
     */
    public function employees() {
        return $this->hasMany('App\Models\Hrm\EmployeeBasicInfos');
    }

}