<?php

declare(strict_types=1);

namespace Moon\Container\Exception;

use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends \InvalidArgumentException implements NotFoundExceptionInterface
{
}
