<?php
/**
 * @package Newscoop
 * @copyright 2011 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Newscoop\News;

/**
 * @MappedSuperclass
 */
abstract class Feed
{
    /**
     * @Id
     * @var string
     */
    protected $id;

    /**
     * @Hash
     * @var array
     */
    protected $configuration = array();

    /**
     * @Date
     * @var DateTime
     */
    protected $updated;

    /**
     * @Boolean
     * @var bool
     */
    protected $isAutoMode = false;

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set configuration
     *
     * @param array $configuration
     * @return void
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Get configuration
     *
     * @return array
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Update feed
     *
     * @param Doctrine\Common\Persistence\ObjectManager
     * @return void
     */
    abstract public function update(\Doctrine\Common\Persistence\ObjectManager $om);

    /**
     * Get updated
     *
     * @return DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Test if is auto mode active
     *
     * @return bool
     */
    public function isAutoMode()
    {
        return (bool) $this->isAutoMode;
    }

    /**
     * Switch mode
     *
     * @return void
     */
    public function switchMode()
    {
        $this->isAutoMode = ! $this->isAutoMode();
    }

    /**
     * Get name
     *
     * @return string
     */
    abstract public function getName();
}
