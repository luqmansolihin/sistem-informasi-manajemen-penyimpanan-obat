<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionPatientHasMedicine extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class, 'medicine_id', 'id')->withTrashed();
    }

    public function medicineSales(): HasMany
    {
        return $this->hasMany(MedicineSale::class, 'transaction_patient_has_medicine_id', 'id');
    }
}
