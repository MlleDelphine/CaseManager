<?php

namespace SecurityAppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SecurityAppBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}

