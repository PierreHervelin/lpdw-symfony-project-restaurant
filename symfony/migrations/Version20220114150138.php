<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220114150138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_D34A04ADB1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchase_order (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, restaurant_id INT NOT NULL, purchase_order_id VARCHAR(255) NOT NULL, total_price DOUBLE PRECISION NOT NULL, INDEX IDX_21E210B2A76ED395 (user_id), INDEX IDX_21E210B2B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchase_order_line (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, purchase_order_id INT NOT NULL, quantity INT NOT NULL, UNIQUE INDEX UNIQ_90D6D92B4584665A (product_id), INDEX IDX_90D6D92BA45D7E6A (purchase_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant_user (restaurant_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_4F85462DB1E7706E (restaurant_id), INDEX IDX_4F85462DA76ED395 (user_id), PRIMARY KEY(restaurant_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE purchase_order ADD CONSTRAINT FK_21E210B2A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE purchase_order ADD CONSTRAINT FK_21E210B2B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE purchase_order_line ADD CONSTRAINT FK_90D6D92B4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE purchase_order_line ADD CONSTRAINT FK_90D6D92BA45D7E6A FOREIGN KEY (purchase_order_id) REFERENCES purchase_order (id)');
        $this->addSql('ALTER TABLE restaurant_user ADD CONSTRAINT FK_4F85462DB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE restaurant_user ADD CONSTRAINT FK_4F85462DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD firstname VARCHAR(180) NOT NULL, ADD lastname VARCHAR(180) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase_order_line DROP FOREIGN KEY FK_90D6D92B4584665A');
        $this->addSql('ALTER TABLE purchase_order_line DROP FOREIGN KEY FK_90D6D92BA45D7E6A');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADB1E7706E');
        $this->addSql('ALTER TABLE purchase_order DROP FOREIGN KEY FK_21E210B2B1E7706E');
        $this->addSql('ALTER TABLE restaurant_user DROP FOREIGN KEY FK_4F85462DB1E7706E');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE purchase_order');
        $this->addSql('DROP TABLE purchase_order_line');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE restaurant_user');
        $this->addSql('ALTER TABLE `user` DROP firstname, DROP lastname');
    }
}
