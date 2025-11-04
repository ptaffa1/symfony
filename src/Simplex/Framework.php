<?php
namespace Simplex;

// src/Simplex/Framework.php
namespace Simplex;

use Symfony\Component\HttpKernel\HttpKernel;

class Framework extends HttpKernel
{
    // No code needed here: HttpKernel hace el trabajo
}
//Ya no implementamos manualmente handle(). La orquestación la hace HttpKernel