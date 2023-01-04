<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\UX\Dropzone\Form\DropzoneType;

class UploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('files', DropzoneType::class, [
            'attr' => ['data-controller' => 'dropzone'],
            'required' => true,
            'multiple' => true,
//            'constraints' => [
//                new File(extensions: ['json']),
//            ],
        ]);
    }
}
