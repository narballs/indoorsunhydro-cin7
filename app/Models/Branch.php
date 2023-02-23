<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
      'branchId',
      'secondaryContactId',
      'branchLocationId',
      'isActive',
      'company',
      'firstName',
      'lastName',
      'jobTitle',
      'email',
      'website',
      'phone',
      'fax',
      'mobile',
      'address1',
      'address2',
      'city',
      'state',
      'country',
      'postalAddress1',
      'postalAddress2',
      'postalCity',
      'postalPostCode',
      'postalState',
      'postalCountry',
      'notes',
      'integrationRef',
      'customFields',
      'branchType',
      'stockControlOptions',
      'taxStatus',
      'accountNumber',
    ];
}
