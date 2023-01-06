<?php

namespace App\Security\Authorization\Voter;

use App\Admin\AbstractAdmin;
use App\Model\Entity\User\OwnedEntityInterface;
use LogicException;
use Psr\Log\LoggerInterface;
use Sonata\AdminBundle\Admin\Pool;
use Sonata\UserBundle\Model\UserInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Acl\Model\AclProviderInterface;
use Symfony\Component\Security\Acl\Model\ObjectIdentityRetrievalStrategyInterface;
use Symfony\Component\Security\Acl\Model\SecurityIdentityRetrievalStrategyInterface;
use Symfony\Component\Security\Acl\Permission\PermissionMapInterface;
use Symfony\Component\Security\Acl\Voter\AclVoter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Voter denying access to owned entities to not-owner users.
 */
class OwnedEntityAclVoter extends AclVoter
{

    /**
     * {@inheritDoc}
     *
     * @param ContainerInterface $container Application container.
     * @param Security           $security  Security helper.
     */
    public function __construct(
      AclProviderInterface $aclProvider,
      ObjectIdentityRetrievalStrategyInterface $oidRetrievalStrategy,
      SecurityIdentityRetrievalStrategyInterface $sidRetrievalStrategy,
      PermissionMapInterface $permissionMap,
      private Pool $adminPool,
      private Security $security,
      LoggerInterface $logger = null,
      $allowIfObjectIdentityUnavailable = true,
    ) {
        parent::__construct(
          $aclProvider,
          $oidRetrievalStrategy,
          $sidRetrievalStrategy,
          $permissionMap,
          $logger,
          $allowIfObjectIdentityUnavailable
        );
    }

    /**
     * {@inheritDoc}
     */
    public function vote(
      TokenInterface $token,
      $subject,
      array $attributes
    ): int {
        // Super admin access everything.
        if (in_array(
          UserInterface::ROLE_SUPER_ADMIN,
          $this->security->getUser()?->getRoles() ?: []
        )) {
            return self::ACCESS_GRANTED;
        }

        if ($subject instanceof Request
          && $subject->attributes->has('_sonata_admin')
        ) {
            return $this->restrictSonataRequest($subject);
        }

        return self::ACCESS_GRANTED;
    }

    /**
     * Restrict owned entity sonata pages to they owners only.
     *
     * @param Request $request Current request.
     *
     * @return int
     */
    private function restrictSonataRequest(Request $request): int
    {
        /** @var AbstractAdmin $admin */
        $admin = $this->adminPool->getAdminByAdminCode(
          $request->attributes->get('_sonata_admin')
        );
        $admin->setRequest($request);

        try {
            $subject = $admin->getSubject();
        } catch (LogicException) {
            $subject = null;
        }

        if (!$admin->isCurrentRoute('list')
          && $subject instanceof OwnedEntityInterface
          && $subject->getOwner() !== $this->security->getUser()
        ) {
            return self::ACCESS_DENIED;
        }

        return self::ACCESS_GRANTED;
    }

}