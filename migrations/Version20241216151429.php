<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241216151429 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE options_cars (options_id INT NOT NULL, cars_id INT NOT NULL, INDEX IDX_8167152A3ADB05F1 (options_id), INDEX IDX_8167152A8702F506 (cars_id), PRIMARY KEY(options_id, cars_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE options_cars ADD CONSTRAINT FK_8167152A3ADB05F1 FOREIGN KEY (options_id) REFERENCES options (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE options_cars ADD CONSTRAINT FK_8167152A8702F506 FOREIGN KEY (cars_id) REFERENCES cars (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE options ADD name VARCHAR(255) NOT NULL, ADD value VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE photos ADD order_by INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE options_cars DROP FOREIGN KEY FK_8167152A3ADB05F1');
        $this->addSql('ALTER TABLE options_cars DROP FOREIGN KEY FK_8167152A8702F506');
        $this->addSql('DROP TABLE options_cars');
        $this->addSql('ALTER TABLE options DROP name, DROP value');
        $this->addSql('ALTER TABLE photos DROP order_by');
    }
}
