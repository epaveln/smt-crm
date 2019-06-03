<?php
/*
 * This file is part of the "Smarttechno" company CRM system.
 *
 * (c) Pavel Evseenko <e.pavel@tut.by>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Custom;

use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Paginator
 */
class Paginator
{
    public static function paginate(PaginatorInterface $paginator, QueryBuilder $queryBuilder, Request $request)
    {
        return $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            30/*limit per page*/
        );
    }
}