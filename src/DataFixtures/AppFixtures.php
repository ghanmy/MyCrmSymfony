<?php

namespace App\DataFixtures;

use App\Entity\Activityarea;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $areaactivity = [
            "Divers",
            "Transports et services connexes",
            "Télécommunication et technologies de l'information",
            "Services et équipements pour la santé",
            "Services aux entreprises",
            "Pétrole et gaz",
            "Mécanique et sous-traitance industrielle",
            "Maison et décoration",
            "Loisirs, tourisme et bien-être",
            "Logistique, manutention et stockage",
            "Informatique, bureautique et NTIC",
            "Impression, papier et édition",
            "Hydraulique et pneumatique",
            "Habillement et industrie textile",
            "Equipements pour la distribution",
            "Environnement",
            "Energie, minerais et matières premières",
            "Emballage et conditionnement",
            "Electricité , électronique et électroménager",
            "Communication, événement et équipements audiovisuels",
            "Chimie, cosmétique et hygiène",
            "Chauffage et climatisation",
            "Caoutchouc et plastique",
            "Biens et équipements d'hôtellerie et de restauration",
            "Biens et équipements d'entreprise",
            "Bâtiment et construction",
            "Artisanat",
            "Analyse, mesure et pesage",
            "Agroalimentaire",
            "Agriculture , élevage et pêche",
            "Administration et organismes"
            ];

        foreach ($areaactivity as $key => $value){
            $activity = new Activityarea();
            $activity->setLabel($value);
            $manager->persist($activity);
        }
        $manager->flush();
    }
}
