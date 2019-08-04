<?php

namespace App\Civility;

use App\Entity\Civility;

class CivilityRequestHandler
{
    private $civilityFactory;
    private $civilityService;

    public function __construct(CivilityFactory $civilityFactory, CivilityService $civilityService)
    {
        $this->civilityFactory = $civilityFactory;
        $this->civilityService = $civilityService;
    }

    public function addCivility(CivilityRequest $civilityRequest): Civility
    {
        $civility = $this->civilityFactory->createFromCivilityRequest($civilityRequest);
        $this->civilityService->updateCivility($civility);

        return $civility;
    }

    public function updateCivility(CivilityRequest $civilityRequest, Civility $civility): void
    {
        $civility->update($civilityRequest);
        $this->civilityService->updateCivility($civility);
    }
}
