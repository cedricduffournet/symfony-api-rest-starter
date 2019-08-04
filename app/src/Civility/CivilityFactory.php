<?php

namespace App\Civility;

use App\Entity\Civility;

class CivilityFactory
{
    public function createFromCivilityRequest(CivilityRequest $request): Civility
    {
        return Civility::create($request->name, $request->code);
    }
}
