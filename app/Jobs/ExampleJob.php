<?php

namespace App\Jobs;

use App\Models\User;

class ExampleJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       User::get();
       //Delete users
    }
}
