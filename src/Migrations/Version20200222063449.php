<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200222063449 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE niveau (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE etudiant ADD niveau_id INT DEFAULT NULL, DROP niveau');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E3B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_717E22E3B3E9C81 ON etudiant (niveau_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E3B3E9C81');
        $this->addSql('DROP TABLE niveau');
        $this->addSql('DROP INDEX IDX_717E22E3B3E9C81 ON etudiant');
        $this->addSql('ALTER TABLE etudiant ADD niveau VARCHAR(100) DEFAULT NULL COLLATE utf8mb4_unicode_ci, DROP niveau_id');
    }
}
