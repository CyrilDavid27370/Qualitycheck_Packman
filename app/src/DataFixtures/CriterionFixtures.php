<?php

namespace App\DataFixtures;

use App\Entity\CategoryType;
use App\Entity\Criterion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CriterionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $categoryTypeRepo = $manager->getRepository(CategoryType::class);

        // ── Batterie HV ────────────────────────────────────────
        $batterieType = $categoryTypeRepo->findOneBy(['name' => 'Batterie HV']);
        $criterions = [
            [1,  'Présence fiche GPS'],
            [2,  'Conformité plaque identification SV avec GPS'],
            [3,  'Conformité étiquetage bâche / carton avec GPS'],
            [4,  'Conformité conditionnement (Thermoformé, bâche/carton, doublure cartonée)'],
            [5,  'Ecrimétal sur toutes les tête de vis'],
            [6,  'Etat des connectiques'],
            [7,  'Aspect & propreté : absence de liquide de refroidissement, oxydation...'],
            [8,  'Composition Excel ou Gpro checkée et présente dans le dossier traçabilité'],
            [9,  'Relevé des couples de serrage présent dans le dossier de traçabilité'],
            [10, 'Relevé des couples de serrage résiduel présent dans le dossier de traçabilité (pour le lièvre, pièces MAPA, etc.)'],
            [11, 'Test étanchéité air'],
            [12, 'Test étanchéité eau'],
            [13, 'Tests électriques : réveil'],
            [14, 'Vérification tension des modules'],
            [15, 'Mise en place du faux PLUG service avec collier de sécurité'],
            [16, 'Présence PLUG SD/SW avec la batterie'],
            [17, "Présence du ou des clapets de mise à l'air libre - pastille NITO"],
            [18, 'Présence du ou des bouchons PRD'],
            [19, 'Réalisation du maquettage'],
            [20, 'Instrumentation en cohérence avec GPS'],
            [21, 'Présence du ou des pictogrammes sécurité'],
            [22, "Conformité du montage de l'embase faisceau signal (avant fermeture capot et sur le dossier Ass)"],
            [23, 'Présence du carton T11 + listing'],
            [24, 'Vérifier que les marquages + et - sont indiqués au feutre auprès des embases (prises oranges) de la batterie'],
            [25, "Vérifier que la résistance d'isolement a été effectué, et que sa valeur est correcte"],
            [26, 'Masse des pièces réalisée (hors T11 et une par affaire)'],
            [27, 'Photos des 6 faces identifiées au n° du SV (suivant photos types)'],
        ];
        $this->persistCriterions($manager, $batterieType, $criterions);

        // ── Modules GPEC batteries ─────────────────────────────
        $moduleGpecType = $categoryTypeRepo->findOneBy(['name' => 'Modules GPEC Batteries']);
        $criterions = [
            [1,  'Présence fiche GPS'],
            [2,  'Conformité étiquette identification Module avec GPS'],
            [3,  "Conformité étiquetage bâche / carton avec GPS — Corrélation étiquetage bâche avec CARO"],
            [4,  'Instrumentation en cohérence avec GPS'],
            [5,  'Masse des pièces réalisée'],
            [6,  'Aspect & propreté : absence de particules sur les cellules (photo de chaque face des 16 cellules)'],
            [7,  "Etat des connectiques sur le busbar frame avant (pas d'oxydation, pas de traces de doigts)"],
            [8,  "Etat des connectiques sur le busbar frame arrière (pas d'oxydation, pas de traces de doigts)"],
            [9,  'Composition Excel ou Gpro checkée et présente dans le dossier traçabilité'],
            [10, "Conformité des tests électriques des 16 cellules à l'entrée de ligne"],
            [11, 'Conformité du test EOL'],
            [12, 'Conformité du test de la thermistance'],
            [13, 'Conformité des soudures / carters inférieur et supérieur'],
            [14, 'Vérification alignement des cellules (photo 6 faces)'],
            [15, 'Présence du ou des pictogrammes sécurité (MD 800 V)'],
            [16, 'Vérifier que les marquages (+ / -) sont gravé sur le carter sup du module'],
            [17, 'Vérifier que la résistance d\'isolement par rapport au + et au - a été effectué, et que sa valeur est correcte'],
            [18, 'Vérifier que la résistance interne est correcte'],
            [19, 'Masse du module réalisée'],
            [20, 'Vérification de la présence d\'un isolant de protection sur les 2 bornes + / -'],
            [21, 'Photos des 6 faces identifiées au n° du module'],
            [22, 'Conformité conditionnement (Thermoformé, bâche / carton, doublure cartonée)'],
        ];
        $this->persistCriterions($manager, $moduleGpecType, $criterions);

        // ── Modules PVAL — même template que Modules GPEC ─────
        $modulePvalType = $categoryTypeRepo->findOneBy(['name' => 'Modules PVAL']);
        $this->persistCriterions($manager, $modulePvalType, $criterions);

        // ── Moteur AFM ─────────────────────────────────────────
        $moteurAfmType = $categoryTypeRepo->findOneBy(['name' => 'Moteur AFM']);
        $criterions = [
            [1,  'Présence et Conformité étiquetage bâche avec GPS (machine et T11)'],
            [2,  'Aspect & Propreté : Aspect, Propreté conforme'],
            [3,  'Présence des pions de centrage — Présence des anneaux de levage'],
            [4,  'Vérification état des cosses (photo assemblage) — Vérif. état des connectiques'],
            [5,  'Relevés des couples de serrage présents dans le dossier de traçabilité'],
            [6,  'Etat des connectiques'],
            [7,  'Compos Excel checkées et présentes (dossier & carton T11)'],
            [8,  'Présence photos de contrôle (cf. dossier traçabilité)'],
            [9,  "Plaque d'identification présente sur la machine et en corrélation avec GPS"],
            [10, 'Présence écrimétal sur tête de vis'],
            [11, "Vérifier le bon positionnement des circlips (ct visuel et dossier d'assemblage) — Présence du bouchon VAT"],
            [12, 'Contrôler la présence des tests et mesures électriques (dossier de traçabilité)'],
            [13, "Numéro d'Identification des organes associés à la machine dans le dossier de traçabilité"],
            [14, 'Présence des joints de trappe et du refroidisseur'],
            [15, 'Présence du joint de carter'],
            [16, 'Présence des clapets de mise à l\'air libre (Qt 2) Pastille NITO'],
            [17, 'Bouchonnage Machine (ct visuel)'],
            [18, 'Rapport passage Banc Statique'],
            [19, 'Conformité conditionnement (thermoformé, bâche)'],
            [20, 'Réalisation du maquettage'],
            [21, 'Présence du calculateur dans le TR11'],
            [22, "Présence de l'étiquette avec ou sans huile sur le réducteur (RDO)"],
            [23, "Présence de l'étiquette avec huile sur le PEB"],
            [24, 'Masse des pièces réalisée (hors T11 et une par affaire)'],
            [25, 'Photos des 6 faces identifiées au n° du SV (suivant photos types)'],
        ];
        $this->persistCriterions($manager, $moteurAfmType, $criterions);

        // ── Carter C-MOT ───────────────────────────────────────
        $carterCMotType = $categoryTypeRepo->findOneBy(['name' => 'Carter C-MOT']);
        $criterions = [
            [1,  'Présence du TIV (support instrumenté en cohérence avec GPS)'],
            [2,  'Métrologie Labo documentée et présente'],
            [3,  'Métrologie Atelier documentée et présente'],
            [4,  'Présence et Conformité étiquetage bâche avec GPS (machine et T11)'],
            [5,  'Aspect & Propreté : Aspect, Propreté conforme'],
            [6,  'Présence du pion de centrage — Présence des anneaux de levage'],
            [7,  'Vérification état des cosses (photo assemblage) — Vérif. état des connectiques'],
            [8,  'Relevés des couples de serrage présents dans le dossier de traçabilité'],
            [9,  "Etat des connectiques (présence des inserts), et présence écrou prisonnier"],
            [10, "Présence photo d'encollage (stack)"],
            [11, 'Compos Excel checkées et présentes (dossier & carton T11)'],
            [12, 'Présence photos de contrôle (cf. dossier traçabilité)'],
            [13, "Présence des isolants fond d'encoche (photo dossier assemblage)"],
            [14, "Présence de la colle des bobinots (photo dossier d'assemblage)"],
            [15, 'Absence de loctite dans les trous de graissage'],
            [16, 'Thermistance bloquée à la loctite'],
            [17, 'Contrôle étanchéité circuit de la plaque'],
            [18, 'Identification présente sur la machine et en corrélation avec GPS'],
            [19, 'Présence écrimétal sur tête de vis'],
            [20, 'Présence test av et ap impregnation'],
            [21, 'Contrôler la présence des tests et mesures électriques (plan de controle)'],
            [22, 'Bouchonnage stator (ct visuel)'],
            [23, 'Rapport passage Banc Stator'],
            [24, 'Conformité conditionnement (thermoformé, bâche)'],
            [25, 'Réalisation du maquettage'],
            [26, 'Masse des pièces réalisée (hors T11 et une par affaire)'],
            [27, 'Présence du poids du stator'],
            [28, 'Vérification conditionnement'],
        ];
        $this->persistCriterions($manager, $carterCMotType, $criterions);

        // ── Carter C-FERM — même template que Carter C-MOT ────
        $carterCFermType = $categoryTypeRepo->findOneBy(['name' => 'Carter C-FERM']);
        $this->persistCriterions($manager, $carterCFermType, $criterions);

        // ── Rotor AFM ──────────────────────────────────────────
        $rotorAfmType = $categoryTypeRepo->findOneBy(['name' => 'Rotor AFM']);
        $criterions = [
            [1,  'Présence fiche GPS'],
            [2,  'Conformité étiquetage rotor avec GPS'],
            [3,  'Vérification courbes OK emmanchement roulements'],
            [4,  "Vérification de l'Identification du disque au feutre indélébiles côté rainure de 1 à 12"],
            [5,  "Vérifier l'alignement de la rainure avec le centre de l'aimant N°1"],
            [6,  'Vérifier le sens de la rondelle (Bombé vers le haut)'],
            [7,  "Vérifier la présence du PV d'équilibrage"],
            [8,  'Vérifier le sens des 2 roulements rainure vers le haut'],
            [9,  'Contrôle présence étiquettes avant envoi chez Electrfisico'],
            [10, 'Vérification absence de chocs ; Rayures sur le rotor'],
            [11, 'Vérification absence de bruyance roulements'],
            [12, 'Plan de contrôle : Vérification résultats conforme du plan de contrôle'],
            [13, 'Plan de contrôle : Vérification résultats du champs électrique (RISOL)'],
            [14, 'Présence des photos de contrôle (cf dossier de traçabilité ATOMe)'],
            [15, 'Présence des Photos 6 faces (cf dossier de traçabilité ATOMe)'],
            [16, 'Réalisation du maquettage'],
            [17, 'Masse des pièces réalisée (hors T11 et une par affaire)'],
            [18, 'Prendre le poids du rotor'],
            [19, 'Vérification conditionnement conforme du rotor'],
        ];
        $this->persistCriterions($manager, $rotorAfmType, $criterions);

        // ── Stator AFM — même template que Rotor AFM ──────────
        $statorAfmType = $categoryTypeRepo->findOneBy(['name' => 'Stator AFM']);
        $this->persistCriterions($manager, $statorAfmType, $criterions);

        // ── Moteur GMPE ────────────────────────────────────────
        $moteurGmpeType = $categoryTypeRepo->findOneBy(['name' => 'Moteur GMPE']);
        $criterions = [
            [1,  'Présence fiche GPS'],
            [2,  'Conformité étiquetage bâche avec GPS'],
            [3,  "Conformité avec GPS fiche d'identité placée dans pochette collée sur carton TR"],
            [4,  'Vérification conformité des câbles LEAR'],
            [5,  "Aspect & Propreté : absence d'huile, oxydation..."],
            [6,  'Etat des connectiques'],
            [7,  'Relevés de couple de serrage présents dans le dossier de traçabilité'],
            [8,  'Conformité conditionnement (thermoformé, bâche)'],
            [9,  'Compos Excel checkées et présentes'],
            [10, "Réducteur GMPE avec huile & étiquette correspondante placée sur l'organe"],
            [11, 'Vérification de la mise en place des bouchons obturateur'],
            [12, 'Vérification de la mise en place des câblages'],
            [13, 'Présence écrimétal sur tête de vis'],
            [14, 'Vérification fixation des interlocks du câble triphasé KOSTAL'],
            [15, 'Présence : Joint, reniflard, bouchon joint de différentiel (suivant indication de la gamme)'],
            [16, "Présence du clapet de mise à l'air sur le PEC"],
            [17, 'Réalisation du maquettage'],
            [18, 'Masse des pièces réalisée (hors T11 et une par affaire)'],
            [19, 'Photos des 5 faces identifiées au n° du SV (suivant photos types)'],
        ];
        $this->persistCriterions($manager, $moteurGmpeType, $criterions);

        // ── Rotor GMPE ─────────────────────────────────────────
        $rotorGmpeType = $categoryTypeRepo->findOneBy(['name' => 'Rotor']);
        $criterions = [
            [1,  'Présence fiche GPS'],
            [2,  'Conformité étiquetage rotor avec GPS'],
            [3,  'Vérification courbes OK emmanchement roulements'],
            [4,  'Vérification localisation papiers isolant OK'],
            [5,  'Bobinage : Absence de fils croisés et détendu selon procédure 2 de la FOS contrôle qualité rotor'],
            [6,  'Bobinage : Contrôle conformité bobinage selon procédure 1 de la FOS contrôle qualité rotor'],
            [7,  'Bobinage : Absence de marquage sur le fil selon procédure 2 de la FOS contrôle qualité rotor'],
            [8,  'Sertissage : Vérification visuelle bon état du sertissage'],
            [9,  "Imprégnation : Vérification visuelle bon état de l'imprégnation"],
            [10, 'Equilibrage : Vérification localisation trous équilibrage selon Cahier des Charges'],
            [11, 'Vérification présence circlips et touché visuel'],
            [12, "Vérification absence de chocs ; Rayures sur l'arbre rotor + bagues d'excitation"],
            [13, 'Vérification absence de bruyance roulement'],
            [14, 'Plan de contrôle : Vérification résultats conforme du plan de contrôle'],
            [15, 'Plan de contrôle : Vérification résultats du test électrique'],
            [16, 'Présence des photos de contrôle (cf dossier de traçabilité ATOMe)'],
            [17, 'Vérification de la valeur de la température sur la fiche suiveuse'],
            [18, 'Présence des Photos 6 faces (cf dossier de traçabilité ATOMe)'],
            [19, 'Réalisation du maquettage'],
            [20, 'Masse des pièces réalisée (hors T11 et une par affaire)'],
            [21, 'Vérification conditionnement conforme du rotor'],
        ];
        $this->persistCriterions($manager, $rotorGmpeType, $criterions);

        // ── Stator GMPE — même template que Rotor GMPE ────────
        $statorGmpeType = $categoryTypeRepo->findOneBy(['name' => 'Stator']);
        $this->persistCriterions($manager, $statorGmpeType, $criterions);

        // ── PEB ────────────────────────────────────────────────
        $pebType = $categoryTypeRepo->findOneBy(['name' => 'PEB']);
        $criterions = [
            [1,  'Présence fiche GPS'],
            [2,  "Présence de la plaque d'identification PEB"],
            [3,  'Conformité étiquetage bâche avec GPS — Corrélation étiquetage bâche avec CARO'],
            [4,  "Conformité avec GPS de la fiche d'identité placée dans pochette collée sur carton TR"],
            [5,  "Aspect & Propreté : absence d'huile, oxydation..."],
            [6,  'Etat des connectiques'],
            [7,  'Relevés de couple de serrage présents dans le dossier de traçabilité'],
            [8,  'Compos Excel checkées et présentes (ATOMe)'],
            [9,  'Présence écrimétal sur tête de vis'],
            [10, 'Photos connecteurs RCS présentes'],
            [11, 'Test étanchéité air'],
            [12, "Test étanchéité du circuit d'eau"],
            [13, 'Réveil PEB'],
            [14, 'Test isolation'],
            [15, "Présence du clapet de mise à l'air libre (pastille NITO)"],
            [16, 'Réalisation du maquettage'],
            [17, 'Vérifier le marquage évolution soft'],
            [18, 'Conformité conditionnement (thermoformé, bâche)'],
            [19, 'Présence du carton T11 + listing'],
            [20, 'Masse des pièces réalisée (hors T11 et une par affaire)'],
            [21, 'Réalisation des photos 6 faces'],
        ];
        $this->persistCriterions($manager, $pebType, $criterions);

        // ── Moteur thermique ───────────────────────────────────
        $moteurThermiqueType = $categoryTypeRepo->findOneBy(['name' => 'Moteur thermique']);
        $criterions = [
            [1,  'Rapport passage BAV + présence étiquette sur bâche'],
            [2,  'Présence écrimétal serrage manocontact après BAV'],
            [3,  'Face accessoire en cohérence avec GPS => FNA - SFA - FAA'],
            [4,  'Présence étiquetage bâche (identification SV)'],
            [5,  'Etiquette jaune sur le T11'],
            [6,  'Conformité Tronçon 11 & Tronçon 12 ==> vérification sur listing'],
            [7,  "Présence calculateurs moteur & injection d'urée (si SV concerné) / cohérence entre étiquettes calculateur et collectage"],
            [8,  'Conformité plaquage & corrélation avec GPS'],
            [9,  'Instrumentation en cohérence avec GPS'],
            [10, 'Présence écrimétal sur têtes de vis'],
            [11, 'Présence étiquette moteur en huile'],
            [12, 'Niveau d\'huile conforme / crantage => tirer la jauge'],
            [13, 'Refroidisseur sans fuite'],
            [14, "Absence de trace d'huile sur le carter d'huile"],
            [15, "Bouchonnage moteur en conformité avec gamme d'assemblage"],
            [16, 'Absence de résiduel liquide refroidissement et gazole (retirer les bouchons)'],
            [17, 'Etat des connectiques des capteurs & actionneurs'],
            [18, "Centrage embrayage (outil spécifique attention diversité) + détrompeur orientation friction"],
            [19, 'Dossier de traçabilité : métrologies documentées et présentes, compo Excel checkées et présentes, listings relevés de couples de serrages présents et repérés au n° de SV, présence photos de contrôle'],
            [20, 'Thermoformé pour transport adapté'],
            [21, 'Présence code Datamatrix & codes IMA injecteurs visibles en partie supérieure moteur (version diesel)'],
            [22, 'Vérifier la suppression du roulement villebrequin coté accouplement si moteur associé à une boite automatique (sauf organe DC4)'],
            [23, 'Pompe haute pression => vérification état des embouts arrivée et départ + présence protecteurs (version diesel)'],
            [24, "Adaptation(s) selon compte-rendu maquettage ou réception avec le client"],
            [25, 'Présence dans le dossier de traçabilité des courbes de pré-serrage et serrage bielles réputées conformes'],
            [26, 'Position connecteurs des capteurs'],
            [27, 'Vérification suppression agrafe déverrouillage (ou déclipsage) des colliers fixation tuyaux eau'],
            [28, 'Contrôle présence pions de centrage carter-cylindres / BV'],
            [29, "Présence obturateur d'eau dans carter-cylindres + photo (version essence)"],
            [30, 'Conformité du sanglage moteur / palette (4 points + équerre sur M9T 260 to 282)'],
            [31, 'Réalisation du maquettage conduit SAT (moteur lièvre)'],
            [32, 'Réalisation du maquettage'],
            [33, 'Masse des pièces réalisée (hors T11 et une par affaire)'],
            [34, 'Photos des 6 faces identifiées au n° du SV (suivant photos types)'],
        ];
        $this->persistCriterions($manager, $moteurThermiqueType, $criterions);

        // ── Boîte de vitesses ──────────────────────────────────
        $boiteVitessesType = $categoryTypeRepo->findOneBy(['name' => 'Boîte de vitesse']);
        $criterions = [
            [1,  'Présence fiche GPS'],
            [2,  'Plaque support BV collée en conformité avec GPS et fiche contrat'],
            [3,  "Etiquettage bâche et fiche d'identité sur TR en conformité avec GPS"],
            [4,  'Aspect & Propreté'],
            [5,  'Relevés de couple de serrage présents dans le dossier'],
            [6,  'Conformité conditionnement (thermoformé, bâche)'],
            [7,  'Conformité tronçons 9 à 13 : check interne (fichier excel checké et présent dans le dossier)'],
            [8,  "Vérification de la mise en place d'obturateurs (adhésif / bouchons) au niveau des sorties / présence joint d'étanchéité sortie B.V."],
            [9,  'Présence écrimétal sur tête de vis'],
            [10, 'Présence écrimétal sur le bouchon de vidange'],
            [11, 'Rapport de contrôle métrologie (HPD, ...)'],
            [12, 'Feuille de calculs calage'],
            [13, "Vérifier l'étagement BV avec définition technique"],
            [14, 'Passage contrôle étanchéité'],
            [15, "Boîte livrée sans huile & étiquette correspondante placée sur l'organe"],
            [16, 'Présence pion du démarreur sur carter embrayage / bague fendue sur réducteur'],
            [17, "Adaptation(s) selon compte-rendu maquettage ou réception avec le client"],
            [18, "Présence des joints d'étanchéité sur la machine locobox"],
            [19, 'Passage test banc de fin de chaîne'],
            [20, "Présence du clapet d'air sur la machine"],
            [21, 'Vérification de la conformité des embouts d\'eau sur la BV'],
            [22, "Contrôler le parcours des tuyaux d'huile entre la machine et la pompe à huile"],
            [23, 'Vérification du marquage ME/HSG sur les connecteurs des câbles électriques'],
            [24, 'Les paliers / cannelures de l\'arbre joint au réducteur sans chocs/rouille'],
            [25, 'Présence de 3 circlips / roulement emmanché dans bague aluminium / joint / bague élastique acier'],
            [26, 'Conformité du Tronçon 11 => vérification sur listing présent dans le TR11'],
            [27, 'Présence étiquette jaune Tronçon 11'],
            [28, 'Réalisation du maquettage'],
            [29, 'Masse des pièces réalisée (hors T11 et une par affaire)'],
            [30, 'Photos des 5 faces identifiées au n° du SV (suivant photos types)'],
        ];
        $this->persistCriterions($manager, $boiteVitessesType, $criterions);

        // ── Réducteur GMPE — même template que Boîte de vitesses
        $reducteurType = $categoryTypeRepo->findOneBy(['name' => 'Réducteur']);
        $this->persistCriterions($manager, $reducteurType, $criterions);

        // Un seul flush à la fin pour des performances optimales
        $manager->flush();
    }

    // Méthode privée pour éviter la répétition du code
    private function persistCriterions(
        ObjectManager $manager,
        ?CategoryType $type,
        array $criterions
    ): void {
        if (!$type) {
            return;
        }

        foreach ($criterions as [$itemNumber, $label]) {
            $criterion = new Criterion();
            $criterion->setItemNumber($itemNumber);
            $criterion->setLabel($label);
            $criterion->setCategoryType($type);
            $manager->persist($criterion);
        }
    }

    public function getDependencies(): array
    {
        return [
            DashboardFixtures::class,
        ];
    }
}
