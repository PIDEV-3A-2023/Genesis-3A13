<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230503235836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY commande_ibfk_3');
        $this->addSql('ALTER TABLE commande CHANGE id_livre id_livre INT DEFAULT NULL, CHANGE id_client id_client INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DE173B1B8 FOREIGN KEY (id_client) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY commentaire_ibfk_2');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY commentaire_ibfk_1');
        $this->addSql('ALTER TABLE commentaire CHANGE id_client id_client INT DEFAULT NULL, CHANGE id_evenement id_evenement INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCE173B1B8 FOREIGN KEY (id_client) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC8B13D439 FOREIGN KEY (id_evenement) REFERENCES evenement (id_evenement)');
        $this->addSql('ALTER TABLE competition DROP FOREIGN KEY competition_ibfk_1');
        $this->addSql('ALTER TABLE competition CHANGE id_livre id_livre INT DEFAULT NULL');
        $this->addSql('ALTER TABLE competition ADD CONSTRAINT FK_B50A2CB142E60EA9 FOREIGN KEY (id_livre) REFERENCES livre (id_livre)');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY evenement_ibfk_1');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY evenement_ibfk_2');
        $this->addSql('ALTER TABLE evenement CHANGE id_auteur id_auteur INT DEFAULT NULL, CHANGE id_livre id_livre INT DEFAULT NULL');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E236D04AD FOREIGN KEY (id_auteur) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E42E60EA9 FOREIGN KEY (id_livre) REFERENCES livre (id_livre)');
        $this->addSql('ALTER TABLE fidelite DROP FOREIGN KEY fidelite_ibfk_1');
        $this->addSql('ALTER TABLE fidelite CHANGE id_client id_client INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fidelite ADD CONSTRAINT FK_EF425B23E173B1B8 FOREIGN KEY (id_client) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE livre DROP FOREIGN KEY livre_ibfk_2');
        $this->addSql('ALTER TABLE livre DROP FOREIGN KEY livre_ibfk_3');
        $this->addSql('ALTER TABLE livre CHANGE id_auteur id_auteur INT DEFAULT NULL, CHANGE id_categorie id_categorie INT DEFAULT NULL');
        $this->addSql('ALTER TABLE livre ADD CONSTRAINT FK_AC634F99C9486A13 FOREIGN KEY (id_categorie) REFERENCES categorie (id_categorie)');
        $this->addSql('ALTER TABLE livre ADD CONSTRAINT FK_AC634F99236D04AD FOREIGN KEY (id_auteur) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY messagerie_ibfk_2');
        $this->addSql('ALTER TABLE messagerie ADD rating TINYINT(1) DEFAULT 0 NOT NULL, CHANGE id_destinataire id_destinataire INT DEFAULT NULL, CHANGE date_heure date_heure DATETIME NOT NULL');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT FK_14E8F60CDD688AE0 FOREIGN KEY (id_destinataire) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY offre_ibfk_1');
        $this->addSql('ALTER TABLE offre CHANGE id_livre id_livre INT DEFAULT NULL');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F42E60EA9 FOREIGN KEY (id_livre) REFERENCES livre (id_livre)');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY paiement_ibfk_3');
        $this->addSql('ALTER TABLE paiement CHANGE id_client id_client INT DEFAULT NULL');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1EE173B1B8 FOREIGN KEY (id_client) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY panier_ibfk_2');
        $this->addSql('ALTER TABLE panier CHANGE id_livre id_livre INT DEFAULT NULL, CHANGE id_client id_client INT DEFAULT NULL');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2E173B1B8 FOREIGN KEY (id_client) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE panierlivre DROP FOREIGN KEY fk_livre');
        $this->addSql('ALTER TABLE panierlivre DROP FOREIGN KEY fk_panier');
        $this->addSql('ALTER TABLE panierlivre ADD id_panier_livre INT AUTO_INCREMENT NOT NULL, CHANGE id_livre id_livre INT DEFAULT NULL, CHANGE id_panier id_panier INT DEFAULT NULL, ADD PRIMARY KEY (id_panier_livre)');
        $this->addSql('ALTER TABLE panierlivre ADD CONSTRAINT FK_5A01AFB42E60EA9 FOREIGN KEY (id_livre) REFERENCES livre (id_livre)');
        $this->addSql('ALTER TABLE panierlivre ADD CONSTRAINT FK_5A01AFB2FBB81F FOREIGN KEY (id_panier) REFERENCES panier (id_panier)');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY question_ibfk_1');
        $this->addSql('ALTER TABLE question CHANGE id_quiz id_quiz INT DEFAULT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E2F32E690 FOREIGN KEY (id_quiz) REFERENCES quiz (id_quiz)');
        $this->addSql('ALTER TABLE quiz DROP FOREIGN KEY quiz_ibfk_2');
        $this->addSql('ALTER TABLE quiz DROP FOREIGN KEY quiz_ibfk_3');
        $this->addSql('ALTER TABLE quiz CHANGE id_livre id_livre INT DEFAULT NULL, CHANGE id_competition id_competition INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA92AD18E146 FOREIGN KEY (id_competition) REFERENCES competition (id_competition)');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA9242E60EA9 FOREIGN KEY (id_livre) REFERENCES livre (id_livre)');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY fk_user_id');
        $this->addSql('ALTER TABLE reclamation CHANGE image image LONGBLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404A76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY reservation_ibfk_1');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY reservation_ibfk_2');
        $this->addSql('ALTER TABLE reservation CHANGE id_ticket id_ticket INT DEFAULT NULL, CHANGE id_evenement id_evenement INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955B197184E FOREIGN KEY (id_ticket) REFERENCES ticket (id_ticket)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849558B13D439 FOREIGN KEY (id_evenement) REFERENCES evenement (id_evenement)');
        $this->addSql('ALTER TABLE resulat_quiz DROP FOREIGN KEY resulat_quiz_ibfk_1');
        $this->addSql('ALTER TABLE resulat_quiz DROP FOREIGN KEY resulat_quiz_ibfk_2');
        $this->addSql('ALTER TABLE resulat_quiz CHANGE id_client id_client INT DEFAULT NULL, CHANGE id_quiz id_quiz INT DEFAULT NULL');
        $this->addSql('ALTER TABLE resulat_quiz ADD CONSTRAINT FK_304E9B992F32E690 FOREIGN KEY (id_quiz) REFERENCES quiz (id_quiz)');
        $this->addSql('ALTER TABLE resulat_quiz ADD CONSTRAINT FK_304E9B99E173B1B8 FOREIGN KEY (id_client) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE utilisateur CHANGE roles roles LONGTEXT DEFAULT NULL, CHANGE authCode authCode LONGTEXT NOT NULL, CHANGE resetToken resetToken LONGTEXT NOT NULL, CHANGE lastConnection lastConnection DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DE173B1B8');
        $this->addSql('ALTER TABLE commande CHANGE id_client id_client INT NOT NULL, CHANGE id_livre id_livre INT NOT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT commande_ibfk_3 FOREIGN KEY (id_client) REFERENCES utilisateur (id_utilisateur) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCE173B1B8');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC8B13D439');
        $this->addSql('ALTER TABLE commentaire CHANGE id_client id_client INT NOT NULL, CHANGE id_evenement id_evenement INT NOT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT commentaire_ibfk_2 FOREIGN KEY (id_evenement) REFERENCES evenement (id_evenement) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT commentaire_ibfk_1 FOREIGN KEY (id_client) REFERENCES utilisateur (id_utilisateur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE competition DROP FOREIGN KEY FK_B50A2CB142E60EA9');
        $this->addSql('ALTER TABLE competition CHANGE id_livre id_livre INT NOT NULL');
        $this->addSql('ALTER TABLE competition ADD CONSTRAINT competition_ibfk_1 FOREIGN KEY (id_livre) REFERENCES livre (id_livre) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E236D04AD');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E42E60EA9');
        $this->addSql('ALTER TABLE evenement CHANGE id_auteur id_auteur INT NOT NULL, CHANGE id_livre id_livre INT NOT NULL');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT evenement_ibfk_1 FOREIGN KEY (id_auteur) REFERENCES utilisateur (id_utilisateur) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT evenement_ibfk_2 FOREIGN KEY (id_livre) REFERENCES livre (id_livre) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fidelite DROP FOREIGN KEY FK_EF425B23E173B1B8');
        $this->addSql('ALTER TABLE fidelite CHANGE id_client id_client INT NOT NULL');
        $this->addSql('ALTER TABLE fidelite ADD CONSTRAINT fidelite_ibfk_1 FOREIGN KEY (id_client) REFERENCES utilisateur (id_utilisateur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livre DROP FOREIGN KEY FK_AC634F99C9486A13');
        $this->addSql('ALTER TABLE livre DROP FOREIGN KEY FK_AC634F99236D04AD');
        $this->addSql('ALTER TABLE livre CHANGE id_categorie id_categorie INT NOT NULL, CHANGE id_auteur id_auteur INT NOT NULL');
        $this->addSql('ALTER TABLE livre ADD CONSTRAINT livre_ibfk_2 FOREIGN KEY (id_categorie) REFERENCES categorie (id_categorie) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livre ADD CONSTRAINT livre_ibfk_3 FOREIGN KEY (id_auteur) REFERENCES utilisateur (id_utilisateur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY FK_14E8F60CDD688AE0');
        $this->addSql('ALTER TABLE messagerie DROP rating, CHANGE id_destinataire id_destinataire INT NOT NULL, CHANGE date_heure date_heure VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT messagerie_ibfk_2 FOREIGN KEY (id_destinataire) REFERENCES utilisateur (id_utilisateur) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F42E60EA9');
        $this->addSql('ALTER TABLE offre CHANGE id_livre id_livre INT NOT NULL');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT offre_ibfk_1 FOREIGN KEY (id_livre) REFERENCES livre (id_livre) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1EE173B1B8');
        $this->addSql('ALTER TABLE paiement CHANGE id_client id_client INT NOT NULL');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT paiement_ibfk_3 FOREIGN KEY (id_client) REFERENCES utilisateur (id_utilisateur) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2E173B1B8');
        $this->addSql('ALTER TABLE panier CHANGE id_livre id_livre INT NOT NULL, CHANGE id_client id_client INT NOT NULL');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT panier_ibfk_2 FOREIGN KEY (id_client) REFERENCES utilisateur (id_utilisateur) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE panierlivre MODIFY id_panier_livre INT NOT NULL');
        $this->addSql('ALTER TABLE panierlivre DROP FOREIGN KEY FK_5A01AFB42E60EA9');
        $this->addSql('ALTER TABLE panierlivre DROP FOREIGN KEY FK_5A01AFB2FBB81F');
        $this->addSql('DROP INDEX `primary` ON panierlivre');
        $this->addSql('ALTER TABLE panierlivre DROP id_panier_livre, CHANGE id_livre id_livre INT NOT NULL, CHANGE id_panier id_panier INT NOT NULL');
        $this->addSql('ALTER TABLE panierlivre ADD CONSTRAINT fk_livre FOREIGN KEY (id_livre) REFERENCES livre (id_livre) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panierlivre ADD CONSTRAINT fk_panier FOREIGN KEY (id_panier) REFERENCES panier (id_panier) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E2F32E690');
        $this->addSql('ALTER TABLE question CHANGE id_quiz id_quiz INT NOT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT question_ibfk_1 FOREIGN KEY (id_quiz) REFERENCES quiz (id_quiz) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quiz DROP FOREIGN KEY FK_A412FA92AD18E146');
        $this->addSql('ALTER TABLE quiz DROP FOREIGN KEY FK_A412FA9242E60EA9');
        $this->addSql('ALTER TABLE quiz CHANGE id_competition id_competition INT NOT NULL, CHANGE id_livre id_livre INT NOT NULL');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT quiz_ibfk_2 FOREIGN KEY (id_competition) REFERENCES competition (id_competition) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT quiz_ibfk_3 FOREIGN KEY (id_livre) REFERENCES livre (id_livre) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404A76ED395');
        $this->addSql('ALTER TABLE reclamation CHANGE image image BLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES utilisateur (id_utilisateur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955B197184E');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849558B13D439');
        $this->addSql('ALTER TABLE reservation CHANGE id_ticket id_ticket INT NOT NULL, CHANGE id_evenement id_evenement INT NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT reservation_ibfk_1 FOREIGN KEY (id_ticket) REFERENCES ticket (id_ticket) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT reservation_ibfk_2 FOREIGN KEY (id_evenement) REFERENCES evenement (id_evenement) ON UPDATE CASCADE ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE resulat_quiz DROP FOREIGN KEY FK_304E9B992F32E690');
        $this->addSql('ALTER TABLE resulat_quiz DROP FOREIGN KEY FK_304E9B99E173B1B8');
        $this->addSql('ALTER TABLE resulat_quiz CHANGE id_quiz id_quiz INT NOT NULL, CHANGE id_client id_client INT NOT NULL');
        $this->addSql('ALTER TABLE resulat_quiz ADD CONSTRAINT resulat_quiz_ibfk_1 FOREIGN KEY (id_client) REFERENCES utilisateur (id_utilisateur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE resulat_quiz ADD CONSTRAINT resulat_quiz_ibfk_2 FOREIGN KEY (id_quiz) REFERENCES quiz (id_quiz) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur CHANGE roles roles TEXT DEFAULT NULL, CHANGE resetToken resetToken LONGTEXT DEFAULT NULL, CHANGE authCode authCode LONGTEXT DEFAULT NULL, CHANGE lastConnection lastConnection LONGTEXT DEFAULT NULL');
    }
}
