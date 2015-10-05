<?php

namespace Tec\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Media
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Tec\ServiceBundle\Entity\MediaRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Media
{
    /********************************************************
     *                      ATTRIBUTS                       *
     ********************************************************/
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=100)
     */
    private $alt;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;    
    
    /**
     *
     * GESTION DE L'IMAGE
     */
    private $file; //va contenir le fichier (l'image)

    private $tempFilename; //va contenir le nom du fichier temporairement
    
    /********************************************************
     *                      RELATION TABLES                 *
     ********************************************************/
    
    /**
     * @ORM\OneToOne(targetEntity="Tec\UserBundle\Entity\User", mappedBy="media")
     */
    private $user;
    
    /**
     * @ORM\OneToOne(targetEntity="Categorie", mappedBy="media")
     */
    private $categorie;

    /********************************************************
     *                      GETTER/SETTER                   *
     ********************************************************/
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Media
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Media
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set user
     *
     * @param \Tec\UserBundle\Entity\User $user
     *
     * @return Media
     */
    public function setUser(\Tec\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Tec\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set categorie
     *
     * @param \Tec\ServiceBundle\Entity\Categorie $categorie
     *
     * @return Media
     */
    public function setCategorie(\Tec\ServiceBundle\Entity\Categorie $categorie = null)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \Tec\ServiceBundle\Entity\Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }
    
    /**
     * GESTION DE L'IMAGE
     */
    
    // On modifie le setter de File, pour prendre en compte l'upload d'un fichier lorsqu'il en existe déjà un autre
    public function setFile(UploadedFile $file){
        $this->file = $file;
        // On vérifie si on avait déjà un fichier pour cette entité
        if (null !== $this->path) {
            // On sauvegarde l'extension du fichier pour le supprimer plus tard
            $this->tempFilename = $this->path;        
            // On réinitialise les valeurs des attributs path et alt
            $this->path = null;

            $this->alt = null;
    }
  }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload(){
        // Si jamais il n'y a pas de fichier (champ facultatif)
        if (null === $this->file) {
            return;
        }
        // Le nom du fichier est son id, on doit juste stocker également son extension
        // Pour faire propre, on devrait renommer cet attribut en « extension », plutôt que « path »
        $this->path = $this->getTempFilename()."."."png";
        var_dump($this->path);
        // Et on génère l'attribut alt de la balise <img>, à la valeur du nom du fichier sur le PC de l'internaute
        $this->alt = $this->file->getClientOriginalName();
        
        $this->setTempFilename(null);
    }

    /**
    * @ORM\PostPersist()
    * @ORM\PostUpdate()
    */
    public function upload(){
        //Si jamais il n'y a pas de fichier (champ facultatif)
        if (null === $this->file) {
            return;
        }
        
        //Si le fichier existe on le supprime
        if (file_exists($this->id.$this->path)) {
            unlink($this->id.$this->path);
        }

        // On déplace le fichier envoyé dans le répertoire de notre choix
        $this->file->move($this->getUploadRootDir(), $this->id.$this->path);
    }

    /**
    * @ORM\PreRemove()
    */
    public function preRemoveUpload(){
        // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
        $this->tempFilename = $this->getUploadRootDir().'/'.$this->id.''.$this->path;
    }

    /**
    * @ORM\PostRemove()
    */
    public function removeUpload(){
        // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
        if (file_exists($this->tempFilename)) {
            // On supprime le fichier
            unlink($this->tempFilename);
        }
    }

    public function getUploadDir(){
        // On retourne le chemin relatif vers l'image pour un navigateur
        return 'uploads/img';
    }

    protected function getUploadRootDir(){
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
    
    /**
    * Get file
    * @return string 
    */
    public function getFile(){
        return $this->file;
    }

    /**
    * Set tempFilename
    * @param string $tempFilename
    * @return Image
    */
    public function setTempFilename($tempFilename){
        var_dump($tempFilename);
        $this->tempFilename = $tempFilename;
        return $this;
    }

    /**
    * Get tempFilename
    *
    * @return string 
    */
    public function getTempFilename(){
        return $this->tempFilename;
    }
}
