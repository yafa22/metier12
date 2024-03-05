<?php

namespace App\Form;

use App\Entity\Abonnement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class AbonnementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
            ->add('date_debut')
            ->add('date_fin')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abonnement::class,
        ]);
    }
    
    public function validatePrice($value, ExecutionContextInterface $context)
    {
        // Your logic to validate the price based on the selected type
        $data = $context->getRoot()->getData();

        if ($data->getType() === 'Option 1') {
            $prix = $data->getPrix();

            if ($prix < 50 || $prix > 75) {
                $context->buildViolation('For Option 1, the price must be between 50 and 75.')
                    ->atPath('prix')
                    ->addViolation();
            }
        }
    }
}
