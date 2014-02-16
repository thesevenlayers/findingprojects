<?php

namespace FP\BEBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FPBEBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
