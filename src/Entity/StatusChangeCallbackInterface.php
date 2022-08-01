<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Entity;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface StatusChangeCallbackInterface extends ResourceInterface
{
    public const STATUS_SUCCESS = 'success';

    public const STATUS_ERROR = 'error';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const PRE_AUTH_STATUS_IN_PROGRESS = 'in_progress';

    public function getId();

    public function getOrderId(): ?string;

    public function setOrderId(?string $orderId): void;

    public function getStatus(): ?string;

    public function setStatus(?string $status): void;

    public function isSuccessful(): bool;

    public function getPaymentHash(): ?string;

    public function setPaymentHash(?string $paymentHash): void;

    public function getIpayPaymentId(): ?string;

    public function setIpayPaymentId(?string $ipayPaymentId): void;

    public function getStatusDescription(): ?string;

    public function setStatusDescription(?string $statusDescription): void;

    public function getOrder(): ?OrderInterface;

    public function setOrder(?OrderInterface $order): void;

    public function getPaymentMethod(): ?string;

    public function setPaymentMethod(?string $paymentMethod): void;

    public function getCardType(): ?string;

    public function setCardType(?string $cardType): void;

    public function getPan(): ?string;

    public function setPan(?string $pan): void;

    public function getTransactionId(): ?string;

    public function setTransactionId(?string $transactionId): void;

    public function getPreAuthStatus(): ?string;

    public function setPreAuthStatus(?string $preAuthStatus): void;

    public function usesPreAuthorization(): bool;

    public function getCaptureMethod(): ?string;

    public function setCaptureMethod(?string $captureMethod): void;
}
