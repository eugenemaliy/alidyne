<?php

namespace Alydine\FrontBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AlydineFrontBundle extends Bundle
{
	public function getParent()
    {
        return 'FOSUserBundle';
    }
}
