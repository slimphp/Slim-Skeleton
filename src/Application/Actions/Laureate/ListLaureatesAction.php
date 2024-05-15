<?php

declare(strict_types=1);

namespace App\Application\Actions\Laureate;

use Psr\Http\Message\ResponseInterface as Response;

class ListLaureatesAction extends LaureateAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $laureates = $this->laureateRepository->findAll();

        $this->logger->info("Laureates list was viewed.");

        return $this->respondWithData($laureates);
    }
}