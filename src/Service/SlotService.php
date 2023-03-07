<?php

namespace App\Service;

use App\Entity\Slot;
use App\Entity\SlotAvailableDTO;
use App\Entity\Store;
use App\Repository\SlotRepository;
use App\Repository\StoreProductRepository;
use App\Repository\StoreRepository;

class SlotService
{
    public function __construct(
        private readonly SlotRepository $slotRepository
    )
    {
    }

    private function slotToSlotAvailable(Slot $slot): SlotAvailableDTO {
        $sa = new SlotAvailableDTO();
        $sa->setId($slot->getId());
        $sa->setUser($slot->getUser());
        $sa->setStore($slot->getStore());
        $sa->setStartDate($slot->getStartDate());
        $sa->setEndDate($slot->getEndDate());
        $sa->setAvailable(is_null($slot->getUser() ?? null));
        return $sa;
    }

    /**
     * @param Store $store
     * @return SlotAvailableDTO[]
     */
    public function availableList(Store $store): array {
        $slots = $this->slotRepository->findBy(['store' => $store->getId()]);
        return array_map(fn($s) => $this->slotToSlotAvailable($s), $slots);
    }
}
