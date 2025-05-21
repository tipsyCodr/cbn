<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpressionOfInterest extends Model
{
    protected $fillable = [
        'form_type',
        'chapter_name',
        'region',
        'invited_by',
        'visit_date',
        'referral_source',
        'previous_member',
        'full_name',
        'company_name',
        'professional_classification',
        'email',
        'mobile',
        'experience_rating',
        'product_service_feedback',
    ];
}
