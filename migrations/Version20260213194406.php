<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260213194406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE flow (id INT AUTO_INCREMENT NOT NULL, hash_id VARCHAR(255) NOT NULL, `key` VARCHAR(255) NOT NULL, first_seen DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_seen DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', asn JSON NOT NULL COMMENT \'(DC2Type:json)\', geo_ip JSON NOT NULL COMMENT \'(DC2Type:json)\', info VARCHAR(255) DEFAULT NULL, local_mac VARCHAR(255) DEFAULT NULL, l4proto VARCHAR(255) DEFAULT NULL, l7proto VARCHAR(255) DEFAULT NULL, bytes BIGINT NOT NULL, local_ip VARCHAR(255) NOT NULL, local_port INT NOT NULL, local_name VARCHAR(255) DEFAULT NULL, remote_ip VARCHAR(255) NOT NULL, remote_port INT NOT NULL, remote_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE host (id INT AUTO_INCREMENT NOT NULL, first_seen DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_seen DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ip VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, mac VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE id_entity (entity_id VARCHAR(255) NOT NULL, id VARCHAR(255) NOT NULL, expiry DATETIME NOT NULL, PRIMARY KEY(entity_id, id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE log (id INT AUTO_INCREMENT NOT NULL, channel VARCHAR(255) NOT NULL, level INT NOT NULL, message LONGTEXT NOT NULL, time DATETIME NOT NULL, details JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE flow');
        $this->addSql('DROP TABLE host');
        $this->addSql('DROP TABLE id_entity');
        $this->addSql('DROP TABLE log');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
