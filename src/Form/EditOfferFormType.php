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

use App\Entity\Offer;
use App\Form\DataTransformer\StringToFileTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditOfferFormType extends AbstractType
{
    private $projectDir;

    public function __construct($projectDir)
    {
        $this->projectDir = $projectDir;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number')
            //->add('createdAt')
            ->add('title')
            ->add('attach', CollectionType::class, [
                'allow_add' => true,
                'entry_type' => TextType::class,
                'label' => false,
            ])
            ->add('offerType')
            //->add('user')
            ->add('contractor')
			->add('createdAt', DateType::class, [
                'widget' => 'single_text',
            ]);
            //DATA TRANSFORMER
            //->addModelTransformer(new StringToFileTransformer($this->projectDir));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
            'allow_add' => true,
        ]);
    }
}