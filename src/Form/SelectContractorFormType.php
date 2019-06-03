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

use App\Entity\Country;
use App\Repository\CountryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SelectContractorFormType
 */
class SelectContractorFormType extends AbstractType
{
    private $countries;

    public function __construct(CountryRepository $countryRepository)
    {
        foreach ($countryRepository->findAll() as $value) {
            $this->countries[$value->getName()] = $value->getId();
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', ChoiceType::class, [
                'placeholder' => 'Выберите страну',
                'choices' => $this->countries,
                'required' => false,
            ])
            ->add('contractor', ChoiceType::class, [
                'placeholder' => 'Выберите компанию',
                'choices' => [],
                'required' => false,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Country::class,
        ]);
    }
}
