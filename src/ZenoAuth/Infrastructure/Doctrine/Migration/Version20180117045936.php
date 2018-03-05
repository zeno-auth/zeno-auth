<?php declare(strict_types=1);

namespace ZenoAuth\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180117045936 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE zeno_auth_clients (id UUID NOT NULL, user_id UUID NOT NULL, name VARCHAR(150) NOT NULL, secret CHAR(50) NOT NULL, redirect_uris JSON NOT NULL, allowed_grant_types JSON NOT NULL, trusted BOOLEAN DEFAULT \'false\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B2A8FCE3A76ED395 ON zeno_auth_clients (user_id)');
        $this->addSql('CREATE INDEX IDX_B2A8FCE35CA2E8E5 ON zeno_auth_clients (secret)');
        $this->addSql('COMMENT ON COLUMN zeno_auth_clients.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN zeno_auth_clients.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE zeno_auth_clients ADD CONSTRAINT FK_B2A8FCE3A76ED395 FOREIGN KEY (user_id) REFERENCES zeno_auth_users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE zeno_auth_clients');
    }
}
