<?php
namespace App\Service;

use App\Entity\Character;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Created by PhpStorm.
 * User: Abderrahim
 * Date: 24/06/2022
 * Time: 15:00
 */
class MarvelService
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var HttpClientInterface
     */
    private $client;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * MarvelService constructor.
     * @param HttpClientInterface $client
     * @param EntityManagerInterface $entityManager
     * @param ContainerInterface $container
     */
    function __construct(HttpClientInterface $client, EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        $this->container = $container;
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    /**
     * Import list of characters
     * @return array
     */
    public function import(){
        $characters = $this->entityManager->getRepository(Character::class)->findAll();

        // Check characters number
        $charactersTotal = $this->getCharactersTotal();
        if ($charactersTotal === 0)
            return ['type'=>"error",'message'=>"Please retry later"];
        elseif ($charactersTotal <= count($characters))
            return ['type'=>"success",'message'=>"List successfully updated"];

        // Get 100 by 100 characters
        $offset = 0;
        for ($i=0; $i<=intval(floor($charactersTotal/100)); $i++){
            $response = $this->charactersRequest($offset);
            $statusCode = $response->getStatusCode();
            if ($statusCode !== 200)
                continue;
            $content = $response->toArray();
            $results = $content['data']['results'];
            $batchSize = 20;
            $j = 0;
            foreach ($results as $result){
                $this->createCharacter($result);
                if (($j % $batchSize) === 0) {
                    $this->entityManager->flush();
                    $this->entityManager->clear(); // Detaches all objects from Doctrine!
                }
                $j++;
            }
            $this->entityManager->flush(); // Persist objects that did not make up an entire batch
            $this->entityManager->clear();
            $offset+=100;
        }
        return ['type'=>"success",'message'=>"List successfully updated"];
    }

    /**
     * Get number of characters
     * @return int
     */
    private function getCharactersTotal(){
        $response = $this->charactersRequest();
        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200)
            return 0;
        $content = $response->toArray();
        return $content['data']['total'];
    }

    /**
     * Get characters request
     * @param int $offset
     * @param int $limit
     * @return \Symfony\Contracts\HttpClient\ResponseInterface
     */
    private function charactersRequest($offset = 0, $limit = 100){
        // Generate a hash value
        $hash = md5(
            '1'.
            $this->container->getParameter('marvel_private_key') .
            $this->container->getParameter('marvel_public_key')
        );
        // Get request
        $response = $this->client->request(
            'GET',
            $this->container->getParameter('marvel_uri') . '/v1/public/characters',
            [
                'query' => [
                    'offset'    => $offset,
                    'limit'    => $limit,
                    'ts'        => '1',
                    'apikey'    => $this->container->getParameter('marvel_public_key'),
                    'hash'      => $hash,
                ]
            ]
        );
        return $response;
    }

    /**
     * Create new character if not exist
     * @param $data
     * @return bool
     */
    private function createCharacter($data){
        $character = $this->entityManager->getRepository(Character::class)->find($data['id']);
        if ($character)
            return false;

        $character = new Character();
        $character->setId($data['id']);
        $character->setName($data['name']);
        $character->setPictureUrl($data['thumbnail']['path']);
        $character->setPictureType($data['thumbnail']['extension']);

        $this->entityManager->persist($character);
        return true;
    }


}