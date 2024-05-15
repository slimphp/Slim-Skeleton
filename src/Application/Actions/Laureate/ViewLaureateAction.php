<?php

declare(strict_types=1);

namespace App\Application\Actions\Laureate;

use Psr\Http\Message\ResponseInterface as Response;

class ViewLaureateAction extends LaureateAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $laureateId = (int) $this->resolveArg('id');
        $laureate = $this->laureateRepository->findLaureateOfId($laureateId);

        $this->logger->info("Laureate of id `{$laureateId}` was viewed.");

        return $this->respondWithData($laureate);
    }
}
