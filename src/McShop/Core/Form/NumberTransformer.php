<?php
namespace McShop\Core\Form;

use Symfony\Component\Console\Question\Question;
use Symfony\Component\Form\Form;

class NumberTransformer extends AbstractTransformer
{
    /**
     * @param Form $form
     *
     * @return Question
     */
    public function transform(Form $form)
    {
        return new Question($this->questionFrom($form), $this->defaultValueFrom($form));
    }
}