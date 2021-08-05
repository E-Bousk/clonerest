<?php

namespace App\Form;

use App\Entity\Pin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image (JPG or PNG file)',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Delete ?',
                'download_label' => 'Download file',
                'download_uri' => true,
                'image_uri' => true,
                'imagine_pattern' => 'squared_thumbnail_small',
                'asset_helper' => true,
            ])
            ->add('title', null, [
                'attr' => [
                    'autofocus' => true,
                    'placeholder' => "Ici : message du placeholder"
                ]
                // 'required' => false
            ])
            ->add('description', null, [
                'attr' => [
                    'rows' => '3',
                    'cols' => '30',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pin::class,
        ]);
    }
}
