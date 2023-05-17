<?php
/**
 * Description du fichier : Ce fichier contient l'entity Seismes
 *
 * @category   Fonctions controller API
 * @package    App
 * @subpackage Entity
 * @author     Elouan Teissere
 * @version    1.0 - 08/05/2023
 *
 */
namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Seismes
 *
 * @ORM\Table(name="seismes")
 * @ORM\Entity(repositoryClass="App\Repository\SeismesRepository")
 */
class Seismes
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="instant", type="datetime", nullable=false)
     */
    private $instant;

    /**
     * @var float|null
     *
     * @ORM\Column(name="lat", type="float", precision=10, scale=0, nullable=true, options={"default"="NULL"})
     */
    private $lat = NULL;

    /**
     * @var float|null
     *
     * @ORM\Column(name="lon", type="float", precision=10, scale=0, nullable=true, options={"default"="NULL"})
     */
    private $lon = NULL;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pays", type="string", length=50, nullable=true, options={"default"="NULL"})
     */
    private $pays = 'NULL';

    /**
     * @var float|null
     *
     * @ORM\Column(name="mag", type="float", precision=10, scale=0, nullable=true, options={"default"="NULL"})
     */
    private $mag = NULL;

    /**
     * @var float
     *
     * @ORM\Column(name="profondeur", type="float", precision=10, scale=0, nullable=false)
     */
    private $profondeur;

    public function __toString()
    {
        return $this->pays;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInstant(): ?\DateTimeInterface
    {
        return $this->instant;
    }

    public function setInstant(\DateTimeInterface $instant): self
    {
        $this->instant = $instant;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(?float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLon(): ?float
    {
        return $this->lon;
    }

    public function setLon(?float $lon): self
    {
        $this->lon = $lon;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getMag(): ?float
    {
        return $this->mag;
    }

    public function setMag(?float $mag): self
    {
        $this->mag = $mag;

        return $this;
    }

    public function getProfondeur(): ?float
    {
        return $this->profondeur;
    }

    public function setProfondeur(float $profondeur): self
    {
        $this->profondeur = $profondeur;

        return $this;
    }


}
