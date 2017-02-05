<?php
/**
 * Created by PhpStorm.
 * User: mops1k
 * Date: 05.02.2017
 * Time: 12:19
 */

namespace McShop\ServersBundle\Services\Form;


use Matthias\SymfonyConsoleForm\Bridge\Transformer\AbstractTransformer;
use Matthias\SymfonyConsoleForm\Form\FormUtil;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Translation\TranslatorInterface;

class NumberTransformer extends AbstractTransformer
{
    /** @var TranslatorInterface */
    private $translator;

    /**
     * NumberTransformer constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param FormInterface $form
     *
     * @return string
     */
    protected function questionFrom(FormInterface $form)
    {
        $question = FormUtil::label($form);
        $question = $this->translator->trans($question);

        return $this->formattedQuestion($question, $this->defaultValueFrom($form));
    }

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