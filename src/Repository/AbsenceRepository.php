<?php

namespace App\Repository;

use App\Entity\Absence;
use App\Entity\Etudiant;
use App\Entity\Matiere;
use App\Entity\Semestre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Absence|null find($id, $lockMode = null, $lockVersion = null)
 * @method Absence|null findOneBy(array $criteria, array $orderBy = null)
 * @method Absence[]    findAll()
 * @method Absence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbsenceRepository extends ServiceEntityRepository
{
    /**
     * AbsenceRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Absence::class);
    }

    /**
     * @param Matiere $matiere
     * @param         $anneeCourante
     *
     * @return mixed
     */
    public function getAbsencesMatiere(Matiere $matiere, $anneeCourante)
    {
        return $this->createQueryBuilder('m')
            ->where('m.matiere = :matiere')
            ->andWhere('m.anneeuniversitaire = :annee')
            ->setParameter('matiere', $matiere->getId())
            ->setParameter('annee', $anneeCourante)
            ->orderBy('m.date', 'DESC')
            ->orderBy('m.heure', 'DESC')
            ->getQuery()
            ->getResult();

    }

    /**
     * @param Semestre $semestre
     * @param          $anneeCourante
     *
     * @return mixed
     */
    public function findBySemestre(Semestre $semestre, $anneeCourante)
    {
        return $this->createQueryBuilder('a')
            ->innerJoin(Etudiant::class, 'e', 'WITH', 'a.etudiant =e.id')
            ->where('e.semestre = :semestre')
            ->andWhere('a.anneeuniversitaire = :annee')
            ->setParameter('semestre', $semestre->getId())
            ->setParameter('annee', $anneeCourante)
            ->orderBy('a.date', 'DESC')
            ->orderBy('a.heure', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getAbsencesMatiereArray($matiere, $anneeCourante)
    {

        $absences = $this->getAbsencesMatiere($matiere, $anneeCourante);

        $tab = array();
        dump($absences);
        /** @var Absence $absence */
        foreach ($absences as $absence) {
            $date = $absence->getDate()->format('Y-m-d');
            $heure = $absence->getHeure()->format('H:i');

            if (!array_key_exists($date, $tab)) {
                $tab[$date] = array();
                echo 'date';
            }

            if (!array_key_exists($heure, $tab[$date])) {
                $tab[$date][$heure] = array();
                echo 'heure';
            }

            $tab[$date][$heure][] = $absence->getEtudiant()->getId();
        }

        return $tab;

    }

}
