<?php
/*
 * This file is part of the "Smarttechno" company CRM system.
 *
 * (c) Pavel Evseenko <e.pavel@tut.by>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Form;

use App\Entity\Tender;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EditTenderFormType
 */
class EditTenderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startAt', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('endAt')
            ->add('openedAt', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('sentAt', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('title')
            ->add('description')
            ->add('attach', CollectionType::class, [
                'allow_add' => true,
                'entry_type' => TextType::class,
                'label' => false,
            ])
            ->add('result')
            ->add('contractor')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tender::class,
        ]);
    }
}
