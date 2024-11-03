<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ColumnForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('columnName', TextType::class, [
                'label' => 'Column Name',
            ])
            ->add('dataType', ChoiceType::class, [
                'label' => 'Data Type',
                'choices' => [
                    'Integer' => 'INT',
                    'String' => 'VARCHAR(255)',
                    'Boolean' => 'TINYINT(1)',
                    'Text' => 'TEXT',
                    'DateTime' => 'DATETIME',
                    'Long Text' => 'LONGTEXT',
                ],
            ])
            ->add('isNullable', CheckboxType::class, [
                'label' => 'Is nullable',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
