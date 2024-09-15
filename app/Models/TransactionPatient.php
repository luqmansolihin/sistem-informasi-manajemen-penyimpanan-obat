<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionPatient extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id')->withTrashed();
    }

    public function transactionPatientHasMedicines(): HasMany
    {
        return $this->hasMany(TransactionPatientHasMedicine::class, 'transaction_patient_id', 'id');
    }
}
