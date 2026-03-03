<?php

namespace Webkul\AdminApi\Repositories;

use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository as BaseClientRepository;
use Laravel\Passport\Passport;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ClientRepository extends BaseClientRepository
{
    /**
     * Get a client by the given ID.
     *
     * @param  int|string  $id
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function find($id): ?Client
    {
        $client = Passport::client();

        $client = $client->where($client->getKeyName(), $id)->first();
        if (request()->has('username')) {
            $username = request()->get('username');
            $user = $client?->admins()->where('email', $username)->get()->first();

            if (! $user) {
                $client = null;
            }
        }

        return $client;
    }

    /**
     * Get an active client by the given ID.
     *
     * @param  int|string  $id
     */
    public function findActive($id): ?Client
    {
        $client = $this->find($id);

        return $client && ! $client->revoked ? $client : null;
    }
}
