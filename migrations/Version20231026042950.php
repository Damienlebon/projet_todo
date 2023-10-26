<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231026042950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_93872075E85441D8');
        $this->addSql('DROP INDEX IDX_93872075E85441D8 ON tache');
        $this->addSql('ALTER TABLE tache CHANGE liste_id name_liste_id INT NOT NULL');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_93872075C485B416 FOREIGN KEY (name_liste_id) REFERENCES liste (id)');
        $this->addSql('CREATE INDEX IDX_93872075C485B416 ON tache (name_liste_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_93872075C485B416');
        $this->addSql('DROP INDEX IDX_93872075C485B416 ON tache');
        $this->addSql('ALTER TABLE tache CHANGE name_liste_id liste_id INT NOT NULL');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_93872075E85441D8 FOREIGN KEY (liste_id) REFERENCES liste (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_93872075E85441D8 ON tache (liste_id)');
    }
}
