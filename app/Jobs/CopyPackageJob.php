<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Classes\CopyPackageClass;

class CopyPackageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $fromId,$toId,$copyPackage;

    public function __construct($fromId,$toId)
    {
        $this->fromId=$fromId;
        $this->toId=$toId;
        $this->copyPackage=new CopyPackageClass();
    }

    public function handle()
    {
        $this->copyPackage->copyPackage($this->fromId,$this->toId);
    }
}
