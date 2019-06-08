<?php

namespace App\Service\Traits;

use Symfony\Contracts\Translation\TranslatorInterface;

trait TranslatorAwareTrait
{
    /** @var TranslatorInterface */
    private $translator;

    /**
     * @required
     */
    public function setTranslator(TranslatorInterface $translator): void
    {
        $this->translator = $translator;
    }
}
