<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220929213710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonne (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, status TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE achat (id INT NOT NULL, commande_id INT NOT NULL, UNIQUE INDEX UNIQ_26A9845682EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, auteur_id INT NOT NULL, categorie_article_id INT NOT NULL, titre VARCHAR(255) NOT NULL, contenu TEXT NOT NULL, statut TINYINT(1) NOT NULL, lisibilite TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', published_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', refused TINYINT(1) DEFAULT NULL, INDEX IDX_23A0E6660BB6FE6 (auteur_id), INDEX IDX_23A0E66EC5D4C30 (categorie_article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_article (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_evenement (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_produit (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, reference VARCHAR(255) NOT NULL, date DATE NOT NULL, etat VARCHAR(255) NOT NULL, prix_total INT NOT NULL, adresse VARCHAR(255) NOT NULL, INDEX IDX_6EEAA67D19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, evenement_id INT DEFAULT NULL, article_id INT DEFAULT NULL, sujet_id INT DEFAULT NULL, content LONGTEXT NOT NULL, date DATE NOT NULL, is_visible TINYINT(1) NOT NULL, INDEX IDX_67F068BCA76ED395 (user_id), INDEX IDX_67F068BCFD02F13 (evenement_id), INDEX IDX_67F068BC7294869C (article_id), INDEX IDX_67F068BC7C4D497E (sujet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cotisation (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, montant INT NOT NULL, solde INT NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', etat TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cotisation_membre (cotisation_id INT NOT NULL, membre_id INT NOT NULL, INDEX IDX_F300D9E23EAA84B1 (cotisation_id), INDEX IDX_F300D9E26A99F74A (membre_id), PRIMARY KEY(cotisation_id, membre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cotisation_transaction (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, promotion VARCHAR(255) NOT NULL, pays VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, date DATE NOT NULL, etat VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE depot (id INT NOT NULL, reference VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE detail_commande (id INT AUTO_INCREMENT NOT NULL, commande_id INT NOT NULL, produit_id INT NOT NULL, quantite INT NOT NULL, prix INT NOT NULL, INDEX IDX_98344FA682EA2E54 (commande_id), INDEX IDX_98344FA6F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, proprietaire_id INT NOT NULL, nom VARCHAR(255) NOT NULL, domaine VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, adresses LONGTEXT NOT NULL, slug VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, INDEX IDX_D19FA6076C50E4A (proprietaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, date DATE NOT NULL, start_at TIME NOT NULL, end_at TIME NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_B26681EBCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, evenement_id INT DEFAULT NULL, article_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, INDEX IDX_C53D045FF347EFB (produit_id), INDEX IDX_C53D045FFD02F13 (evenement_id), INDEX IDX_C53D045F7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE letter (id INT AUTO_INCREMENT NOT NULL, content TEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', titre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membre (id INT NOT NULL, promotion VARCHAR(255) NOT NULL, pays VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, statut TINYINT(1) NOT NULL, cv VARCHAR(255) DEFAULT NULL, profile VARCHAR(255) DEFAULT NULL, profession VARCHAR(255) DEFAULT NULL, secteur VARCHAR(255) DEFAULT NULL, entreprise VARCHAR(255) DEFAULT NULL, role_amicale VARCHAR(255) DEFAULT NULL, bac VARCHAR(25) DEFAULT NULL, univ VARCHAR(255) DEFAULT NULL, diplome VARCHAR(255) DEFAULT NULL, experience VARCHAR(255) DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, bio LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membre_bureau (id INT AUTO_INCREMENT NOT NULL, fonction_id INT NOT NULL, nom_complet VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, etat TINYINT(1) NOT NULL, img VARCHAR(255) NOT NULL, INDEX IDX_8C664B7D57889920 (fonction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre_emplois (id INT AUTO_INCREMENT NOT NULL, membre_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, domaine VARCHAR(255) NOT NULL, create_at DATE NOT NULL, end_at DATE NOT NULL, description LONGTEXT NOT NULL, slug VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, INDEX IDX_EAC907996A99F74A (membre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page_about (id INT AUTO_INCREMENT NOT NULL, mission_titre LONGTEXT NOT NULL, mission_text LONGTEXT NOT NULL, mot_titre VARCHAR(255) NOT NULL, mot_contenu LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page_accueil (id INT AUTO_INCREMENT NOT NULL, carousel_titre1 VARCHAR(255) DEFAULT NULL, carousel_titre2 VARCHAR(255) DEFAULT NULL, carousel_titre3 VARCHAR(255) NOT NULL, carousel_text1 LONGTEXT DEFAULT NULL, carousel_text2 LONGTEXT DEFAULT NULL, carousel_text3 LONGTEXT DEFAULT NULL, carousel_image1 VARCHAR(255) DEFAULT NULL, carousel_image2 VARCHAR(255) DEFAULT NULL, carousel_image3 VARCHAR(255) DEFAULT NULL, mission_titre VARCHAR(255) NOT NULL, mission_text LONGTEXT NOT NULL, chiffre_alumni VARCHAR(255) NOT NULL, chiffre_projet VARCHAR(255) NOT NULL, chiffre_fonds VARCHAR(255) NOT NULL, chiffre_alumni_text VARCHAR(255) NOT NULL, chiffre_projet_text VARCHAR(255) NOT NULL, chiffre_fonds_text VARCHAR(255) NOT NULL, entreprise_titre VARCHAR(255) NOT NULL, entreprise_texte LONGTEXT NOT NULL, temoignage_auteur1 VARCHAR(255) NOT NULL, temoignage_auteur2 VARCHAR(255) NOT NULL, temoignage_auteur3 VARCHAR(255) NOT NULL, temoignage_auteur4 VARCHAR(255) NOT NULL, temoignage_titre1 LONGTEXT NOT NULL, temoignage_titre2 LONGTEXT NOT NULL, temoignage_text1 LONGTEXT NOT NULL, temoignage_text2 LONGTEXT NOT NULL, temoignage_text4 LONGTEXT NOT NULL, temoignage_text3 LONGTEXT NOT NULL, membre_titre VARCHAR(255) NOT NULL, membre_text LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partenaire (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, logo VARCHAR(255) NOT NULL, etat TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partenariat (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, entreprise VARCHAR(255) DEFAULT NULL, mail VARCHAR(255) NOT NULL, requete LONGTEXT NOT NULL, date DATETIME NOT NULL, etat VARCHAR(255) NOT NULL, reponse LONGTEXT DEFAULT NULL, date_reponse DATETIME DEFAULT NULL, telephone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE postes_bureau (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, prix INT NOT NULL, qte_stock INT NOT NULL, etat TINYINT(1) NOT NULL, description LONGTEXT NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_29A5EC27BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE realisation (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password (id INT AUTO_INCREMENT NOT NULL, mail VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, mode VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sujet (id INT AUTO_INCREMENT NOT NULL, auteur_id INT NOT NULL, thematique_id INT NOT NULL, contenu VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', published_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', etat TINYINT(1) NOT NULL, lisibilite TINYINT(1) NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_2E13599D60BB6FE6 (auteur_id), INDEX IDX_2E13599D476556AF (thematique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE super_admin (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE thematique (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, wallet_id INT NOT NULL, date DATETIME NOT NULL, montant DOUBLE PRECISION NOT NULL, type VARCHAR(255) NOT NULL, dtype VARCHAR(255) NOT NULL, INDEX IDX_723705D1712520F3 (wallet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, dtype VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wallet (id INT AUTO_INCREMENT NOT NULL, membre_id INT DEFAULT NULL, solde DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_7C68921F6A99F74A (membre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A9845682EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A98456BF396750 FOREIGN KEY (id) REFERENCES transaction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE admin ADD CONSTRAINT FK_880E0D76BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6660BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66EC5D4C30 FOREIGN KEY (categorie_article_id) REFERENCES categorie_article (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D19EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCFD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC7C4D497E FOREIGN KEY (sujet_id) REFERENCES sujet (id)');
        $this->addSql('ALTER TABLE cotisation_membre ADD CONSTRAINT FK_F300D9E23EAA84B1 FOREIGN KEY (cotisation_id) REFERENCES cotisation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cotisation_membre ADD CONSTRAINT FK_F300D9E26A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cotisation_transaction ADD CONSTRAINT FK_59E91927BF396750 FOREIGN KEY (id) REFERENCES transaction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBCBF396750 FOREIGN KEY (id) REFERENCES transaction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE detail_commande ADD CONSTRAINT FK_98344FA682EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE detail_commande ADD CONSTRAINT FK_98344FA6F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE entreprise ADD CONSTRAINT FK_D19FA6076C50E4A FOREIGN KEY (proprietaire_id) REFERENCES membre (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_evenement (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FFD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE membre ADD CONSTRAINT FK_F6B4FB29BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE membre_bureau ADD CONSTRAINT FK_8C664B7D57889920 FOREIGN KEY (fonction_id) REFERENCES postes_bureau (id)');
        $this->addSql('ALTER TABLE offre_emplois ADD CONSTRAINT FK_EAC907996A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_produit (id)');
        $this->addSql('ALTER TABLE sujet ADD CONSTRAINT FK_2E13599D60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sujet ADD CONSTRAINT FK_2E13599D476556AF FOREIGN KEY (thematique_id) REFERENCES thematique (id)');
        $this->addSql('ALTER TABLE super_admin ADD CONSTRAINT FK_BC8C2783BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE wallet ADD CONSTRAINT FK_7C68921F6A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC7294869C');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F7294869C');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66EC5D4C30');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EBCF5E72D');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27BCF5E72D');
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A9845682EA2E54');
        $this->addSql('ALTER TABLE detail_commande DROP FOREIGN KEY FK_98344FA682EA2E54');
        $this->addSql('ALTER TABLE cotisation_membre DROP FOREIGN KEY FK_F300D9E23EAA84B1');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCFD02F13');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FFD02F13');
        $this->addSql('ALTER TABLE cotisation_membre DROP FOREIGN KEY FK_F300D9E26A99F74A');
        $this->addSql('ALTER TABLE entreprise DROP FOREIGN KEY FK_D19FA6076C50E4A');
        $this->addSql('ALTER TABLE offre_emplois DROP FOREIGN KEY FK_EAC907996A99F74A');
        $this->addSql('ALTER TABLE wallet DROP FOREIGN KEY FK_7C68921F6A99F74A');
        $this->addSql('ALTER TABLE membre_bureau DROP FOREIGN KEY FK_8C664B7D57889920');
        $this->addSql('ALTER TABLE detail_commande DROP FOREIGN KEY FK_98344FA6F347EFB');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FF347EFB');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC7C4D497E');
        $this->addSql('ALTER TABLE sujet DROP FOREIGN KEY FK_2E13599D476556AF');
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A98456BF396750');
        $this->addSql('ALTER TABLE cotisation_transaction DROP FOREIGN KEY FK_59E91927BF396750');
        $this->addSql('ALTER TABLE depot DROP FOREIGN KEY FK_47948BBCBF396750');
        $this->addSql('ALTER TABLE admin DROP FOREIGN KEY FK_880E0D76BF396750');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6660BB6FE6');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455BF396750');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D19EB6921');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA76ED395');
        $this->addSql('ALTER TABLE membre DROP FOREIGN KEY FK_F6B4FB29BF396750');
        $this->addSql('ALTER TABLE sujet DROP FOREIGN KEY FK_2E13599D60BB6FE6');
        $this->addSql('ALTER TABLE super_admin DROP FOREIGN KEY FK_BC8C2783BF396750');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1712520F3');
        $this->addSql('DROP TABLE abonne');
        $this->addSql('DROP TABLE achat');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE categorie_article');
        $this->addSql('DROP TABLE categorie_evenement');
        $this->addSql('DROP TABLE categorie_produit');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE cotisation');
        $this->addSql('DROP TABLE cotisation_membre');
        $this->addSql('DROP TABLE cotisation_transaction');
        $this->addSql('DROP TABLE demande');
        $this->addSql('DROP TABLE depot');
        $this->addSql('DROP TABLE detail_commande');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE letter');
        $this->addSql('DROP TABLE membre');
        $this->addSql('DROP TABLE membre_bureau');
        $this->addSql('DROP TABLE offre_emplois');
        $this->addSql('DROP TABLE page_about');
        $this->addSql('DROP TABLE page_accueil');
        $this->addSql('DROP TABLE partenaire');
        $this->addSql('DROP TABLE partenariat');
        $this->addSql('DROP TABLE postes_bureau');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE realisation');
        $this->addSql('DROP TABLE reset_password');
        $this->addSql('DROP TABLE sujet');
        $this->addSql('DROP TABLE super_admin');
        $this->addSql('DROP TABLE thematique');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE wallet');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
