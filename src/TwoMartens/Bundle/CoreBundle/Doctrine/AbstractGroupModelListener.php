<?php

/*
 * (c) Jim Martens <github@2martens.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwoMartens\Bundle\CoreBundle\Doctrine;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use TwoMartens\Bundle\CoreBundle\Group\GroupServiceInterface;
use TwoMartens\Bundle\CoreBundle\Model\Group;
use TwoMartens\Bundle\CoreBundle\Model\OptionCategory;

/**
 * Base group model listener.
 *
 * Overwritten by database specific listeners to specify the events.
 *
 * @author    Jim Martens <github@2martens.de>
 * @copyright 2013-2015 Jim Martens
 */
abstract class AbstractGroupModelListener implements EventSubscriber
{
    /**
     * the group service
     * @var GroupServiceInterface
     */
    private $groupService;

    /**
     * Constructor.
     *
     * @param GroupServiceInterface $groupService
     */
    public function __construct(GroupServiceInterface $groupService)
    {
        $this->groupService = $groupService;
    }

    /**
     * Called on the postLoad event.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        $object = $eventArgs->getObject();
        // only handle our model
        if (!($object instanceof Group)) {
            return;
        }

        // read group option data
        $topLevelCategory = $this->groupService->getOptionsFor(
            $object->getRoleName()
        );
        $categories = $topLevelCategory->getCategories();
        foreach ($categories as $category) {
            $name = $category->getName();
            switch ($name) {
                case 'frontend':
                    $object->setFrontendUserCategory($category);
                    break;
                case 'mod':
                    $object->setFrontendModCategory($category);
                    break;
                case 'acp':
                    $object->setACPCategory($category);
                    break;
            }
        }
    }

    /**
     * Called on the prePersist event.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $object = $eventArgs->getObject();
        // only handle our model
        if (!($object instanceof Group)) {
            return;
        }

        $categories = [
            $object->getFrontendUserCategory(),
            $object->getFrontendModCategory(),
            $object->getACPCategory()
        ];

        $topLevelCategory = new OptionCategory();
        $topLevelCategory->setCategories($categories);
        $this->groupService->setOptionsFor($object->getRoleName(), $topLevelCategory);
    }

    /**
     * Called on the preRemove event.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $object = $eventArgs->getObject();
        // only handle our model
        if (!($object instanceof Group)) {
            return;
        }

        $this->groupService->removeOptionsFor($object->getRoleName());
    }
}
