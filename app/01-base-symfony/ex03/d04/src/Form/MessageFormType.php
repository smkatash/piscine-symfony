<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Event\SubmitEvent;

class MessageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('message', TextType::class, [
                'label' => 'Message',
            ])
            ->add('includeTimestamp', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
                'label' => 'Include Timestamp',
                ])
            ->add('submit', SubmitType::class, ['label' => 'Submit']);

        $builder->addEventListener(FormEvents::SUBMIT, function (SubmitEvent $event) {
            $formData = $event->getData();
                
            if ($formData->getIncludeTimestamp()) {
                $formData->setTimestamp(new \DateTimeImmutable());
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}