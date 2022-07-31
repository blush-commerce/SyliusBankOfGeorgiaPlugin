<?php

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Entity;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\Order as BaseOrder;

class Order extends BaseOrder
{
    /**
     * @var Collection|StatusChangeCallback[]
     *
     * @psalm-var Collection<array-key, StatusChangeCallback>
     */
    protected $statusChangeCallbacks;

    public function getStatusChangeCallbacks(): Collection
    {
        return $this->statusChangeCallbacks;
    }

    public function addStatusChangeCallback(StatusChangeCallback $statusChangeCallback): void
    {
        if (!$this->statusChangeCallbacks->contains($statusChangeCallback)) {
            $this->statusChangeCallbacks->add($statusChangeCallback);
        }
    }

    public function hasStatusChangeCallbacks(): bool
    {
        return !$this->statusChangeCallbacks->isEmpty();
    }

    public function getLastStatusChangeCallback(): ?StatusChangeCallback
    {
        if (!$this->hasStatusChangeCallbacks()) {
            return null;
        }

        return $this->statusChangeCallbacks->last();
    }

    public function usesPreAuthorization(): ?bool
    {
        if ($this->hasStatusChangeCallbacks()) {
            if (null !== $this->getLastStatusChangeCallback()->getPreAuthStatus()) {
                return true;
            } else {
                return false;
            }
        }

        return null;
    }
}
