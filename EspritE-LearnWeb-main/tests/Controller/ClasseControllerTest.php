<?php

namespace App\Test\Controller;

use App\Entity\Classe;
use App\Repository\ClasseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClasseControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ClasseRepository $repository;
    private string $path = '/classe/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Classe::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Classe index');

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
            'classe[nomclasse]' => 'Testing',
            'classe[filiere]' => 'Testing',
            'classe[nbreetudi]' => 'Testing',
            'classe[niveaux]' => 'Testing',
        ]);

        self::assertResponseRedirects('/classe/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Classe();
        $fixture->setNomclasse('My Title');
        $fixture->setFiliere('My Title');
        $fixture->setNbreetudi('My Title');
        $fixture->setNiveaux('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Classe');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Classe();
        $fixture->setNomclasse('My Title');
        $fixture->setFiliere('My Title');
        $fixture->setNbreetudi('My Title');
        $fixture->setNiveaux('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'classe[nomclasse]' => 'Something New',
            'classe[filiere]' => 'Something New',
            'classe[nbreetudi]' => 'Something New',
            'classe[niveaux]' => 'Something New',
        ]);

        self::assertResponseRedirects('/classe/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNomclasse());
        self::assertSame('Something New', $fixture[0]->getFiliere());
        self::assertSame('Something New', $fixture[0]->getNbreetudi());
        self::assertSame('Something New', $fixture[0]->getNiveaux());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Classe();
        $fixture->setNomclasse('My Title');
        $fixture->setFiliere('My Title');
        $fixture->setNbreetudi('My Title');
        $fixture->setNiveaux('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/classe/');
    }
}
