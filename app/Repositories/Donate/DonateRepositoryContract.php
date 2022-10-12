<?php

namespace App\Repositories\Donate;

use Illuminate\Database\Eloquent\Collection;

interface DonateRepositoryContract
{
    public function update($community, $data, $id);

    public function generateLink();

    public function getDonateById($id);

    public function getDonatesByCommunities(array $communityIds): Collection;
}
