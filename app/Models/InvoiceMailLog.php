<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceMailLog extends Model
{
    use HasFactory;

    protected $table = 'invoice_mail_logs';

    protected $fillable = ['email','request_id','error','type'];
}
