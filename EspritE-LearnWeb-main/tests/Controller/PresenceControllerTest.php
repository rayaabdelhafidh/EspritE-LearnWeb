<?php

namespace App\Test\Controller;

use App\Entity\Presence;
use App\Repository\PresenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PresenceControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private PresenceRepository $repository;
    private string $path = '/presence/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Presence::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Presence index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'presence[date]' => 'Testing',
            'presence[seance]' => 'Testing',
            'presence[nomclasse]' => 'Testing',
            'presence[idclasse]' => 'Testing',
        ]);

        self::assertResponseRedirects('/presence/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Presence();
        $fixture->setDate('My Title');
        $fixture->setSeance('My Title');
        $fixture->setNomclasse('My Title');
        $fixture->setIdclasse('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Presence');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Presence();
        $fixture->setDate('My Title');
        $fixture->setSeance('My Title');
        $fixture->setNomclasse('My Title');
        $fixture->setIdclasse('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'presence[date]' => 'Something New',
            'presence[seance]' => 'Something New',
            'presence[nomclasse]' => 'Something New',
            'presence[idclasse]' => 'Something New',
        ]);

        self::assertResponseRedirects('/presence/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDate());
        self::assertSame('Something New', $fixture[0]->getSeance());
        self::assertSame('Something New', $fixture[0]->getNomclasse());
        self::assertSame('Something New', $fixture[0]->getIdclasse());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Presence();
        $fixture->setDate('My Title');
        $fixture->setSeance('My Title');
        $fixture->setNomclasse('My Title');
        $fixture->setIdclasse('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/presence/');
    }
}
