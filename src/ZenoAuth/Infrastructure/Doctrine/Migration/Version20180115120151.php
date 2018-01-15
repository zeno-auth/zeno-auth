<?php declare(strict_types=1);

namespace ZenoAuth\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180115120151 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE zeno_auth_roles (id UUID NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62FD5EA85E237E06 ON zeno_auth_roles (name)');
        $this->addSql('COMMENT ON COLUMN zeno_auth_roles.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE zeno_auth_users (id UUID NOT NULL, username VARCHAR(10) NOT NULL, password TEXT DEFAULT NULL, email VARCHAR(50) NOT NULL, last_login_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C040D586F85E0677 ON zeno_auth_users (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C040D586E7927C74 ON zeno_auth_users (email)');
        $this->addSql('COMMENT ON COLUMN zeno_auth_users.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN zeno_auth_users.password IS \'(DC2Type:json_object)\'');
        $this->addSql('CREATE TABLE zeno_auth_user_role (user_id UUID NOT NULL, role_id UUID NOT NULL, PRIMARY KEY(user_id, role_id))');
        $this->addSql('CREATE INDEX IDX_7B35DBE6A76ED395 ON zeno_auth_user_role (user_id)');
        $this->addSql('CREATE INDEX IDX_7B35DBE6D60322AC ON zeno_auth_user_role (role_id)');
        $this->addSql('COMMENT ON COLUMN zeno_auth_user_role.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN zeno_auth_user_role.role_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE zeno_auth_user_role ADD CONSTRAINT FK_7B35DBE6A76ED395 FOREIGN KEY (user_id) REFERENCES zeno_auth_users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE zeno_auth_user_role ADD CONSTRAINT FK_7B35DBE6D60322AC FOREIGN KEY (role_id) REFERENCES zeno_auth_roles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE zeno_auth_user_role DROP CONSTRAINT FK_7B35DBE6D60322AC');
        $this->addSql('ALTER TABLE zeno_auth_user_role DROP CONSTRAINT FK_7B35DBE6A76ED395');
        $this->addSql('DROP TABLE zeno_auth_roles');
        $this->addSql('DROP TABLE zeno_auth_users');
        $this->addSql('DROP TABLE zeno_auth_user_role');
    }
}
