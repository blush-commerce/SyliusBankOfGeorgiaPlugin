<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

final class BankOfGeorgiaGatewayConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('client_id', TextType::class);
        $builder->add('secret_key', TextType::class);
        $builder->add('intent', ChoiceType::class, [
            'choices' => [
                'CAPTURE' => 'CAPTURE',
                'AUTHORIZE' => 'AUTHORIZE'
            ]
        ]);
        $builder->add('capture_method', ChoiceType::class, [
            'choices' => [
                'AUTOMATIC' => 'AUTOMATIC',
                'MANUAL' => 'MANUAL'
            ]
        ]);
        $builder->add('show_shop_order_id_on_extract', CheckboxType::class);
        $builder->add('currency_code', ChoiceType::class, [
            'choices' => [
                'GEL' => 'GEL',
                'USD' => 'USD',
                'EUR' => 'EUR',
                'GBP' => 'GBP'
            ]
        ]);
        $builder->add('redirect_url', TextType::class);
    }
}