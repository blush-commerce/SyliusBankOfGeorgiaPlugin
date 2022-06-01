<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Entity;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ResourceInterface;

class StatusChangeCallback implements ResourceInterface
{
    use TimestampableTrait;

    /** @var mixed */
    protected $id;

    /** @var string|null */
    protected $orderId;

    /** @var string|null */
    protected $status;

    /** @var string|null */
    protected $paymentHash;

    /** @var string|null */
    protected $ipayPaymentId;

    /** @var string|null */
    protected $statusDescription;

    /** @var OrderInterface|null */
    protected OrderInterface $order;

    /** @var string|null */
    protected $paymentMethod;

    /** @var string|null */
    protected $cardType;

    /** @var string|null */
    protected $pan;

    /** @var string|null */
    protected $transactionId;

    /** @var string|null */
    protected $preAuthStatus;

    /** @var string|null */
    protected $captureMethod;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getOrderId(): ?string
    {
        return $this->orderId;
    }

    public function setOrderId(?string $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function getPaymentHash(): ?string
    {
        return $this->paymentHash;
    }

    public function setPaymentHash(?string $paymentHash): void
    {
        $this->paymentHash = $paymentHash;
    }

    public function getIpayPaymentId(): ?string
    {
        return $this->ipayPaymentId;
    }

    public function setIpayPaymentId(?string $ipayPaymentId): void
    {
        $this->ipayPaymentId = $ipayPaymentId;
    }

    public function getStatusDescription(): ?string
    {
        return $this->statusDescription;
    }

    public function setStatusDescription(?string $statusDescription): void
    {
        $this->statusDescription = $statusDescription;
    }

    public function getOrder(): ?OrderInterface
    {
        return $this->order;
    }

    public function setOrder(?OrderInterface $order): void
    {
        $this->order = $order;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?string $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function getCardType(): ?string
    {
        return $this->cardType;
    }

    public function setCardType(?string $cardType): void
    {
        $this->cardType = $cardType;
    }

    public function getPan(): ?string
    {
        return $this->pan;
    }

    public function setPan(?string $pan): void
    {
        $this->pan = $pan;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function setTransactionId(?string $transactionId): void
    {
        $this->transactionId = $transactionId;
    }

    public function getPreAuthStatus(): ?string
    {
        return $this->preAuthStatus;
    }

    public function setPreAuthStatus(?string $preAuthStatus): void
    {
        $this->preAuthStatus = $preAuthStatus;
    }

    public function getCaptureMethod(): ?string
    {
        return $this->captureMethod;
    }

    public function setCaptureMethod(?string $captureMethod): void
    {
        $this->captureMethod = $captureMethod;
    }
}
