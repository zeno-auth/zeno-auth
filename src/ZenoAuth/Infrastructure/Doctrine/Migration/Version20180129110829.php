<?php declare(strict_types=1);

namespace ZenoAuth\Infrastructure\Doctrine\Migration;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180129110829 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE zeno_auth_access_tokens (id UUID NOT NULL, client_id UUID NOT NULL, user_id UUID NOT NULL, token TEXT NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ttl INT NOT NULL, scopes TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_567B530319EB6921 ON zeno_auth_access_tokens (client_id)');
        $this->addSql('CREATE INDEX IDX_567B5303A76ED395 ON zeno_auth_access_tokens (user_id)');
        $this->addSql('CREATE INDEX IDX_567B5303F9D83E2 ON zeno_auth_access_tokens (expires_at)');
        $this->addSql('COMMENT ON COLUMN zeno_auth_access_tokens.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN zeno_auth_access_tokens.client_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN zeno_auth_access_tokens.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE zeno_auth_authorization_codes (id UUID NOT NULL, client_id UUID NOT NULL, user_id UUID NOT NULL, redirect_uri TEXT NOT NULL, state TEXT DEFAULT NULL, token TEXT NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ttl INT NOT NULL, scopes TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_269A11DF19EB6921 ON zeno_auth_authorization_codes (client_id)');
        $this->addSql('CREATE INDEX IDX_269A11DFA76ED395 ON zeno_auth_authorization_codes (user_id)');
        $this->addSql('CREATE INDEX IDX_269A11DFF9D83E2 ON zeno_auth_authorization_codes (expires_at)');
        $this->addSql('COMMENT ON COLUMN zeno_auth_authorization_codes.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN zeno_auth_authorization_codes.client_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN zeno_auth_authorization_codes.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE zeno_auth_refresh_tokens (id UUID NOT NULL, access_token_id UUID DEFAULT NULL, client_id UUID NOT NULL, user_id UUID NOT NULL, token TEXT NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ttl INT NOT NULL, scopes TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C07CE32B2CCB2688 ON zeno_auth_refresh_tokens (access_token_id)');
        $this->addSql('CREATE INDEX IDX_C07CE32B19EB6921 ON zeno_auth_refresh_tokens (client_id)');
        $this->addSql('CREATE INDEX IDX_C07CE32BA76ED395 ON zeno_auth_refresh_tokens (user_id)');
        $this->addSql('CREATE INDEX IDX_C07CE32BF9D83E2 ON zeno_auth_refresh_tokens (expires_at)');
        $this->addSql('COMMENT ON COLUMN zeno_auth_refresh_tokens.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN zeno_auth_refresh_tokens.access_token_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN zeno_auth_refresh_tokens.client_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN zeno_auth_refresh_tokens.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE zeno_auth_access_tokens ADD CONSTRAINT FK_567B530319EB6921 FOREIGN KEY (client_id) REFERENCES zeno_auth_clients (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE zeno_auth_access_tokens ADD CONSTRAINT FK_567B5303A76ED395 FOREIGN KEY (user_id) REFERENCES zeno_auth_users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE zeno_auth_authorization_codes ADD CONSTRAINT FK_269A11DF19EB6921 FOREIGN KEY (client_id) REFERENCES zeno_auth_clients (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE zeno_auth_authorization_codes ADD CONSTRAINT FK_269A11DFA76ED395 FOREIGN KEY (user_id) REFERENCES zeno_auth_users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE zeno_auth_refresh_tokens ADD CONSTRAINT FK_C07CE32B2CCB2688 FOREIGN KEY (access_token_id) REFERENCES zeno_auth_access_tokens (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE zeno_auth_refresh_tokens ADD CONSTRAINT FK_C07CE32B19EB6921 FOREIGN KEY (client_id) REFERENCES zeno_auth_clients (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE zeno_auth_refresh_tokens ADD CONSTRAINT FK_C07CE32BA76ED395 FOREIGN KEY (user_id) REFERENCES zeno_auth_users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE zeno_auth_clients ALTER redirect_uris DROP DEFAULT');
        $this->addSql('ALTER TABLE zeno_auth_clients ALTER redirect_uris DROP NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE zeno_auth_refresh_tokens DROP CONSTRAINT FK_C07CE32B2CCB2688');
        $this->addSql('DROP TABLE zeno_auth_access_tokens');
        $this->addSql('DROP TABLE zeno_auth_authorization_codes');
        $this->addSql('DROP TABLE zeno_auth_refresh_tokens');
        $this->addSql('ALTER TABLE zeno_auth_clients ALTER redirect_uris DROP DEFAULT');
        $this->addSql('ALTER TABLE zeno_auth_clients ALTER redirect_uris SET NOT NULL');
    }
}
