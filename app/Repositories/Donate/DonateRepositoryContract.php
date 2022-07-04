<?php

namespace App\Repositories\Donate;

interface DonateRepositoryContract
{
    public function update($community, $data, $id);
}
