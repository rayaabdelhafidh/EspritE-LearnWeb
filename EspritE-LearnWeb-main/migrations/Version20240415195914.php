<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240415195914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classe (idClasse INT AUTO_INCREMENT NOT NULL, nom_classe VARCHAR(255) NOT NULL, filiere VARCHAR(255) NOT NULL, nbreetudi INT NOT NULL, niveaux VARCHAR(255) NOT NULL, PRIMARY KEY(idClasse)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cour (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(500) NOT NULL, duree INT NOT NULL, objectif VARCHAR(500) NOT NULL, image VARCHAR(300) NOT NULL, courspdfurl VARCHAR(500) NOT NULL, note INT NOT NULL, nblike INT NOT NULL, idMatiere INT DEFAULT NULL, INDEX IDX_A71F964F80AD3CB8 (idMatiere), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere (idm INT AUTO_INCREMENT NOT NULL, idplandetude_id INT DEFAULT NULL, nomm VARCHAR(255) NOT NULL, idenseignant INT NOT NULL, nbrheure INT NOT NULL, coefficient INT NOT NULL, semester INT NOT NULL, credit INT NOT NULL, INDEX IDX_9014574A9C30ADCC (idplandetude_id), PRIMARY KEY(idm)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plandetude (id INT AUTO_INCREMENT NOT NULL, nomprogramme VARCHAR(500) NOT NULL, niveau VARCHAR(250) NOT NULL, dureetotal INT NOT NULL, creditsrequistotal INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE presence (idpresence INT AUTO_INCREMENT NOT NULL, date VARCHAR(255) NOT NULL, seance VARCHAR(255) NOT NULL, nomClasse VARCHAR(255) NOT NULL, idClasse INT DEFAULT NULL, INDEX IDX_6977C7A5EC96170C (idClasse), PRIMARY KEY(idpresence)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, id_classe INT NOT NULL, etat_presence VARCHAR(255) NOT NULL, IdClasse INT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D64915E0755A (IdClasse), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cour ADD CONSTRAINT FK_A71F964F80AD3CB8 FOREIGN KEY (idMatiere) REFERENCES matiere (idm)');
        $this->addSql('ALTER TABLE matiere ADD CONSTRAINT FK_9014574A9C30ADCC FOREIGN KEY (idplandetude_id) REFERENCES plandetude (id)');
        $this->addSql('ALTER TABLE presence ADD CONSTRAINT FK_6977C7A5EC96170C FOREIGN KEY (idClasse) REFERENCES classe (idClasse)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64915E0755A FOREIGN KEY (IdClasse) REFERENCES classe (idClasse)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cour DROP FOREIGN KEY FK_A71F964F80AD3CB8');
        $this->addSql('ALTER TABLE matiere DROP FOREIGN KEY FK_9014574A9C30ADCC');
        $this->addSql('ALTER TABLE presence DROP FOREIGN KEY FK_6977C7A5EC96170C');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64915E0755A');
        $this->addSql('DROP TABLE classe');
        $this->addSql('DROP TABLE cour');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE plandetude');
        $this->addSql('DROP TABLE presence');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
