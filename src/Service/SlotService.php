<?php

namespace App\Service;

use App\Entity\Command;
use App\Entity\Slot;
use App\Entity\SlotAvailableDTO;
use App\Entity\Store;
use App\Entity\User;
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
        $sa->setStore($slot->getStore());
        $sa->setStartDate($slot->getStartDate());
        $sa->setEndDate($slot->getEndDate());
        $sa->setAvailable(is_null($slot->getCommand() ?? null));
        return $sa;
    }

    /**
     * @param int $id
     * @param Store $store
     * @return Slot|null
     */
    public function getByIdAndStore(int $id, Store $store): Slot | null {
        return $this->slotRepository->findOneBy(['id' => $id, 'store' => $store->getId()]);
    }

    /**
     * @param Store $store
     * @return SlotAvailableDTO[]
     */
    public function availableList(Store $store): array {
        $slots = $this->slotRepository->findBy(['store' => $store->getId()]);
        return array_map(fn($s) => $this->slotToSlotAvailable($s), $slots);
    }

    /**
     * @param Store $store
     * @param int $page
     * @param int $limit
     * @return SlotAvailableDTO[]
     */
    public function availableListPagination(Store $store, int $page, int $limit): array {
        $slots = $this->slotRepository->findAllByStoreWithPagination($store->getId(), $page, $limit);
        return array_map(fn($s) => $this->slotToSlotAvailable($s), $slots);
    }

    /**
     * @param Slot $slot
     * @param bool $flush
     * @return Slot
     */
    public function saveSlot(Slot $slot, bool $flush = false): Slot {
        $this->slotRepository->save($slot, $flush);
        return $slot;
    }

    /**
     * return null if already booked
     * @param Slot $slot
     * @param Command $command
     * @param bool $flush
     * @return SlotAvailableDTO|null
     */
    public function bookSlot(Slot $slot, Command $command, bool $flush = false): SlotAvailableDTO | null {
        if (!is_null($slot->getCommand() ?? null)) return null;
        $slot->setCommand($command);
        $command->setSlot($slot);
        $this->slotRepository->save($slot, $flush);
        return $this->slotToSlotAvailable($slot);
    }
}
