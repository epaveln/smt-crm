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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TenderFormType
 */
class TenderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('contractor')
            ->add('title')
            ->add('description')
            ->add('startAt', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('endAt')
            ->add('openedAt', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('attach', CollectionType::class, [
                'allow_add' => true,
                'entry_type' => FileType::class,
                'label' => false,
            ])
            //->add('result')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tender::class,
            'allow_extra_fields' => true,
        ]);
    }
}
